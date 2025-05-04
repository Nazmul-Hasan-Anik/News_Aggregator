<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'content',
        'published_at',
        'category',
        'source',
    ];

    // It will be hidden in response
    protected $hidden = ['created_at', 'updated_at'];

}
