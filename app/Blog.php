<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $softDelete = true;

    protected $fillable = [
        'title', 'image', 'content',
    ];
}
