<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    private function isAdmin(User $user): bool
    {
        return ($user->role ?? null) === 'admin';
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Event $event): bool
    {
        return $this->isAdmin($user) && (int) $event->user_id === (int) $user->id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $this->isAdmin($user) && (int) $event->user_id === (int) $user->id;
    }
}
