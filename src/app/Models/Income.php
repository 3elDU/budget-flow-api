<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Income extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Returns all categories attached to this income
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Returns the budget associated with this income
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}