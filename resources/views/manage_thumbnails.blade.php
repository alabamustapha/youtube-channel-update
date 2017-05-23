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
              <li><a href="{{ url('/') }}"><i class="material-icons left ">home</i> Home</a></li>
            </ul>

            <ul class="side-nav" id="mobile-demo">
              <li>  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">settings_power</i> Logout</a></li>
              <li><a href="{{ url('getAccessToken') }}"><i class="material-icons left">vpn_key</i> Access Tokens</a></li>
              <!-- <li><a href="collapsible.html"><i class="material-icons left ">home</i> Home</a></li> -->
              <li><a href="{{ url('AuthorizedChannels') }}"><i class="material-icons left">vpn_key</i> Authorized Channel</a></li>
              <li><a href="{{ url('/') }}"><i class="material-icons left ">home</i> Home</a></li>
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
          <div class="col s6">
              <!-- Modal Trigger -->
              <a class="waves-effect waves-light btn" href="#modal1">New thumbnails Folder</a>
          </div>

          <!-- Modal Structure -->
          <div id="modal1" class="modal">
            <div class="modal-content">
              <h4>Modal Header</h4>
              <form action="{{ url('createThumbnailsFolder') }}" method="POST" enctype="multipart/form-data">


                {{ csrf_field() }}

                <div class="file-field input-field">
                    <div class="btn">
                      <span>File</span>
                      <input type="file" name="thumbnail_folder">
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
            <div class="modal-footer">
              <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
          </div>

        </div>

        <div class="row">
            <div class="col s12">
              <h4>{{ count($directories) }} Thumbanails directories available</h4>
              <ul class="collapsible" data-collapsible="accordion">
              @foreach($directories as $directory)
              <li>
                <div class="collapsible-header">
                  <i class="material-icons">folder</i>{{ array_last(explode('\\', $directory)) }}
                  <form class="right" method="POST" action="{{ url('deleteThumbnailsFolder') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="thumbnail_folder" value="{{ $directory }}">
                    <button class="btn waves-effect waves-light btn-small" style="padding: 0;" type="submit" name="action">
                        <i class="material-icons right">delete</i>
                      </button>
                  </form>
                </div>
                <div class="collapsible-body">
                    <ul>

                      @foreach(array_get($files, $directory) as $file)

                        <li><i class="material-icons">perm_media</i> {{ array_last(explode('/', $file)) }}
                          <!-- <a><i class="material-icons right">delete</i></a> -->
                        </li>
                      @endforeach
                    </ul>
                </div>
              </li>
              @endforeach

            </ul>
            </div>
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
