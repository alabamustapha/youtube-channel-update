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

      <nav>
        <div class="nav-wrapper teal">
          <ul id="nav-mobile" class="left hide-on-med-and-down">

              <li>  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">settings_power</i> Logout</a></li>
              <li><a href="{{ url('getAccessToken') }}"><i class="material-icons left">vpn_key</i> Aceess Tokens</a></li>
              <li><a href="{{ url('/') }}"><i class="material-icons left ">home</i> Home</a></li>
            </ul>
          </div>
        </nav>


      @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class= "row" style="margin-top: 50px;">
            <div class="col s3">
                <a href="#!" class="collection-item"><span class="badge"> {{ $channels->count() }}</span>Total Authorize channel</a>
            </div>
        </div>
        <div class="row">


            <table class="striped bordered">
                <thead>
                  <tr>
                      <th>Owner Name</th>
                      <th>Channel ID</th>
                      <th>Access Token</th>
                      <th>Refresh Token</th>
                      <th>Action</th>
                  </tr>
                </thead>

                <tbody>
                @foreach($channels as $channel)
                  <tr>
                    <td>{{ $channel->name }}</td>
                    <td>{{ $channel->channel_id }}</td>
                    <td>{{ $channel->access_token == '' ? 'Unautorized' : 'Autorized' }}</td>
                    <td>{{ $channel->access_token == '' ? 'Unautorized' : 'Autorized' }}</td>
                    <td>
                        <form action="{{ url('revokeToken/' . $channel->id) }}" method="POST">

                            {{ csrf_field() }}

                            <input type="hidden" name="oauth2_client_secret" value="{{ $channel->oauth2_client_secret }}"/>
                            <input type="hidden" name="oauth2_client_id" value="{{ $channel->oauth2_client_id }}"/>

                            <button class="btn waves-effect waves-light" type="submit" name="action">Revoke Access
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
