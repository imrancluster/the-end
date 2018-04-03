<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title', 'body'
    ];

    public function files() {
        return $this->hasMany(File::class);
    }
}
