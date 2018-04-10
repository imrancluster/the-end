<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'user_id', 'name', 'email', 'mobile'
    ];

    public function notes(){
        return $this->belongsToMany('App\Note')->withPivot('id');
    }
}
