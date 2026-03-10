<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sport extends Model
{
    protected $fillable = ['name', 'icon_url'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_sports');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
