<?php

namespace App\Models;

use Brick\Money\Currency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Budget extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Returns all the users associated with this budget
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Converts the model to Brick\Money\Currency object
     */
    protected function currency(): Attribute
    {
        return Attribute::make(
            get: fn(string $value, array $attributes) => Currency::of($attributes["name"]),
            set: fn(Currency $value) => $value->getCurrencyCode(),
        );
    }
}