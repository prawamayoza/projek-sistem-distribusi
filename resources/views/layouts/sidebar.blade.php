<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="{{('/assets/img/logo-ct.png')}}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">CV Bintang Berkah</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('home*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('home')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('user*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('user.index')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Kelola user</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('pelanggan*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('pelanggan.index')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Kelola Pelanggan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('pesanan*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('pesanan.index')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">shopping_basket</i>
            </div>
            <span class="nav-link-text ms-1">Kelola Pesanan Pelanggan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('kendaraan*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('kendaraan.index')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">directions_car</i>
            </div>
            <span class="nav-link-text ms-1">Kelola Kendaraan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->is('data-set*') ? 'active  bg-gradient-warning' : '' }}" href="{{route('data-set.index')}}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dataset</i>
            </div>
            <span class="nav-link-text ms-1">Kelola Data Perhitungan</span>
          </a>
        </li>
      </ul>
    </div>

  </aside>