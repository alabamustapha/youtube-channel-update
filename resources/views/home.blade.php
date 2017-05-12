<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Update Youtube Channel Videos</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css">

    </head>
    <body>

    <div class="container">
      <form action="#">


          @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="file-field input-field">
            <div class="btn">
              <span>File</span>
              <input type="file">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" id="filled-in-box-thumbnails" />
              <label for="filled-in-box-thumbnails">Change Thumbnails</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" id="filled-in-box-titles" />
              <label for="filled-in-box-titles">Change titles</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" id="filled-in-box-description" />
              <label for="filled-in-box-description">Change description</label>
            </div>

            <div class="col s12 m6 l3">
              <input type="checkbox" class="filled-in" id="filled-in-box-tags" />
              <label for="filled-in-box-tags">Change tags</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12 m6">
              <select>
                <option value="" disabled selected>Choose your option</option>
                <option value="private">Private</option>
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
              </select>
              <label>Privacy</label>
            </div>

            <div class="input-field col s12 m6">
              <select>
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

        </form>
    </div>


    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script>
    $(document).ready(function() {
      $('select').material_select();
    });
    </script>
    </body>
</html>
