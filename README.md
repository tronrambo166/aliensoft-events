# Ticket Tier API Assessment

## Assumptions

- Permission names were not specified in the assessment, so I assumed:
    - `ticket-tier.view`
    - `ticket-tier.create`
    - `ticket-tier.update`
    - `ticket-tier.delete`
- Publishing a ticket tier reuses the `update` permission.
- `NULL` in `sales_channels` means the tier is available on all sales channels.
- `event_id` is immutable after creation.
- Data objects are resolved after `$this->authorize(...)` so authorization occurs before validation.

## Supported Query Parameters

- `filter[event_id]`
- `filter[channel]`
- `sort=name,price,created_at`
- `include=event`

## Setup & Run

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in `.env`, then run:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

## Tests

```bash
php artisan test
```

## API Testing

A Postman collection is included.

Protected endpoints require a valid Sanctum Bearer token. Generate one via Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::factory()->create();
$user->createToken('postman')->plainTextToken;
```
