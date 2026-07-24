<?php

namespace App\Actions\TicketTier;

use App\Data\TicketTier\UpdateTicketTierData;
use App\Models\TicketTier;

final class UpdateTicketTierAction
{
    public function execute(TicketTier $ticketTier, UpdateTicketTierData $data): TicketTier
    {
        $attributes = $this->resolveUpdateAttributes($data);
        $ticketTier->fill($attributes);
        $ticketTier->save();
        return $ticketTier;
    }
    private function resolveUpdateAttributes(UpdateTicketTierData $data): array
    {
        return $data->toArray();
    }
}
