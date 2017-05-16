<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $table = "access_tokens";

    protected $fillable = ["channel_id", "access_token", "oauth2_client_secret", "oauth2_client_id", "name", "refresh_token"];


}
