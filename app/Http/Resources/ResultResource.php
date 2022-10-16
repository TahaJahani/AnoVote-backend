<?php

namespace App\Http\Resources;

use App\Models\UserGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $id = $this->id;
        $detail = UserGroup::withCount(['votes' => function ($query) use($id) {
            $query->where('option_id', $id);
        }])->where('votes_count', '!=', 0)->get();
        return [
            "id" => $this->id,
            "text" => $this->text,
            "total_count" => sizeof($this->votes),
            "detail" => GroupResource::collection($detail)
        ];
    }
}
