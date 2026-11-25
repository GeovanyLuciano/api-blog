<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Categoria extends Model
{
    use HasFactory, Notifiable;
    //
    protected $fillable = ["name","slug", "description"];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function post():HasOne{
        return $this->hasOne(Post::class);
    }
}
