<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'name',
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
}
