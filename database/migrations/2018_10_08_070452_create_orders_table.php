<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('vehicle_id');
            $table->unsignedInteger('depot_id');
            $table->unsignedInteger('driver_id');
            $table->text('items');
            $table->text('order_document')->nullable();
            $table->boolean('loaded')->default(false);
            $table->string('sms_code');
            $table->timestamps();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('depot_id')->references('id')->on('depots');
            $table->foreign('driver_id')->references('id')->on('drivers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
