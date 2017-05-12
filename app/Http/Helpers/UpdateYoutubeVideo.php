<?php

function update_video($row, $channel){

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
            
        array_walk($r_tags, 'trim');

      
        // Preserve any tags already associated with the video. If the video does
        // not have any tags, create a new list. Replace the values "tag1" and
        // "tag2" with the new tags you want to associate with the video.
        if (is_null($tags)) {
          $tags = $r_tags;
        } else {
          array_push($tags, $r_tags);
        }

        
        // Set the tags array for the video snippet
        $videoSnippet['tags'] = $tags;
        $videoSnippet['title'] = $row->title;
        $videoSnippet['description'] = $spinner::process($row->description);
        $videoSnippet['categoryId'] = $row->category_id;
        
        $videoStatus['privacyStatus'] = $row->privacy;
        

        // Update the video resource by calling the videos.update() method.
        $snippetUpdateResponse = $youtube->videos->update("snippet", $video_snippet);
        $statusUpdateResponse = $youtube->videos->update("status", $video_status);
        
        
        $thumbnail_url = update_thumbnail($videoId, random_pic(public_path() . '/thumbnails'), $client, $youtube);
    
        var_dump(public_path() . '/thumbnails');
        
        $responseTags = $snippetUpdateResponse['snippet']['tags'];
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


