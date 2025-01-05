<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cron_job_log_uuid')->unique();
            $table->string('name')->index();
            $table->longText('message');
            $table->integer('success')->nullable()->default(1);
            $table->integer('is_archive')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::table('cron_job_logs', function(Blueprint $table) {
            $table->index(['name', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_job_logs');
    }
}
