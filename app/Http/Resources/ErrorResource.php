<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public function withResponse($request, $response)
    {
        $response->setStatusCode(500);

    }

    public function toArray($request): array
    {
        self::withoutWrapping();

        return [
            'success' => false,
            'message' => $this['message'] ?? '',
        ];
    }
}
