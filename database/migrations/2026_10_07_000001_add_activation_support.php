<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan dukungan untuk fitur Aktivasi Akun:
     * 1. Kolom date_of_birth pada students (untuk verifikasi aktivasi siswa)
     * 2. Email pada users menjadi nullable (akun belum diaktivasi belum punya email)
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('phone_number');
        });

        // Email menjadi nullable agar Admin bisa import data siswa
        // tanpa harus menentukan email (siswa akan isi sendiri saat aktivasi)
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
