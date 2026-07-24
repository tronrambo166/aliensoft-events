<?php

namespace App\Http\Controllers\Api;

use App\Actions\TicketTier\CreateTicketTierAction;
use App\Actions\TicketTier\DeleteTicketTierAction;
use App\Actions\TicketTier\PublishTicketTierAction;
use App\Actions\TicketTier\UpdateTicketTierAction;
use App\Data\TicketTier\CreateTicketTierData;
use App\Data\TicketTier\UpdateTicketTierData;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\TicketTierResource;
use App\Models\TicketTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Ticket;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TicketTierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', TicketTier::class);

        $perPage = request()->integer('per_page', 15);

        $ticketTiers = QueryBuilder::for(TicketTier::class)
            ->allowedFilters([
                AllowedFilter::exact('event_id'),
                AllowedFilter::callback('channel',
                    fn($query, $value) => $query->availableOnChannel($value))
            ])
            ->allowedSorts(['name', 'price', 'created_at'])
            ->allowedIncludes(['event'])
            ->defaultSort('-created_at')
            ->paginate($perPage);

        return TicketTierResource::collection($ticketTiers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateTicketTierAction $action)
    {
        $this->authorize('create',TicketTier::class);
        $data = CreateTicketTierData::from($request);

        DB::beginTransaction();

        try{
            $ticketTier = $action->execute($data);
            DB::commit();

            return (new ApiResponseResource(
                __('messages.ticket_tier_created'),
                new TicketTierResource($ticketTier),
            ))->response()->setStatusCode(201);
        }
        catch(ValidationException $e){
            DB::rollBack();
            throw $e;
        }
        catch (\Throwable $e){
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new HttpException(500, __('messages.generic_error'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketTier $ticketTier)
    {
        $this->authorize('view', $ticketTier);

        $ticketTier->load('event');

        return new TicketTierResource($ticketTier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UpdateTicketTierAction $action, TicketTier $ticketTier)
    {
        $this->authorize('update', $ticketTier);
        $data = UpdateTicketTierData::factory()->withoutOptionalValues()->from($request);

        DB::beginTransaction();

        try {
            $action->execute($ticketTier, $data);

            DB::commit();

            return (new ApiResponseResource(
                __('messages.ticket_tier_updated'),
                new TicketTierResource($ticketTier),
            ))->response()->setStatusCode(200);
        }
        catch(ValidationException $e){
            DB::rollBack();
            throw $e;
        }
        catch (\Throwable $e){
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new HttpException(500, __('messages.generic_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketTier $ticketTier, DeleteTicketTierAction $action)
    {
        $this->authorize('delete', $ticketTier);

        DB::beginTransaction();

        try{
            $action->execute($ticketTier);
            DB::commit();

            return (new ApiResponseResource(
                __('messages.ticket_tier_deleted'),
                new TicketTierResource($ticketTier),
            ))->response()->setStatusCode(200);
        }
        catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
        catch(\Throwable $exception){
            DB::rollBack();
            Log::error($exception->getMessage(), ['exception' => $exception]);
            throw new HttpException(500, __('messages.generic_error'));
        }
    }

    public function publish(PublishTicketTierAction $action, TicketTier $ticketTier)
    {
        $this->authorize('update', $ticketTier);

        DB::beginTransaction();

        try {
            $action->execute($ticketTier);

            DB::commit();

            return (new ApiResponseResource(
                __('messages.ticket_tier_published'),
                new TicketTierResource($ticketTier),
            ))->response()->setStatusCode(200);
        }
        catch(ValidationException $e){
            DB::rollBack();
            throw $e;
        }
        catch (\Throwable $e){
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new HttpException(500, __('messages.generic_error'));
        }
    }
}
