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
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('user_id')
                ->on('users')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('project_id')->references('project_id')
                ->on('projects')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('tenant_id')->references('id')
                ->on('tenants')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
