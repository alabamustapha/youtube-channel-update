<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Update Youtube Channel Videos</title>

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

      <nav>
        <div class="nav-wrapper teal">
          <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
          <ul id="nav-mobile" class="left hide-on-med-and-down">
              <li>  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">settings_power</i> Logout</a></li>
              <li><a href="{{ url('getAccessToken') }}"><i class="material-icons left">vpn_key</i> Access Tokens</a></li>
              <li><a href="{{ url('AuthorizedChannels') }}"><i class="material-icons left">vpn_key</i> Authorized Channel</a></li>
              <li><a href="{{ url('manageThumbnails') }}"><i class="material-icons left">view_list</i> Manage Thumbnails</a></li>
              <li><a href="{{ url('/') }}"><i class="material-icons left ">home</i> Home</a></li>
            </ul>

            <ul class="side-nav" id="mobile-demo">
              <li>  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">settings_power</i> Logout</a></li>
              <li><a href="{{ url('getAccessToken') }}"><i class="material-icons left">vpn_key</i> Access Tokens</a></li>
              <!-- <li><a href="collapsible.html"><i class="material-icons left ">home</i> Home</a></li> -->
              <li><a href="{{ url('AuthorizedChannels') }}"><i class="material-icons left">vpn_key</i> Authorized Channel</a></li>
              <li><a href="{{ url('/') }}"><i class="material-icons left ">home</i> Home</a></li>
              <li><a href="{{ url('manageThumbnails') }}"><i class="material-icons left">view_list</i> Manage Thumbnails</a></li>
            </ul>

          </div>
        </nav>


      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>

            @if (count($errors) > 0)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif



        <div class="row">

          <form action="{{ url('createPlaylist') }}" method="POST" enctype="multipart/form-data">


            {{ csrf_field() }}

            <div class="file-field input-field">
                <div class="btn">
                  <span>File</span>
                  <input type="file" name="playlist">
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" type="text">
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action">Upload
                <i class="material-icons right">send</i>
              </button>
          </form>


        </div>

        <div class="row">
          <table class="striped  responsive-table">
              <thead>
                <tr>
                    <th>Channel ID</th>
                    <th>Title</th>
                    <th>Privacy</th>
                    <th>Action</th>
                </tr>
              </thead>

              <tbody>
              @foreach($playlists as $playlist)
                <tr>
                  <td>{{ $playlist->channel_id }}</td>
                  <td>{{ $playlist->title }}</td>
                  <td>{{ $playlist->privacy }}</td>
                  <td>
                      <form action="{{ url('makePlaylist/' . $playlist->id) }}" method="GET" class="create_playlist">

                          {{ csrf_field() }}

                          <button class="btn waves-effect waves-light" type="submit" name="action">Create
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
      $(".button-collapse").sideNav();
      $('.modal').modal();
      $('.collapsible').collapsible();
    });
    </script>
    </body>
</html>
