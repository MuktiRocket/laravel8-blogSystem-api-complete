<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class Blog extends Model
{
    use HasApiTokens, HasFactory, softdeletes;
    protected $fillable = [
        'user_id',
        'blog_subject',
        'blog_content',
    ];
}
