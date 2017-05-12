<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_tokens', function(Blueprint $table){
            $table->increments("id");
            $table->string("access_token")->default("ACCESS_TOKEN");
            $table->string("channel_id")->unique();
            $table->string("oauth2_client_secret");
            $table->string("oauth2_client_id");
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
        Schema::drop("access_tokens");
    }
}
