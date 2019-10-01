<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateAttribute extends Model
{
	protected $table = "template_attributes";

	protected $primaryKey = "template_attr_id";

	public $name;

	public function checklist()
	{
		return $this->hasOne('App\ChecklistAttributes');
	}

	public function items()
	{
		return $this->hasMany('App\ItemAttributes');
	}
}
