<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if(!Schema::hasColumn('posts', 'main_content')){
                $table->text('main_content')->nullable()->comment('Nội dung thuần');
            }
            if(!Schema::hasColumn('posts', 'short_content')){
                $table->text('short_content')->nullable()->comment('Mô tả ngắn');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            if(Schema::hasColumn('posts', 'main_content')){
                $table->dropColumn('main_content');
            }
            if(Schema::hasColumn('posts', 'short_content')){
                $table->dropColumn('short_content');
            }
        });
    }
}
