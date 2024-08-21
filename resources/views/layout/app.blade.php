<!DOCTYPE html>
<html lang="en" data-bs-theme='light'>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>X-Bakery</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('/fabicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css')}}">
  <link rel="stylesheet" href="{{ asset('css/toastify.min.css') }}">
  <link rel="stylesheet" href="{{ asset('js/toastify-js.js') }}">
  <link rel="stylesheet" href="{{ asset('js/axios.min.js') }}">
  <link rel="stylesheet" href="{{ asset('js/config.js') }}">
</head>
<body>
  
  <div id="loader" class="loadingOverlay">
    <div class="Line-Progress">
      <div class="indeterminate"></div>
    </div>
  </div>

  <div>
    @yield('content')
  </div>

  <script>

    
  </script>

  <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>