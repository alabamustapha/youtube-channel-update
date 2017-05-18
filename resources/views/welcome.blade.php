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
              <!-- <li><a href="collapsible.html"><i class="material-icons left ">home</i> Home</a></li> -->
              <li><a href="{{ url('AuthorizedChannels') }}"><i class="material-icons left">vpn_key</i> Authorized Channel</a></li>
            </ul>

            <ul class="side-nav" id="mobile-demo">
              <li>  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">settings_power</i> Logout</a></li>
              <li><a href="{{ url('getAccessToken') }}"><i class="material-icons left">vpn_key</i> Access Tokens</a></li>
              <!-- <li><a href="collapsible.html"><i class="material-icons left ">home</i> Home</a></li> -->
              <li><a href="{{ url('AuthorizedChannels') }}"><i class="material-icons left">vpn_key</i> Authorized Channel</a></li>
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

      <form action="{{ url('/') }}" method="POST" enctype="multipart/form-data">


        {{ csrf_field() }}

        <div class="file-field input-field">
            <div class="btn">
              <span>File</span>
              <input type="file" name="youtube">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" name="thumbnail" id="filled-in-box-thumbnails" />
              <label for="filled-in-box-thumbnails">Change Thumbnails</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" name="title" id="filled-in-box-titles" />
              <label for="filled-in-box-titles">Change titles</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" name="description" id="filled-in-box-description" />
              <label for="filled-in-box-description">Change description</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" name="tag" id="filled-in-box-tags" />
              <label for="filled-in-box-tags">Change tags</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12 m6">
              <select name="privacyStatus">
                <option value="" disabled selected>Choose your option</option>
                <option value="private">Private</option>
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
              </select>
              <label>Privacy</label>
            </div>

            <div class="input-field col s12 m6">
              <select name="categoryId">
                <option value="" disabled selected>Choose your option</option>
                <option value="1">Film &amp; Animation</option>
                <option value="2">Autos &amp; Vehicles</option>
                <option value="10">Music</option>
                <option value="15">Pets &amp; Animals</option>
                <option value="17">Sports</option>
                <option value="19">Travel &amp; Events</option>
                <option value="20">Gaming</option>
                <option value="22">People &amp; Blogs</option>
                <option value="23">Comedy</option><option label="Entertainment" value="string:24">Entertainment</option>
                <option value="25">News &amp; Politics</option>
                <option value="26">Howto &amp; Style</option>
                <option value="27">Education</option>
                <option value="28">Science &amp; Technology</option>
                <option value="29">Nonprofits &amp; Activism</option>
              </select>
              <label>Category</label>
            </div>
        </div>

        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
            <i class="material-icons right">send</i>
          </button>

        </form>



        @if (session('count'))
        <div class="row">
            <div class="col s3">
                  <span class="new badge" data-badge-caption="Video updated">{{ session('count') }}</span>
            </div>
        </div>
        @endif
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
    });
    </script>
    </body>
</html>
