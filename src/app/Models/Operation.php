<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property float amount
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
}
