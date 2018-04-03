<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'note_id', 'filename', 'uri', 'filemime', 'filesize'
    ];

    public function note ()
    {
        return $this->belongsTo(Note::class);
    }
}
