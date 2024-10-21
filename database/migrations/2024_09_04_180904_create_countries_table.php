<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique(); // Country code (e.g., 'US')
                $table->string('name'); // Country name (e.g., 'United States')
                $table->string('flag')->nullable(); // Flag URL or file path
                $table->string('languages')->nullable(); // Comma-separated list of languages
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
