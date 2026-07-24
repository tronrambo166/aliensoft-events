<?php

namespace App\Actions\TicketTier;

use App\Models\TicketTier;

final class DeleteTicketTierAction
{
    public function execute(TicketTier $ticketTier): void
    {
        $this->deleteTicketTier($ticketTier);
    }
    private function deleteTicketTier(TicketTier $ticketTier): void
    {
        $ticketTier->delete();
    }
}
