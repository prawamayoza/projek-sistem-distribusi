<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/logo-cv.png">
  <link rel="icon" type="image/png" href="../assets/img/logo-cv.png">
  <title>Login</title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
</head>

<body>
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100">
      <span class="mask bg-gradient-dark"></span>
      <div class="container my-auto">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 text-center">
                <img src="{{ asset('/assets/img/logo-cv.png') }}" alt="Logo" class="centered-logo">
              </div>
              <div class="card-body">
                <form role="form" class="text-start" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Sign in</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>

<style>
  .card-header {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px; /* Optional: Add padding to the header */
  }

  .centered-logo {
      max-width: 80%; /* Ensure the logo fits within the card */
      height: auto; /* Maintain aspect ratio */
      margin-top: 10px; /* Optional: Add top margin */
      margin-bottom: 10px; /* Optional: Add bottom margin */
  }

  .page-header {
      background: linear-gradient(90deg, #4e54c8, #8f94fb); /* Background gradient */
  }

  .btn.bg-gradient-info {
      background-color: #4285f4; /* Custom button color */
      border-radius: 12px;
  }

  .input-group-outline .form-control {
      border-radius: 8px;
  }

  .input-group-outline .form-label {
      color: #4285f4;
  }

  .invalid-feedback {
      font-size: 0.875em;
      margin-top: 0.25rem;
  }
</style>
