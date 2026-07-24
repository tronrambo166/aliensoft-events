## Assumptions

- Permission names were not specified in the assessment, so I assumed the following Spatie permissions:
  - `ticket-tier.view`
  - `ticket-tier.create`
  - `ticket-tier.update`
  - `ticket-tier.delete`
- The publish action reuses the `update` permission because publishing is treated as a state change of an existing ticket tier.
- A `NULL` value for `sales_channels` means the ticket tier is available on all sales channels.
- `event_id` is immutable after creation, so it cannot be updated.
- `CreateTicketTierData` and `UpdateTicketTierData` are resolved after `$this->authorize(...)` to ensure authorization occurs before validation.

## Supported Query Parameters

- `filter[event_id]`
- `filter[channel]`
- `sort=name,price,created_at`
- `include=event`

## Setup & Run

1. Install dependencies:

```bash
composer install
```

2. Copy the environment file, configure your database credentials in `.env`, and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

3. Run the migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

4. Start the application:

```bash
php artisan serve
```

5. Run the test suite:

```bash
php artisan test
```

## Postman Collection

A Postman collection is included with the submission for testing all API endpoints.
