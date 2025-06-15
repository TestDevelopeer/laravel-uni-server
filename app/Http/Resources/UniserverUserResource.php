<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UniserverUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array|null
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'password' => $this->password,
            'updated_at' => $this->updated_at
        ];
    }
}
