<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function incomes(): BelongsToMany
    {
        return $this->belongsTOMany(Income::class);
    }
    public function expenses(): BelongsToMany
    {
        return $this->belongsToMany(Expense::class);
    }
}