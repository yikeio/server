<?php

namespace App\Modules\Chat;

use App\Modules\Quota\Quota;
use App\Modules\User\User;

class Completion
{
    protected User $creator;

    protected Conversation $conversation;

    protected Quota $quota;

    protected array $prompts;

    protected string $value;

    protected string $model;

    protected array $raws;

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    public function getConversation(): Conversation
    {
        return $this->conversation;
    }

    public function setConversation(Conversation $conversation): void
    {
        $this->conversation = $conversation;
    }

    public function getPrompts(): array
    {
        return $this->prompts;
    }

    public function setPrompts(array $prompts): void
    {
        $this->prompts = $prompts;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getRaws(): array
    {
        return $this->raws;
    }

    public function setRaws(array $raws): void
    {
        $this->raws = $raws;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getQuota(): Quota
    {
        return $this->quota;
    }

    public function setQuota(Quota $quota): void
    {
        $this->quota = $quota;
    }
}
