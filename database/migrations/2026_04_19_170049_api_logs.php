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
    Schema::create('api_logs', function (Blueprint $table) {
    $table->id();
    $table->string('method');       // GET, POST, ...
    $table->string('url');          
    $table->float('duration');      
    $table->integer('query_count'); 
    $table->ipAddress('ip');        
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};


