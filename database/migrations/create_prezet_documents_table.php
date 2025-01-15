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
        Schema::connection('prezet')->create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->index()->unique();
            $table->string('category')->index()->nullable();
            $table->boolean('draft')->index();
            $table->jsonb('frontmatter');
            $table->char('hash', length: 32)->index()->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->index('slug', 'hash');
        });
    }

    public function down(): void
    {
        Schema::connection('prezet')->dropIfExists('documents');
    }
};
