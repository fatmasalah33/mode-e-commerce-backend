<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Readerstacks Implement Facebook Login or Signup in laravel 8 / 9 </title>

  <link href="//netdna.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.1/css/all.min.css" integrity="sha512-9my9Mb2+0YO+I4PUCSwUYO7sEK21Y0STBAiFEYoWtd2VzLEZZ4QARDrZ30hdM1GlioHJ8o8cWQiy8IAb1hy/Hg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="antialiased">
  <div class="container">
    <!-- main app container -->
    <div class="readersack">
      <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <h3>  Implement Facebook Login or Signup in laravel 8 / 9 - Readerstacks</h3>

            @if(\Auth::check())
              <div class='dash'>You are logged in as  : {{\Auth::user()->email}} ,  <a href="{{url('logout')}}"> Logout</a></div>
            @else
            <div class='dash '>
              <div class='error'> You are not logged in  </div>
              <div>  <a href="{{url('login')}}">Login</a> | <a href="{{url('register')}}">Register</a> </div>
            </div>

            @endif

          </div>
        </div>
      </div>
    </div>
    <!-- credits -->
    <div class="text-center">
      <p>
        <a href="#" target="_top"> Implement Facebook Login or Signup in laravel 8 / 9 - Readerstacks</a>
      </p>
      <p>
        <a href="https://readerstacks.com" target="_top">readerstacks.com</a>
      </p>
    </div>
  </div>
</body>



</html>
