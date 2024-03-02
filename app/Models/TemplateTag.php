<?php

namespace App\Models;

use Config;
use DB;
use App\Models\Model;

class TemplateTag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'tag', 'data'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'template_tags';

    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'tag' => 'required',
        'name' => 'required|between:3,100',
    ];
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'tag' => 'required',
        'name' => 'required|between:3,100',
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/



    /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to retrieve only a certain tag.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $tag)
    {
        return $query->where('tag', $tag);
    }

    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/
    
    /**
     * Gets the URL of the tag's editing page.
     *
     * @return string
     */
    public function getAdminUrlAttribute()
    {
        return url('admin/templates/edit/'.$this->id);
    }

    /**
     * Get the data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data'], true);
    }

    /**
     * Get the service associated with this tag.
     *
     * @return mixed
     */
    public function getServiceAttribute()
    {
        $class = 'App\Services\Templates\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $this->tag))) . 'Service';
        return class_exists($class) ? (new $class()) : null;
    }

    /**********************************************************************************************
    
        OTHER FUNCTIONS

    **********************************************************************************************/

    /**
     * Get the template associated with the tag.
     *
     * @return mixed
     */
    public function getTemplate() {
        return method_exists($this->service, 'getTemplate') ? $this->service?->getTemplate($this) : "templates." . $this->tag;
    }

    /**
     * Get the template associated with the tag.
     *
     * @return mixed
     */
    public function getTemplateData() {
        return method_exists($this->service, 'getTemplateData') ? $this->service?->getTemplateData($this) : null;
    }
}
