<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
  protected $table = "playlists";

  protected $fillable = ["channel_id", "privacy", "description", "title"];


}
