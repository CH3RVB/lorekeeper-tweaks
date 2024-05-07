<?php

namespace App\Models\Recipe;

use App\Models\Model;

class RecipeLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recipe_id', 'user_id', 'item_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recipe_log';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'recipe_id' => 'required',
        'user_id' => 'required',
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    
    /**
     * Get the user who purchased the item.
     */
    public function user() 
    {
        return $this->belongsTo('App\Models\User\User');
    }

    /**
     * Get the shop the item was purchased from.
     */
    public function recipe() 
    {
        return $this->belongsTo('App\Models\Recipe\Recipe');
    }

     /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/

     /**
     * Scope a query to only include user's logs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query, $recipe, $user)
    {
        return $query->where('recipe_id', $recipe)->where('user_id', $user);
    }



}