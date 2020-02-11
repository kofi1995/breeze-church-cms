<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'id',
        'group_name',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'group_id');
    }
}
