<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property Money amount
 * @property int budget_id
 * @property int user_id
 * @property string name
 * @property string description
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property Collection<Category> categories
 * @property Budget budget
 * @property User user
 */
class Operation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Returns all categories attached to this operation
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Returns the budget associated with this operation
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Returns the user associated with this operation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function amount(): Attribute {
        return Attribute::make(
            get: fn () => Money::ofMinor(round($this->attributes['amount']), $this->budget->currency_iso),
            set: fn (Money $money) => $money->getMinorAmount()->toInt(),
        );
    }
}
