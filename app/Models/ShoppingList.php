<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        // 'email',
        // 'password',
    ];

    /**
     * Get the items for the shopping list.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the user that created this shopping list.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
