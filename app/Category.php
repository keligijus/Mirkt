<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * The attributes that should be visible in arrays.
     * @var array
     */
	protected $visible = ['id', 'name', 'subCategories', 'sub_categories', 'level'];
    
    /**
     * Field in database will be available for mass assignment.
     * @var array
     */
    protected $fillable = ['name'];
    
    /**
     * Relationships with sub-categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany('App\SubCategory', 'category_id');
	}
    
    /**
     * Remove all relationships and delete category
     * @return bool|null
     */
    public function forceDelete(): ?bool
    {
        $articles = $this->subCategories()->get();
        $articles->map->forceDelete();
		
		return parent::forceDelete(); // TODO: Change the autogenerated stub
	}
    
    /**
     * Clean category name from html before save
     *
     * @param string $name
     */
    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = clean($name);
    }
}
