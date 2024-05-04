<?php

namespace App\Models;

use App\Models\Model;

class ActivityLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_id', 'user_id', 'item_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

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
        'activity_id' => 'required',
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
    public function activity() 
    {
        return $this->belongsTo('App\Models\Activity\Activity');
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
    public function scopeCompleted($query, $activity, $user)
    {
        return $query->where('activity_id', $activity)->where('user_id', $user);
    }



}