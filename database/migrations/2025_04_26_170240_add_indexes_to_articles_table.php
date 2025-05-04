<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->index('category');
            $table->index('source');
            $table->index('published_at');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['source']);
            $table->dropIndex(['published_at']);
        });
    }
};
