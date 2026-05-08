<?php

namespace App\Entity;

interface PublishableEventEntity
{
    public function toEventPayload(): array;
}