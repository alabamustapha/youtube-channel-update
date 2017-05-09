<?php

function update_video_tags(){

  session_start();


  //$OAUTH2_CLIENT_ID = '376686418204-cgr8iprmgk6o2gak77h8im9t2n0qcq16.apps.googleusercontent.com';
  //$OAUTH2_CLIENT_SECRET = 'CqU_WoKM2dEqRbrSjKUB7Elm';

  $OAUTH2_CLIENT_ID = "REPLACE_ME";
  $OAUTH2_CLIENT_SECRET = "REPLACE_ME";

  $client = new Google_Client();
  $client->setClientId($OAUTH2_CLIENT_ID);
  $client->setClientSecret($OAUTH2_CLIENT_SECRET);
  $client->setScopes('https://www.googleapis.com/auth/youtube');
  $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
      FILTER_SANITIZE_URL);
  $client->setRedirectUri($redirect);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  // Check if an auth token exists for the required scopes
  $tokenSessionKey = 'token-' . $client->prepareScopes();
  if (isset($_GET['code'])) {
    if (strval($_SESSION['state']) !== strval($_GET['state'])) {
      die('The session state did not match.');
    }

    $client->authenticate($_GET['code']);
    $_SESSION[$tokenSessionKey] = $client->getAccessToken();
    header('Location: ' . $redirect);
  }

  if (isset($_SESSION[$tokenSessionKey])) {
    $client->setAccessToken($_SESSION[$tokenSessionKey]);
  }

  // Check to ensure that the access token was successfully acquired.
  if ($client->getAccessToken()) {
    try{

      // REPLACE this value with the video ID of the video being updated.
      $videoId = "aCAV2ruYVZc";

      // Call the API's videos.list method to retrieve the video resource.
      $listResponse = $youtube->videos->listVideos("snippet", array('id' => $videoId));


      // If $listResponse is empty, the specified video was not found.
      if (empty($listResponse)) {
        die('<h3>Can\'t find a video with video id: %s</h3> ' . $videoId);
      } else {
        // Since the request specified a video ID, the response only
        // contains one video resource.
        $video = $listResponse[0];
        $videoSnippet = $video['snippet'];
        $tags = $videoSnippet['tags'];

        // Preserve any tags already associated with the video. If the video does
        // not have any tags, create a new list. Replace the values "tag1" and
        // "tag2" with the new tags you want to associate with the video.
        if (is_null($tags)) {
          $tags = array("tag1", "tag2");
        } else {
          array_push($tags, "tag1", "tag2");
        }


        var_dump($video);
        // Set the tags array for the video snippet
        $videoSnippet['tags'] = $tags;
        $videoSnippet['title'] = "New Title";
        $videoSnippet['description'] = "Newly described";
        $videoSnippet['categoryId'] = "1";


        // Update the video resource by calling the videos.update() method.
        $updateResponse = $youtube->videos->update("snippet", $video);

        $responseTags = $updateResponse['snippet']['tags'];
      }
    } catch (Google_Service_Exception $e) {
      die('<p>A service error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    } catch (Google_Exception $e) {
      die('<p>An client error occurred: <code>%s</code></p>' . htmlspecialchars($e->getMessage()));
    }

    $_SESSION[$tokenSessionKey] = $client->getAccessToken();
  } elseif ($OAUTH2_CLIENT_ID == 'REPLACE_ME') {

    die('<h3>Client Credentials Required</h3>
    <p>
      You need to set <code>\$OAUTH2_CLIENT_ID</code> and
      <code>\$OAUTH2_CLIENT_ID</code> before proceeding.
    <p>');
  } else {
    // If the user hasn't authorized the app, initiate the OAuth flow
    $state = mt_rand();
    $client->setState($state);
    $_SESSION['state'] = $state;

    $authUrl = $client->createAuthUrl();

    echo "Authorization Required: <a href='" . $authUrl  . "'>Autorize</a>";

  }


}
