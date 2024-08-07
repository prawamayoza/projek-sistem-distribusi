<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset("/assets/img/logo-cv.png")}}">
  <link rel="icon" type="image/png" href="{{asset('/assets/img/logo-cv.png')}}">
  @hasSection('title')
        <title>@yield('title')</title>
    @else
        <title>{{ $title ?? config('app.name') }}</title>
    @endif
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset("/assets/css/nucleo-icons.css")}}" rel="stylesheet" />
  <link href="{{asset("/assets/css/nucleo-svg.css")}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset("/assets/css/material-dashboard.css?v=3.1.0")}}" rel="stylesheet" />
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('head')
  @stack('styles')
</head>

<body class="g-sidenav-show  bg-gray-200">
  @include('layouts/sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @include('layouts/header')
    <!-- End Navbar -->
    @yield('content')

  </main>
  <!-- Core JS Files -->
  <script src="{{asset('../assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('../assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset("/assets/js/plugins/chartjs.min.js")}}"></script>
  <!-- jQuery and DataTables -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Script for DataTables and SweetAlert -->
  <script>
    $(document).ready(function() {
      $('#mytable').DataTable();

      // Setup CSRF token for Ajax requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Delete button click handler with SweetAlert
      $(document).on('click', '.delete', function() {
        let url = $(this).val();
        console.log(url);
        Swal.fire({ // Use Swal.fire instead of swal
          title: "Apakah anda yakin?",
          text: "Setelah dihapus, Anda tidak dapat memulihkan data ini lagi dan data yang berhubungan akan ikut terhapus!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya, hapus saja!",
          cancelButtonText: "Batal"
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              type: "DELETE",
              url: url,
              dataType: 'json',
              success: function(response) {
                Swal.fire({
                  title: response.status,
                  icon: "success"
                }).then((result) => {
                  location.reload();
                });
              },
              error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseText); // Log any errors to console
                Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
              }
            });
          }
        });
      });
    });
  </script>
  <!-- Your additional scripts -->
  @yield('script')
</body>

</html>
