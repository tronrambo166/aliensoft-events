<?php

namespace App\Actions\TicketTier;

use App\Data\TicketTier\CreateTicketTierData;
use App\Models\TicketTier;

final class CreateTicketTierAction
{
    public function execute(CreateTicketTierData $data): TicketTier
    {
        return $this->createTicketTier($data);
    }
    private function createTicketTier(CreateTicketTierData $data): TicketTier
    {
        return TicketTier::create([
            'event_id' => $data->event_id,
            'name'     => $data->name,
            'price'    => $data->price,
            'quantity' => $data->quantity,
            'sales_channels' => $data->sales_channels,
            'is_published' => false,
            'is_active' => true,
        ]);
    }
}
