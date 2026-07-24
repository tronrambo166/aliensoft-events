<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function __construct(
        public readonly string $message,
        public readonly mixed $data = null,
    ){
        parent::__construct($data);
    }
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
