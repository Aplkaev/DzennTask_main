# Интеграции с внешними трекерами задач

## Идея

DzennTask — не замена корпоративным/сторонним таск-трекерам, а личный слой приоритезации и спринтов
поверх них. Источник истины по содержанию задачи (title/description/статус/комментарии) может жить
во внешней системе (Битрикс24, Todoist, Telegram-чат...), а вот приоритет, спринт, kanban-колонка —
всегда только локальные поля, наружу никогда не уходят.

Из этого вытекает разделение полей `Task` на две категории:

- **Синкающиеся** (приходят/уходят через интеграцию): `title`, `description`, `status`, `deadline`, комментарии.
- **Локальные** (существуют только у нас, во внешней системе аналога нет): `priority`, `storyPoints`,
  `kanbanColumn`. Никогда не пушатся наружу — это резко снижает число конфликтов синка, так как
  конфликтовать может только пересечение синкающихся полей.

## Ключевой инвариант: один проект — одна интеграция

**Проект не может быть одновременно привязан к Telegram и к Битрикс24** (или к любым двум провайдерам
сразу). Причина — задачи из разных источников имеют разную семантику (разные статус-модели, разные
владельцы, разная скорость синка), смешивать их в рамках одного проекта — верный способ получить
путаницу и конфликты при синке. Если пользователю нужно вести и Telegram-инбокс, и Битрикс-проект —
это два разных `Project`.

Инвариант обеспечивается на уровне данных: `ProjectIntegration.project_id` — **уникальное** поле
(unique constraint в БД, а не только проверка в коде). У одного `Project` — ноль или одна запись
`ProjectIntegration`.

## Общая модель данных (для всех провайдеров)

**`ProjectIntegration`**
- `id`
- `project_id` — FK на `Project`, **UNIQUE**
- `provider` — enum: `bitrix24`, `telegram`, `google_calendar`, `todoist`
- `status` — enum: `active`, `disabled`, `error` (например, протух refresh-токен)
- `credentials` — токены/ключи доступа (хранить шифрованными, не в открытом виде)
- `createdBy` — User, который подключил интеграцию
- `lastSyncedAt`

**`TaskExternalLink`**
- `id`
- `task_id` — FK на `Task`
- `project_integration_id` — FK на `ProjectIntegration` (провайдер уже известен через неё)
- `externalId`, `externalUrl`
- `rawPayload` (jsonb) — последний сырой снимок ответа API источника, для дебага рассинхрона
- `lastSyncedAt`, `syncStatus`

**Внешние авторы комментариев.** Комментарий может быть от человека, которого нет в системе DzennTask
(коллега в Битриксе, участник Telegram-чата). `Comment.author` сейчас обязательный `ManyToOne` на
`User` — под интеграции его нужно сделать nullable и добавить `externalAuthorName` /
`externalAuthorRef` как альтернативную "гостевую" подпись. Заводить теневых `User` для внешних людей
не стоит — они начнут путаться с проверками доступа (`ProjectUser`, voters).

**Файлы.** Не перекачиваем и не храним у себя — `Attachment` хранит только `externalUrl` + метаданные
(`filename`, `mimeType`, `size`). Исключение — Telegram, где `file_id` живёт недолго и файл нужно
скачать сразу (см. `telegram.md`).

## Единый адаптер под провайдера

Каждый провайдер реализует общий контракт (условно `TaskProviderInterface`):

- `fetchTasks(ProjectIntegration $integration, ?\DateTimeImmutable $since): iterable`
- `pushStatusUpdate(TaskExternalLink $link, TaskStatusEnum $status): void`
- `pushComment(TaskExternalLink $link, Comment $comment): void`
- `handleWebhook(Request $request): void` — не у всех провайдеров есть (см. таблицу ниже)

Новый провайдер = новый класс-адаптер + значение enum `provider`, без изменений в `Task`/`Project`.

## Провайдеры — статус проработки

| Провайдер        | План           | Push-механизм                          | Auth                          | Привязка   |
|-------------------|----------------|------------------------------------------|--------------------------------|------------|
| Битрикс24         | [bitrix24.md](bitrix24.md) — детальный, первый в очереди | Исходящий вебхук (реалтайм) + поллинг fallback | Входящий вебхук (локальное приложение недоступно на тарифе) | Project    |
| Telegram          | [telegram.md](telegram.md) — эскиз | Telegram Bot Webhook                   | Bot Token                      | Project    |
| Todoist           | [todoist.md](todoist.md) — эскиз | Webhooks (App Management Console)      | Personal Access Token / OAuth  | Project    |
| Google Calendar   | [google-calendar.md](google-calendar.md) — эскиз, открытый вопрос по модели | Push notifications (watch/channels)    | OAuth 2.0                      | **User**, не Project (см. файл) |

Битрикс24 — первый, потому что это реальный рабочий кейс автора (Битрикс24 на работе), остальные —
про запас на будущее, чтобы модульность с самого начала проектировалась не "под один провайдер".
