<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBlog extends Model
{
    use HasFactory;

    protected $table = 'blog';
    
    protected $fillable = ['title', 'content', 'kategori'];
}
