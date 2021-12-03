<!doctype html>
<html lang="{{-- str_replace('_', '-', app()->getLocale()) --}}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="shortcut icon" href="{{-- asset('img/ecoref.ico') --}}" type="image/x-icon" />
  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->  
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Toastr --> 
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">

  @stack('styles')

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset('adminlte/css/skins/skin-blue.min.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">    
</head>
<body class="hold-transition skin-blue login-page">

<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
    <a href="{{-- route('home') --}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>YI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>{{ config('app.name', 'Laravel') }}</b></span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
     
      <span title="Fecha Actualización" class="logo-lg" 
            style="color: #fff; font-size: 20px; line-height: 50px; text-align: center; font-weight: 300;">
        <b id="spn-hora_actual">Hora Actual</b>
      </span>
      
      <div class="navbar-custom-menu">
        
        <ul class="nav navbar-nav">
          <!--<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
        
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="{{-- asset('img/ecoref-160x160.jpg') --}}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
            
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>-->
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{-- asset('img/ecoref-160x160.jpg') --}}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{-- Auth::user()->person->name --}} {{-- Auth::user()->person->last_name --}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{-- asset('img/ecoref-160x160.jpg') --}}" class="img-circle" alt="User Image">

                <p>
                  {{-- Auth::user()->person->name --}} {{-- Auth::user()->person->last_name --}} - Web Developer
                  <small>{{-- auth()->user()->created_at->diffForhumans() --}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Pfrofile</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat"
                        href="{{-- route('logout') --}}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    {{-- __('Logout') --}}</a>
                  <form id="logout-form" action="{{-- route('logout') --}}" method="POST" style="display: none;">
                      @csrf
                  </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{-- asset('img/ecoref-160x160.jpg') --}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{-- Auth::user()->person->name --}} {{-- Auth::user()->person->last_name --}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) 
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu   -->
            @include('partials.nav')                                
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      @yield('header')
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      
      @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Indicadores
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">-</a>.</strong> Todos los derechos reservados.
  </footer>

  <!-- Control Sidebar -->
    <!-- SACADO CONTROL SIDEBAR -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <!--<div class="control-sidebar-bg"></div>-->
</div>

  <!-- jQuery 3 -->
  <script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
  <!-- Axios Paquete -->
  <script src="{{ asset('js/app.js') }}"></script>
   
  @stack('scripts')

  <script>

    $(function () {

      $('.notification-toastr').click(function() {
        //toastr.success('Mensaje.', 'Titulo', {  'progressBar': false, 'timeOut': '0' })    
        success("Hola mud", "titulooo", "1000")
      });
      //, { timeOut: 9500 }, info, warning, error

      @if ($errors->any())
        @foreach ($errors->all() as $error) 
          toastr.error("{{ $error }}", '', {  'progressBar': false, 'timeOut': '0' })
        @endforeach
      @endif

      @if (Session::has('message'))
        toastr.success("{{ Session::get('message') }}", '', {  'progressBar': false, 'timeOut': '0' })  
      @endif
    })

    function success(mensaje, titulo, duracion='0') {
      barra = false;
      if (parseInt(duracion) > 0) {
        barra = true;
      }
      toastr.success(mensaje, titulo, {  'progressBar': barra, 'timeOut': duracion })    
    }

    function error(mensaje, titulo, duracion='0') {
      barra = false;
      if (parseInt(duracion) > 0) {
        barra = true;
      }
      toastr.error(mensaje, titulo, {  'progressBar': false, 'timeOut': '0' })    
    }

    function warning(mensaje, titulo, duracion='0') {
      barra = false;
      if (parseInt(duracion) > 0) {
        barra = true;
      }
      toastr.warning(mensaje, titulo, {  'progressBar': false, 'timeOut': '0' })    
    }

    function info(mensaje, titulo, duracion='0') {
      barra = false;
      if (parseInt(duracion) > 0) {
        barra = true;
      }
      toastr.info(mensaje, titulo, {  'progressBar': false, 'timeOut': '0' })    
    }
  </script>
</body>
</html>
