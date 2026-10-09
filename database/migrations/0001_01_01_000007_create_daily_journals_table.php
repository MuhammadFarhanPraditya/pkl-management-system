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
        Schema::create('daily_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('work_description');
            $table->string('documentation_path')->nullable(); // Path foto dokumentasi kegiatan
            $table->string('status', 30)->default('submitted'); // draft, submitted, approved, revision
            $table->text('teacher_note')->nullable(); // Catatan feedback / revisi dari guru pembimbing
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Cegah duplikasi jurnal per tanggal untuk siswa yang sama
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_journals');
    }
};
