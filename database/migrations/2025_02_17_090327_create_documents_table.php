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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['passport', 'drivers_license', 'other']);
            $table->string('document_name', 150)->nullable(); // для типа "other"
            $table->string('series', 50)->nullable();
            $table->string('number', 50);
            $table->date('issue_date');
            $table->string('issued_by', 250);
            $table->string('department_code', 7)->nullable(); // для паспорта
            $table->string('region', 150)->nullable(); // для водительского
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
