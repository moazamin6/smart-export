<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triggers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        DB::table('triggers')->insert([
            'name' => 'Order Not Delivered',
            'status' => 'pickup',
        ]);
        DB::table('triggers')->insert([
            'name' => 'Tracking No Not Available',
            'status' => 'notfound002',
        ]);
        DB::table('triggers')->insert([
            'name' => 'Order Not Left Country',
            'status' => 'transit001',
        ]);
        DB::table('triggers')->insert([
            'name' => 'Order Delivery Rejected',
            'status' => 'exception011',
        ]);
//        DB::table('triggers')->insert([
//            'name' => 'Tracking Not Working'
//        ]);
        DB::table('triggers')->insert([
            'name' => 'Order Not Dispatched',
            'status' => 'transit003',
        ]);
        DB::table('triggers')->insert([
            'name' => 'Tracking Info Not Updated',
            'status' => 'transit002',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('triggers');
    }
}
