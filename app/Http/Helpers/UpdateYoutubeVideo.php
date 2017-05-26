<?php

function update_video($row, $channel, $options){

  $spinner = new \Spinner();

  $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
  $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);

  $client = new Google_Client();
  $client->setClientId($OAUTH2_CLIENT_ID);
  $client->setClientSecret($OAUTH2_CLIENT_SECRET);
  $client->setScopes('https://www.googleapis.com/auth/youtube');
  $client->setAccessType('offline');


  if($channel->access_token != "ACCESS_TOKEN"){
      $client->setAccessToken($channel->access_token);
  }

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);


  // Check to ensure that the access token was successfully acquired.
  if ($client->getAccessToken()) {

   try{


     if($client->isAccessTokenExpired()){

       $client->refreshToken($channel->refresh_token);
       $channel->access_token = json_encode($client->getAccessToken());
       $channel->save();
      }


      // REPLACE this value with the video ID of the video being updated.
      $videoId = $row->video_id;


      // Call the API's videos.list method to retrieve the video resource.

      $snippetListResponse = $youtube->videos->listVideos("snippet", array('id' => $videoId));
      $statusListResponse = $youtube->videos->listVideos("status", array('id' => $videoId));



      // If $listResponse is empty, the specified video was not found.
      if(empty($snippetListResponse) || empty($statusListResponse)) {

        var_dump('<h3>Can\'t find a video with video id: %s</h3> ' . $videoId);
      } else {


        // Since the request specified a video ID, the response only
        // contains one video resource.
        $video_snippet = $snippetListResponse[0];
        $video_status = $statusListResponse[0];
        $videoSnippet = $video_snippet['snippet'];

        $videoStatus = $video_status['status'];
        $tags = $videoSnippet['tags'];

        $r_tags = explode(',', $row->tags);



        foreach ($r_tags as $key => $value) {
          $r_tags[$key] = trim($value);
        }

        // Preserve any tags already associated with the video. If the video does
        // not have any tags, create a new list. Replace the values "tag1" and
        // "tag2" with the new tags you want to associate with the video.
        if (is_null($tags)) {
          $tags = $r_tags;
        } else {

          foreach ($r_tags as $tag) {
            array_push($tags, $tag);
          }

        }


        // Set the tags array for the video snippet
        if(isset($options['tag']) && $options['tag'] == "on"){
          $videoSnippet['tags'] = $tags;
        }

        if(isset($options['title']) && $options['title'] == "on"){
          $videoSnippet['title'] = $row->title;
        }

        if(isset($options['description']) && $options['description'] == "on"){
          $videoSnippet['description'] = $spinner::process($row->description);

        }



        if(!isset($options['categoryId']) || $options['categoryId'] == null){
          $videoSnippet['categoryId'] = (string)$row->category_id;
        }else{
          $videoSnippet['categoryId'] = $options['categoryId'];
        }


        if(!isset($options['privacyStatus']) || $options['privacyStatus'] == null){
            $videoStatus['privacyStatus'] = $row->privacy;
        }else{
          $videoStatus['privacyStatus'] = $options['privacyStatus'];
        }


        // Update the video resource by calling the videos.update() method.
        $snippetUpdateResponse = $youtube->videos->update("snippet", $video_snippet);
        $statusUpdateResponse = $youtube->videos->update("status", $video_status);


        if(isset($options['thumbnail']) && $options['thumbnail'] == "on"){
          $thumbnail_url = update_thumbnail($videoId, random_pic(public_path() . '/thumbnails/' . $option['directory']), $client, $youtube);
        }

        $responseTags = $snippetUpdateResponse['snippet']['tags'];


        if($responseTags){
          return true;
        }
      }

    } catch (Google_Service_Exception $e) {
      var_dump('<p>A service error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    } catch (Google_Exception $e) {
      var_dump('<p>An client error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    }

  } else {

    echo "Authorization Required: <a href='{{ url('getAccessToken') }}'>Autorize</a>";

  }


}

function update_thumbnail($videoId, $imagePath, $client, $youtube){

        // Specify the size of each chunk of data, in bytes. Set a higher value for
        // reliable connection as fewer chunks lead to faster uploads. Set a lower
        // value for better recovery on less reliable connections.
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $client->setDefer(true);

        // Create a request for the API's thumbnails.set method to upload the image and associate
        // it with the appropriate video.
        $setRequest = $youtube->thumbnails->set($videoId);

        // Create a MediaFileUpload object for resumable uploads.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $setRequest,
            'image/png',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($imagePath));

        // Read the media file and upload it chunk by chunk.

        $status = false;

        $handle = fopen($imagePath, "rb");
        while (!$status && !feof($handle)) {
          $chunk = fread($handle, $chunkSizeBytes);
          $status = $media->nextChunk($chunk);
        }

        fclose($handle);
        // If you want to make other calls after the file upload, set setDefer back to false
        $client->setDefer(false);

        return $thumbnailUrl = $status['items'][0]['default']['url'];
}

function random_pic($dir)
{
    $files = glob($dir . '/*.*');
    $file = array_rand($files);
    return $files[$file];
}

function create_playlist($playlist, $channel, $nextPageToken){

  $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
  $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);

  $client = new Google_Client();
  $client->setClientId($OAUTH2_CLIENT_ID);
  $client->setClientSecret($OAUTH2_CLIENT_SECRET);
  $client->setScopes('https://www.googleapis.com/auth/youtube');
  $client->setAccessType('offline');
  if($channel->access_token != "ACCESS_TOKEN"){
      $client->setAccessToken($channel->access_token);
  }

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);


  // Check to ensure that the access token was successfully acquired.
  if ($client->getAccessToken()) {

   try{

      if($client->isAccessTokenExpired()){
         $client->refreshToken($channel->refresh_token);
         $channel->access_token = json_encode($client->getAccessToken());
         $channel->save();
        }


    $channelsResponse = $youtube->channels->listChannels('contentDetails', array('mine' => 'true'));


    if(is_null($playlist->playlist_id) || $playlist->playlist_id == ""){
        // 1. Create the snippet for the playlist. Set its title and description.
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($playlist->title);
        $playlistSnippet->setDescription($playlist->description);

        // 2. Define the playlist's status.
        $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
        $playlistStatus->setPrivacyStatus($playlist->privacy);

        // 3. Define a playlist resource and associate the snippet and status
        // defined above with that resource.
        $youTubePlaylist = new Google_Service_YouTube_Playlist();
        $youTubePlaylist->setSnippet($playlistSnippet);
        $youTubePlaylist->setStatus($playlistStatus);

        // 4. Call the playlists.insert method to create the playlist. The API
        // response will contain information about the new playlist.
        $playlistResponse = $youtube->playlists->insert('snippet,status', $youTubePlaylist, array());
        $playlistId = $playlistResponse['id'];

        $playlist->playlist_id = $playlistId;
        $playlist->save();
      }

        // 5. Add a video to the playlist. First, define the resource being added
        // to the playlist by setting its video ID and kind.

        foreach ($channelsResponse['items'] as $channel) {
        // Extract the unique playlist ID that identifies the list of videos
        // uploaded to the channel, and then call the playlistItems.list method
        // to retrieve that list.
        $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];

        if(is_null($nextPageToken) || $nextPageToken == ""){

          $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $uploadsListId,
            'maxResults' => 50
          ));
          $new_nextPageToken = $playlistItemsResponse['nextPageToken'];
        }else{

          $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $uploadsListId,
            'maxResults' => 50,
            'pageToken'  => $nextPageToken
          ));

          $new_nextPageToken = $playlistItemsResponse['nextPageToken'];
        }


        foreach ($playlistItemsResponse['items'] as $playlistItem) {
          $resourceId = new Google_Service_YouTube_ResourceId();
          $resourceId->setVideoId($playlistItem['snippet']['resourceId']['videoId']);
          $resourceId->setKind('youtube#video');

          $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
          $playlistItemSnippet->setPlaylistId($playlist->playlist_id);
          $playlistItemSnippet->setResourceId($resourceId);

          // Finally, create a playlistItem resource and add the snippet to the
          // resource, then call the playlistItems.insert method to add the playlist
          // item.
          $playlistItem = new Google_Service_YouTube_PlaylistItem();
          $playlistItem->setSnippet($playlistItemSnippet);
          $playlistItemResponse = $youtube->playlistItems->insert('snippet,contentDetails', $playlistItem, array());

        }
      }

        if(is_null($new_nextPageToken)){
          $playlist->delete();
          return 'managePlaylist';
        }else{
          return 'makePlaylist/' . $playlist->id . "?nextPageToken=" . $new_nextPageToken;
        }

    } catch (Google_Service_Exception $e) {
      var_dump('<p>A service error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    } catch (Google_Exception $e) {
      var_dump('<p>An client error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    }

  } else {

    echo "Authorization Required: <a href='{{ url('getAccessToken') }}'>Autorize</a>";

  }

}
