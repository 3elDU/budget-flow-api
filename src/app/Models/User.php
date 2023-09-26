<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Returns all the budgets associated with this user
     */
    public function budgets(): BelongsToMany
    {
        return $this->belongsToMany(Budget::class);
    }
}