<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CreateArticleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert basic article categories
        DB::table('article_categories')->insert([
            ['name' => 'Sport', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'World', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Business', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tech', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Culture', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_categories');
    }
}
