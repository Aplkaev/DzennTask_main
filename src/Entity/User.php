<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use App\GraphQL\Resolver\User\LoginResolver;
use App\GraphQL\Resolver\User\MeResolver;
use App\GraphQL\Resolver\User\RegisterResolver;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [],
    graphQlOperations: [
        new Query(
            name: 'me',
            resolver: MeResolver::class,
            args: [],
            security: "is_granted('ROLE_USER')"
        ),
        new Query(security: "is_granted('ROLE_ADMIN') or object == user"),

        new Mutation(
            name: 'update',
            security: 'object == user',
            denormalizationContext: ['groups' => ['user:update']],
        ),
        new Mutation(
            name: 'login',
            resolver: LoginResolver::class,
            read: false,
            validate: false,
            write: false,
            denormalizationContext: ['groups' => ['user:auth']],
        ),
        new Mutation(
            name: 'register',
            resolver: RegisterResolver::class,
            read: false,
            denormalizationContext: ['groups' => ['user:auth']],
            validationContext: ['groups' => ['user:register']],
        ),
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 255)]
    #[Groups(['user:auth'])]
    #[Assert\NotBlank(groups: ['user:register'])]
    #[Assert\Email(groups: ['user:register'])]
    private string $email;

    #[ORM\Column(length: 255)]
    #[ApiProperty(readable: false, writable: false)]
    private string $password;

    /**
     * Пароль в открытом виде — только вход для loginUser/registerUser, в БД не хранится.
     */
    #[ApiProperty(readable: false)]
    #[Groups(['user:auth'])]
    #[Assert\NotBlank(groups: ['user:register'])]
    #[Assert\Length(min: 6, groups: ['user:register'])]
    private ?string $plainPassword = null;

    /**
     * JWT — заполняется только в ответе loginUser/registerUser, в БД не хранится.
     */
    #[ApiProperty(writable: false)]
    private ?string $token = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:update'])]
    private ?string $avatarUrl = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Groups(['user:update'])]
    private ?string $timezone = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Groups(['user:update'])]
    private ?string $phone = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Groups(['user:update'])]
    private ?string $telegram = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author', orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false)]
    private Collection $content;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user', orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false)]
    private Collection $notifications;

    /**
     * @var Collection<int, ProjectUser>
     */
    #[ORM\OneToMany(targetEntity: ProjectUser::class, mappedBy: 'user', orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false)]
    private Collection $projectUsers;

    #[ORM\Column(type: 'json')]
    #[ApiProperty(readable: false, writable: false)]
    private array $roles = [];

    public function __construct()
    {
        $this->content = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(?string $telegram): static
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    #[ApiProperty(readable: false)]
    public function getUserIdentifier(): string
    {
        return (string) mb_strtolower($this->email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): static
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(Comment $content): static
    {
        if (!$this->content->contains($content)) {
            $this->content->add($content);
            $content->setAuthor($this);
        }

        return $this;
    }

    public function removeContent(Comment $content): static
    {
        $this->content->removeElement($content);

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        $this->notifications->removeElement($notification);

        return $this;
    }

    /**
     * @return Collection<int, ProjectUser>
     */
    public function getProjectUsers(): Collection
    {
        return $this->projectUsers;
    }

    public function addProjectUser(ProjectUser $projectUser): static
    {
        if (!$this->projectUsers->contains($projectUser)) {
            $this->projectUsers->add($projectUser);
            $projectUser->setUser($this);
        }

        return $this;
    }

    public function removeProjectUser(ProjectUser $projectUser): static
    {
        $this->projectUsers->removeElement($projectUser);

        return $this;
    }
}
