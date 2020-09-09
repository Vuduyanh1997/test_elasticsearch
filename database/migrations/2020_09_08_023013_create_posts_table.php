<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable()->comment('Tiêu đề bài viết');
            $table->string('slug')->nullable()->comment('Slug');
            $table->text('content')->nullable()->comment('Nội dung bài viết');
            $table->bigInteger('user_id')->nullable()->comment('id tác giả');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái bài viết: 0 - private, 1 - public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
