<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForPayoutsInFreelancerWithdrawlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freelancer_withdrawal', function (Blueprint $table) {
            $table->string('bank_country')->nullable()->after('swift_code');
            $table->string('beneficiary_address_1')->nullable()->after('swift_code');
            $table->string('beneficiary_address_2')->nullable()->after('beneficiary_address_1');
            $table->string('beneficiary_address_3')->nullable()->after('beneficiary_address_2');
            $table->string('billing_reason')->nullable()->after('transfer_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('freelancer_withdrawal', function (Blueprint $table) {
            $table->dropColumn('bank_country');
            $table->dropColumn('beneficiary_address_1');
            $table->dropColumn('beneficiary_address_2');
            $table->dropColumn('beneficiary_address_3');
        });
    }
}
