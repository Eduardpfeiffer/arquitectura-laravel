<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 320);
            $table->string('phone', 40);
            $table->string('project_type', 100);
            $table->string('budget_range', 100)->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'contacted', 'archived'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_leads');
    }
};
