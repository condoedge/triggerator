<?php

namespace Condoedge\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('triggers', function (Blueprint $table) {
            addMetaData($table);

            $table->string('name');
            $table->string('trigger_namespace');

            $table->bigInteger('delay')->default(0)->nullable();

            $table->json('trigger_params')->nullable();

            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('triggers');
    }
};