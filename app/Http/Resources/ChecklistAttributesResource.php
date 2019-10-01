<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistAttributesResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'type' => 'checklists',
			'id' => $this->checklist_attr_id,
			'attributes' => [
				'object_id' => $this->object_id,
				'object_domain' => $this->object_domain,
				'description' => $this->description,
				'is_completed' => $this->is_completed,
				'completed_at' => $this->completed_at,
				'updated_by' => $this->updated_by,
				'updated_at' => $this->updated_at,
				'created_at' => $this->created_at,
				'due' => $this->due,
				'urgency' => $this->urgency
			],
			'links' => [
				'self' => route('get_checklist_data_by_id', ['checklistId' => $this->checklist_attr_id])
			]
		];
	}
}