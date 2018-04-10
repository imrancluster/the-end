<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'members'
    ];

    public function files() {
        return $this->hasMany(File::class);
    }

    public function persons(){
        return $this->belongsToMany('App\Person')->withPivot('id');
    }
}
