<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'comment'];

    // Relasi ke model Post
    public function post()
    {
        return $this->belongsTo(Post::class); // Komentar berhubungan dengan Post
    }
}
