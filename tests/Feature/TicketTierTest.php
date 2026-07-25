<?php

use App\Models\Event;
use App\Models\TicketTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    //creates a test user
    $this->user = User::factory()->create();

    // Create the permissions used by the policy
    $allPermissionNames = [
        'ticket-tier.view',
        'ticket-tier.create',
        'ticket-tier.update',
        'ticket-tier.delete',
    ];

    foreach($allPermissionNames as $permission){
        Permission::findOrCreate($permission);
    }

    // Give all permissions to the test user
    $this->user->givePermissionTo($allPermissionNames);

    // Authenticate as this user for every test
    $this->actingAs($this->user);
});


it('creates a ticket tier', function () {
    $event = Event::factory()->create();

    $payload = [
        'event_id' => $event->id,
        'name' => 'VIP',
        'price' => 10.00,
        'quantity' => 100,
        'sales_channels' => ['web', 'box_office'],
    ];

    $response = $this->postJson('/api/ticket-tiers', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'VIP')
        ->assertJsonPath('data.price', '10.00');

    $this->assertDatabaseHas('ticket_tiers', [
        'event_id' => $event->id,
        'name' => 'VIP',
    ]);
});

it('enforces unique ticket tier name within the same event', function () {
    $event = Event::factory()->create();
    TicketTier::factory()->create([
        'event_id' => $event->id,
        'name' => 'VIP',
    ]);

    $response = $this->postJson('/api/ticket-tiers', [
        'event_id' => $event->id,
        'name' => 'VIP',
        'price' => 200,
        'quantity' => 100,
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors('name');

});

it('allows the same ticket tier name for different events', function () {
    $eventOne = Event::factory()->create();
    $eventTwo = Event::factory()->create();

    TicketTier::factory()->create([
        'event_id' => $eventOne->id,
        'name' => 'VIP',
    ]);

    $response = $this->postJson('/api/ticket-tiers', [
        'event_id' => $eventTwo->id,
        'name' => 'VIP',
        'price' => 100,
        'quantity' => 50,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('ticket_tiers', [
        'event_id' => $eventTwo->id,
        'name' => 'VIP',
    ]);
});

it('filters ticket tiers by sales channel', function () {
    $event = Event::factory()->create();

    TicketTier::factory()->create([
        'event_id' => $event->id,
        'name' => 'All',
        'sales_channels' => null,
    ]);

    TicketTier::factory()->create([
        'event_id' => $event->id,
        'name' => 'Web',
        'sales_channels' => ['web'],
    ]);

    TicketTier::factory()->create([
        'event_id' => $event->id,
        'name' => 'Mobile',
        'sales_channels' => ['box_office'],
    ]);

    $response = $this->getJson('/api/ticket-tiers?filter[channel]=web');

    $response
        ->assertOk()
        ->assertJsonFragment(['name' => 'All'])
        ->assertJsonFragment(['name' => 'Web'])
        ->assertJsonMissing(['name' => 'Mobile']);
});

it('publishes a ticket tier', function () {
    $tier = TicketTier::factory()->create([
        'is_published' => false,
    ]);

    $response = $this->patchJson("/api/ticket-tiers/{$tier->id}/publish");

    $response->assertOk()
        ->assertJsonPath('data.is_published', true)
        ->assertJsonPath('data.id', $tier->id);

    expect($tier->fresh()->is_published)->toBeTrue();
});

it('soft deletes a ticket tier', function () {
    $tier = TicketTier::factory()->create();

    $response = $this->deleteJson("/api/ticket-tiers/{$tier->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $tier->id);

    $this->assertSoftDeleted($tier);

    $this->getJson('/api/ticket-tiers')
        ->assertOk()
        ->assertJsonMissing([
            'id' => $tier->id,
        ]);
});
