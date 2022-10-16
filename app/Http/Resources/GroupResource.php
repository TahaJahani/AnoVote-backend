<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $grades = [
            "موارد خاص",
            "کارشناسی",
            "ارشد",
            "دکتری"
        ];
        return [
            'year' => $this->year,
            'grade' => $grades[$this->grade],
            'votes_count' => $this->whenCounted('votes')
        ];
    }
}
