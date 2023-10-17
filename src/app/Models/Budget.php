<?php

namespace App\Models;

use Brick\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Returns all the operations beloging to this budget
     */
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class);
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
