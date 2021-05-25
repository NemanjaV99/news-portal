<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert base data
        // 1 - User, 2 - Editor, 3 - Admin
        DB::table('user_roles')->insert([
            ['name' => 'USER', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'EDITOR', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'ADMIN', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
