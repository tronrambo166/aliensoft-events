<?php

namespace App\Policies;

use App\Models\TicketTier;
use App\Models\User;

class TicketTierPolicy
{
    /**
     * Create a new policy instance.
     */

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ticket-tier.view');
    }

    public function view(User $user, TicketTier $ticketTier): bool
    {
        return $user->hasPermissionTo('ticket-tier.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('ticket-tier.create');
    }

    public function update(User $user, TicketTier $ticketTier): bool
    {
        return $user->hasPermissionTo('ticket-tier.update');
    }

    public function delete(User $user, TicketTier $ticketTier): bool
    {
        return $user->hasPermissionTo('ticket-tier.delete');
    }

    public function publish(User $user, TicketTier $ticketTier): bool
    {
        return $user->hasPermissionTo('ticket-tier.update');
    }

}
