<?php

namespace App\Actions\TicketTier;

use App\Models\TicketTier;

final class PublishTicketTierAction
{
    public function execute(TicketTier $ticketTier): TicketTier
    {
        return $this->publishTicketTier($ticketTier);
    }
    private function publishTicketTier(TicketTier $ticketTier): TicketTier
    {
        $ticketTier->fill([
            'is_published' => true,
        ]);

        $ticketTier->save();

        return $ticketTier;
    }
}
