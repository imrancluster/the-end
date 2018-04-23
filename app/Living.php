<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Living extends Model
{
    protected $fillable = [
        'user_id', 'last_email_sent', 'send_email_after', 'last_email_seen', 'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
