<?php

namespace App\Models;

use Brick\Money\Currency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Returns all the users associated with this budget
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Returns all the incomes beloging to this budget
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Returns all the expenses belonging to this budget
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Converts the model to/from Brick\Money\Currency object
     */
    protected function currency(): Attribute
    {
        return Attribute::make(
            get: fn (string $value, array $attributes) => Currency::of($attributes["name"]),
            set: fn (Currency $value) => $value->getCurrencyCode(),
        );
    }
}
