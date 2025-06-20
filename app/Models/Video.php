<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $primaryKey = 'video_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        "titile","description","video_path","user_id"
    ];
}
