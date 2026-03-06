<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('name_extension')->nullable();
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['Male', 'Female', 'Prefer not to say']);
            // Address
            $table->string('country')->default('Philippines');
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();
            // Foreign address fields
            $table->string('foreign_state')->nullable();
            $table->string('foreign_city')->nullable();
            $table->string('foreign_street')->nullable();
            // Contact
            $table->string('contact_number', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
