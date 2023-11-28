<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'label' => $this->label,
            'expand' => $this->expand,
            'some_id' => $this->id,
            'department' => $this->department->name,
            'children' => PersonalResource::collection($this->children) // Recursividad para los hijos
        ];
    }
}
