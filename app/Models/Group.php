<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'id',
        'group_name',
    ];

    public function people()
    {
        return $this->hasMany('App\Models\Person', 'group_id');
    }
}
