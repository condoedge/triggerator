<?php

namespace Condoedge\Database\Migrations;

use Condoedge\Triggerator\Models\ExecutionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trigger_executions', function (Blueprint $table) {
            addMetaData($table);

            $table->foreignId('trigger_id')->constrained()->cascadeOnDelete();

            $table->tinyInteger('status')->default(ExecutionStatusEnum::PENDING);

            $table->datetime('time_to_execute')->nullable();
            $table->datetime('executed_at')->nullable();

            $table->json('execution_params')->nullable();

            $table->bigInteger('job_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trigger_executions');
    }
};