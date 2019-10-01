<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAttributes extends Model
{
	protected $table = "item_attributes";

	protected $primaryKey = "item_attr_id";

	protected $fillable = [
		'description',
		'is_completed',
		'completed_at',
		'due',
		'urgency',
		'updated_by',
		'updated_at',
		'created_at',
		'assignee_id',
		'task_id'
	];

	public function checklist()
	{
		return $this->belongsTo(ChecklistAttributes::class);
	}

	public function getForeignKey()
	{
		return $this->primaryKey;
	}
}
