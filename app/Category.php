<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $visible = ['id', 'name', 'subCategories', 'level'];
	
	protected $fillable = ['name'];
	
	public function subCategories()
	{
		return $this->hasMany('App\SubCategory', 'category_id');
	}
	
	public function setNameAtrribute($name)
	{
		$this->attributes['name'] = clean($name);
	}
	
	public function forceDelete()
	{
		$articles = $this->subCategories()->get();
		$articles->map->forceDelete();
		
		return parent::forceDelete(); // TODO: Change the autogenerated stub
	}
}
