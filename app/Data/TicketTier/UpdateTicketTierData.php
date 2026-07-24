<?php

namespace App\Data\TicketTier;

use App\Models\TicketTier;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateTicketTierData extends Data
{
    public function __construct(
        public string|Optional $name,
        public float|Optional $price,
        public int|Optional $quantity,
        public array|Optional|null $sales_channels,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        $ticketTier = request()->route('ticket_tier');

        return [
            'name' => ['sometimes', 'string', 'max:255',
                Rule::unique('ticket_tiers', 'name')
                ->where(fn ($query) => $query->where('event_id', $ticketTier->event_id))
                ->ignore($ticketTier->id),
            ],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'sales_channels' => ['sometimes', 'nullable', 'array'],
            'sales_channels.*' => ['string', Rule::in(TicketTier::ALLOWED_CHANNELS)],
        ];
    }
}
