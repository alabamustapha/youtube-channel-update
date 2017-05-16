<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\updateChannelsTableRequest;
use App\Http\Requests\updateChannelsVideoRequest;
use App\AccessToken;
use Maatwebsite\Excel\Facades\Excel;




class AppController extends Controller
{

    public function getAccessToken(Request $request){

        $channels = AccessToken::where('access_token', null)->get();

        return view('access_tokens', compact(['channels']));

    }

    public function saveToken(Request $request){

        $channel = AccessToken::findOrFail($request->session()->get('channel_id'));

        $code = $request->code;
        $state = $request->state;

        $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
        $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);

        $client = new \Google_Client();

        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $client->setAccessType('offline');
        $redirect = filter_var(url('getAccessToken/oauth2callback'), FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        //$youtube = new Google_Service_YouTube($client);

        // Check if an auth token exists for the required scopes
        $tokenSessionKey = 'token-' . $client->prepareScopes();


        if (isset($code)) {
            if (strval($request->session()->get('state')) !== strval($state)) {
                die('The session state did not match.');
            }


            $client->authenticate($code);

            $channel->access_token = json_encode($client->getAccessToken());

            if($channel->access_token !== "null"){
                $channel->save();
            }


            return redirect('getAccessToken');

        }

    }

    public function updateAccessToken(Request $request, $id){

        $channel = AccessToken::findOrFail($id);

        $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
        $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);

        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');

        $client->setAccessType('offline');
        $redirect = filter_var(url('getAccessToken/oauth2callback'), FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        //$youtube = new Google_Service_YouTube($client);

        // Check if an auth token exists for the required scopes
        $tokenSessionKey = 'token-' . $client->prepareScopes();


        $state = mt_rand();
        $client->setState($state);
        session(['state' => $state]);
        session(['channel_id' => $channel->id]);
        $authUrl = $client->createAuthUrl();

        return "Authorization Required: <a href='" . $authUrl  . "'>Autorize</a>";

    }


    public function updateChannelsTable(updateChannelsTableRequest $request){

      if ($request->hasFile('channels')) {

          $excel = Excel::load($request->channels)->get();

          foreach($excel as $row){

              if(strlen(trim($row->oauth2_client_secret)) > 23 && str_contains(trim($row->oauth2_client_id), '.apps.googleusercontent.com')){

                $access_token = AccessToken::where('channel_id', $row->channel_id)
                    ->firstOrCreate([
                        'channel_id' => trim($row->channel_id),
                        'oauth2_client_secret' => trim($row->oauth2_client_secret),
                        'oauth2_client_id' => trim($row->oauth2_client_id)
                        ]);
              }
          }

        }


        return redirect("getAccessToken");

    }

    public function updateChannelVideo(updateChannelsVideoRequest $request){

        $count = 0;
        if ($request->hasFile('youtube')) {

         $excel = Excel::load($request->youtube)->get();

            foreach($excel as $row){

                $channel = AccessToken::where('channel_id', $row->channel_id)->select('access_token', 'channel_id')->first();

                $options = $request->all();
                if($channel){
                    $result = update_video($row, $channel, $options);

                    if($result){
                      $count++;
                    }
                }

            }
        }

        return redirect('/')->with('count', $count);

      }

}
