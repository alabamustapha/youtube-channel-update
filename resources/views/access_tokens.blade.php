<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Get Channel(s) Access Token</title>

        <!-- Fonts -->

        <!-- Styles -->
        <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
 
          
        <!--<link href="css/materialize.min.css" rel="stylesheet" type="text/css">-->

    </head>
    <body>

    <div class="container">
      <form action="{{ url('updateChannelsTable') }}" method="POST">
          
        {{ csrf_field() }}
        
        <div class="file-field input-field">
            <div class="btn">
              <span>Channel List File</span>
              <input type="file" name="youtube">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text">
            </div>
        </div>
        
        <!--<div class="row">-->
        <!--    <div class="col s12 m6 l3">-->
        <!--      <input type="text" class="filled-in" name="thembnail" id="filled-in-box-thumbnails" />-->
        <!--      <label for="filled-in-box-thumbnails">Change Thumbnails</label>-->
        <!--    </div>-->

        <!--    <div class="col s12 m6 l3">-->
        <!--      <input type="checkbox" class="filled-in" name="title" id="filled-in-box-titles" />-->
        <!--      <label for="filled-in-box-titles">Change titles</label>-->
        <!--    </div>-->

        <!--    <div class="col s12 m6 l3">-->
        <!--      <input type="checkbox" class="filled-in" name="description" id="filled-in-box-description" />-->
        <!--      <label for="filled-in-box-description">Change description</label>-->
        <!--    </div>-->

        <!--    <div class="col s12 m6 l3">-->
        <!--      <input type="checkbox" class="filled-in" nmae="tag" id="filled-in-box-tags" />-->
        <!--      <label for="filled-in-box-tags">Change tags</label>-->
        <!--    </div>-->
        <!--</div>-->

        <!--<div class="row">-->
        <!--    <div class="input-field col s12 m6">-->
        <!--      <select name="privacy">-->
        <!--        <option value="" disabled selected>Choose your option</option>-->
        <!--        <option value="private">Private</option>-->
        <!--        <option value="public">Public</option>-->
        <!--        <option value="unlisted">Unlisted</option>-->
        <!--      </select>-->
        <!--      <label>Privacy</label>-->
        <!--    </div>-->

        <!--    <div class="input-field col s12 m6">-->
        <!--      <select name="categoryID">-->
        <!--        <option value="" disabled selected>Choose your option</option>-->
        <!--        <option value="1">Film &amp; Animation</option>-->
        <!--        <option value="2">Autos &amp; Vehicles</option>-->
        <!--        <option value="10">Music</option>-->
        <!--        <option value="15">Pets &amp; Animals</option>-->
        <!--        <option value="17">Sports</option>-->
        <!--        <option value="19">Travel &amp; Events</option>-->
        <!--        <option value="20">Gaming</option>-->
        <!--        <option value="22">People &amp; Blogs</option>-->
        <!--        <option value="23">Comedy</option><option label="Entertainment" value="string:24">Entertainment</option>-->
        <!--        <option value="25">News &amp; Politics</option>-->
        <!--        <option value="26">Howto &amp; Style</option>-->
        <!--        <option value="27">Education</option>-->
        <!--        <option value="28">Science &amp; Technology</option>-->
        <!--        <option value="29">Nonprofits &amp; Activism</option>-->
        <!--      </select>-->
        <!--      <label>Category</label>-->
        <!--    </div>-->
        <!--</div>-->
        
        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
            <i class="material-icons right">send</i>
          </button>
        
        </form>
        
        <hr>
        <div class= "row">
            <div class="col s3">
                <a href="#!" class="collection-item"><span class="badge new"> {{ $channels->count() }}</span>Unauthorize channel</a>    
            </div>
        </div>
        <div class="row">
            
            
            <table class="striped bordered">
                <thead>
                  <tr>
                      <th>Channel ID</th>
                      <th>Access Token</th>
                      <th>Refresh Token</th>
                      <th>Action</th>
                  </tr>
                </thead>
        
                <tbody>
                @foreach($channels as $channel)    
                  <tr>
                    <td>{{ $channel->channel_id }}</td>
                    <td>{{ $channel->access_token == 'ACCESS_TOKEN' ? 'Unautorized' : 'Autorized' }}</td>
                    <td>{{ $channel->access_token == 'ACCESS_TOKEN' ? 'Unautorized' : 'Autorized' }}</td>
                    <td>
                        <form action="{{ url('getAccessToken/' . $channel->id) }}" method="POST">
                            
                            {{ csrf_field() }}
                            
                            <input type="hidden" name="oauth2_client_secret" value="{{ $channel->oauth2_client_secret }}"/>
                            <input type="hidden" name="oauth2_client_id" value="{{ $channel->oauth2_client_id }}"/>
                        
                            <button class="btn waves-effect waves-light" type="submit" name="action">Get Key
                                <i class="material-icons right">send</i>
                             </button>    
                        </form>
                        
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>    
            
        </div>
    </div>


    <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
    
    <!--<script src="js/jquery-2.1.1.min.js"></script>-->
    <!--<script src="js/materialize.min.js"></script>-->
    <script>
    $(document).ready(function() {
      $('select').material_select();
    });
    </script>
    </body>
</html>
