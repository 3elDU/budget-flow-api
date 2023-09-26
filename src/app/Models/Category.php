<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Returns all the incomes associated with this category
     */
    public function incomes(): BelongsToMany
    {
        return $this->belongsToMany(Income::class);
    }

    /**
     * Returns all the expenses associated with this category
     */
    public function expenses(): BelongsToMany
    {
        return $this->belongsToMany(Expense::class);
    }
}