<?php

namespace Condoedge\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('action_setups', function (Blueprint $table) {
            addMetaData($table);

            $table->foreignId('trigger_setup_id')->constrained()->cascadeOnDelete();

            $table->string('action_namespace');
            
            $table->json('action_params')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actions');
    }
};