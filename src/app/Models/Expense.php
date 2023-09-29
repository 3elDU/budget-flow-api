<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Returns all categories attached to this expense
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Returns the budget associated with this expense
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    /**
     * Returns the user associated with this expense
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
