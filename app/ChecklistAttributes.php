<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistAttributes extends Model
{
	protected $table = "checklist_attributes";

	protected $primaryKey = "checklist_attr_id";

	protected $fillable = [
		'object_id', 'object_domain', 'description',
		'is_completed', 'completed_at', 'updated_by',
		'updated_at', 'created_at', 'due',
		'urgency'
	];

	public function items()
	{
		return $this->hasMany(ItemAttributes::class);
	}

	public function getForeignKey()
	{
		return $this->primaryKey;
	}
}
