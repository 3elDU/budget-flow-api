<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string name
 * @property string description
 * @property string color_hex
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property Collection<Operation> operations
 */
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Returns all the operations associated with this category
     */
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class);
    }
}
