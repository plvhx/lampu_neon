<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemAttributesResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'type' => 'checklists',
			'id' => $this->item_attr_id,
			'attributes' => [
				'description' => $this->description,
				'is_completed' => $this->is_completed,
				'completed_at' => $this->completed_at,
				'due' => $this->due,
				'urgency' => $this->urgency,
				'updated_by' => $this->updated_by,
				'updated_at' => $this->updated_at,
				'created_at' => $this->created_at,
				'assignee_id' => $this->assignee_id,
				'task_id' => $this->task_id
			],
			'links' => [
				'self' => route('get_checklist_data_by_id', ['checklistId' => $this->checklist_attr_id])
			]
		];
	}
}