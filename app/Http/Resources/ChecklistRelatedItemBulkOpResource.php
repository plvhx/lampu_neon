<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistRelatedItemBulkOpResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'action' => $this->action,
			'status' => $this->status
		];
	}
}
