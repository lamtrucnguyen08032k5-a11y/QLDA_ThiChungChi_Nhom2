<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('de_this', function (Blueprint $table) {
            $table->id();
            $table->string('ma_de')->unique();
            $table->string('loai_chung_chi');
            $table->foreignId('khoa_id')->constrained('khoas')->cascadeOnDelete();
            $table->string('ten_de');
            $table->string('file_goc')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('cau_hois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('de_thi_id')->constrained('de_this')->cascadeOnDelete();
            $table->text('noi_dung');
            $table->string('loai_cau')->default('tracnghiem'); // tracnghiem | tuluan
            $table->string('dap_an_a')->nullable();
            $table->string('dap_an_b')->nullable();
            $table->string('dap_an_c')->nullable();
            $table->string('dap_an_d')->nullable();
            $table->string('dap_an_dung')->nullable();
            $table->decimal('diem', 5, 2)->default(1);
            $table->integer('thu_tu')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cau_hois');
        Schema::dropIfExists('de_this');
    }
};
