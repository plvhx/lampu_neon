<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompletedItemResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->item_attr_id,
			'item_id' => $this->item_attr_id,
			'is_completed' => $this->is_completed,
			'checklist_id' => $this->checklist_attr_id
		];
	}
}