<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentifierAndTriesToEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('service_identifier')->nullable()->after('id');
            $table->string('sent_with_service')->nullable()->after('service_identifier');
            $table->integer('tries')->default(0)->after('sent_with_service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('service_identifier');
            $table->dropColumn('sent_with_service');
            $table->dropColumn('tries');
        });
    }
}
