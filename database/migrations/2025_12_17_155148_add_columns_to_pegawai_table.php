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
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('telepon')->after('email');
            $table->date('tanggal_lahir')->after('telepon');
            $table->enum('jenis_kelamin', ['L', 'P'])->after('tanggal_lahir');
            $table->dropColumn('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn(['telepon', 'tanggal_lahir', 'jenis_kelamin']);
            $table->enum('gender', ['male','female'])->after('email');
        });
    }
};
