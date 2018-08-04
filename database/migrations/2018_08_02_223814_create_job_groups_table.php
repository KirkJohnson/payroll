<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('wage');
            $table->timestamps();
        });
        
        DB::table('job_groups')->insert(
                array(
                    'name' => 'A',
                    'wage' => '20.00'
                )
         );
        DB::table('job_groups')->insert(
                array(
                    'name' => 'B',
                    'wage' => '30.00'
                )
         );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_groups');
    }
}
