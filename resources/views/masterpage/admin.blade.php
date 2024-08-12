


<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Panel')</title>
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icono.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icono.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icono.png">
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  
  
  


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.min.css?v={{time()}}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('css')
  

</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    @include('masterpage.sidebar')
    <div class="body-wrapper">
    @include('masterpage.header')
      <div class="container-fluid">
        
          @yield('content')
        
        {{--@include('masterpage.footer')--}}
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/dashboard.js?v=23"></script>
  <script src="{{ asset('node_modules/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

  
   @yield('js')
</body>
</html>