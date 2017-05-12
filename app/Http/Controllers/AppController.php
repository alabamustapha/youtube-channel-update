<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccessToken;
use Maatwebsite\Excel\Facades\Excel;




class AppController extends Controller
{
    
    public function getAccessToken(Request $request){
        
        $channels = AccessToken::where('access_token', 'ACCESS_TOKEN')->get();
        
        // $spinner = new \Spinner();
        // $excel = Excel::load(public_path() . '/excel/youtube.xlsx')->get();
        
        // echo '<ul>';
        // foreach($excel as $row){
            
        //     $access_token = AccessToken::select('access_token', 'refresh_token', 'created', 'expires_in', 'token_type')->firstOrCreate(['channel_id' => trim($row->channel_id)]);
            
            
            
        //     if($access_token){
        //         update_video($row, $access_token);        
        //     }
            
        
        //     // $tags = explode(',', $row->tags);
            
        //     // array_walk($tags, 'trim');
            
        //     // echo '<li>' . $row->oauth2_client_id . '</li>';
        //     // echo '<li>' . $row->oauth2_client_secret . '</li>';
        //     // echo '<li>' . $row->channel_id . '</li>';
        //     // echo '<li>' . $row->video_url . '</li>';
        //     // echo '<li>' . $row->video_id . '</li>';
        //     // echo '<li>' . $row->title . '</li>';
        //     // echo '<li>' . $tags[0] . '</li>';
        //     // echo '<li>' . $spinner::process($row->description) . '</li>';
        //     // echo '<li>' . $row->privacy . '</li>';
        //     // echo '<li>' . $row->category_id . '</li>';
            
        // }
        // echo '</ul>';
        
        return view('access_tokens', compact(['channels']));
            
    }
    
    public function saveToken(Request $request){

        $channel = AccessToken::findOrFail($request->session()->get('channel_id'))->first();
        $code = $request->code;
        $state = $request->state;
        
        $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
        $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);
        
        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
          
        $client->setAccessType('offline');
        $redirect = filter_var('https://youtube-channel-update-alabamustapha.c9users.io/getAccessToken/oauth2callback', FILTER_SANITIZE_URL);
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
            $channel->save();
            
            return redirect('getAccessToken');
         
        }
         
    }
    
    public function updateAccessToken(Request $request, $id){
        
        $channel = AccessToken::findOrFail($id)->first();
        
        //dd($channel);
        
        $OAUTH2_CLIENT_ID     = trim($channel->oauth2_client_id);
        $OAUTH2_CLIENT_SECRET = trim($channel->oauth2_client_secret);
        
        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
          
        $client->setAccessType('offline');
        $redirect = filter_var('https://youtube-channel-update-alabamustapha.c9users.io/getAccessToken/oauth2callback', FILTER_SANITIZE_URL);
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
    
    
    public function updateChannelsTable(Request $request){
        
        $excel = Excel::load(public_path() . '/excel/youtube.xlsx')->get();
        
        foreach($excel as $row){
                    
            $access_token = AccessToken::where('channel_id', $row->channel_id)
                ->firstOrCreate([
                    'channel_id' => trim($row->channel_id),
                    'oauth2_client_secret' => trim($row->oauth2_client_secret),
                    'oauth2_client_id' => trim($row->oauth2_client_id)
                    ]);
                    

        }
        
        return redirect("getAccessToken");
        
    }
    
    public function updateChannelVideo(){
        
         $excel = Excel::load(public_path() . '/excel/youtube.xlsx')->get();
        
        
        
        foreach($excel as $row){
            
            $channel = AccessToken::select('access_token', 'channel_id')->firstOrCreate(['channel_id' => trim($row->channel_id)]);
            
            
            if($channel){
                update_video($row, $channel);        
            }
            
        }    
        
    }
    
}