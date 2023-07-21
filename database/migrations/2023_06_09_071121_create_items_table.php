<?php

use App\Models\ShoppingList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(ShoppingList::class);
            $table->boolean("done")->default(false);
            $table->string("name", 100);
            $table->decimal('quantity')->default(1);
            $table->string("instructions")->nullable();
            // idea for later. An optional backup item for each item.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
