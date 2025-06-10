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
        Schema::create('logs_requests', function (Blueprint $table) {
           $table->id();
           $table->text('address');
           $table->text('method');
           $table->text('controller_path');
           $table->text('controller_method');
           $table->text('body_of_request');
           $table->text('request_headers');
           $table->string('identifier');
           $table->string('ip_address');
           $table->string('user_agent');
           $table->string('status');
           $table->text('body_of_response');
           $table->text('response_headers');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_requests');
    }
};
