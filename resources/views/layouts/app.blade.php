<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset("/assets/img/apple-icon.png")}}">
  <link rel="icon" type="image/png" href="{{asset('/assets/img/favicon.png')}}">
  <title>
    Material Dashboard 2 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset("/assets/css/nucleo-icons.css")}}" rel="stylesheet" />
  <link href="{{asset("/assets/css/nucleo-svg.css")}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset("/assets/css/material-dashboard.css?v=3.1.0")}}" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
    @include('layouts/sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @include('layouts/header')
    <!-- End Navbar -->
   @yield('content')

    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{asset('../assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('../assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset("/assets/js/plugins/chartjs.min.js")}}"></script>

  @yield('script')
</body>

</html>
