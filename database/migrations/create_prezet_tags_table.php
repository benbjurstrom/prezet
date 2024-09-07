<?php

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
        Schema::connection('prezet')->create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::connection('prezet')->dropIfExists('tags');
    }
};
