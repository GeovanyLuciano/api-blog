<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "coment"=>$this->coment,
            "user"=> new UserResource($this->whenLoaded("user")),
            "post"=> new PostResource($this->whenLoaded("post"))
        ];
    }
}
