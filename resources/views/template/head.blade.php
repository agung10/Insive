<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin | @yield('title-page')</title>
  <link rel="stylesheet" href="{{ asset('plugins/simplebar/simplebar.css') }}">
  @yield('css')
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
  integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <style media="screen">
    .nav-treeview {
      text-indent: 15px;
    }
    aside {
      z-index: 1040 !important;
    }
    .text-no-decoration {
      text-decoration: none;
    }
    .invalid-feedback {
        display: block !important;
        padding: 0 10px !important;
    }
    .alert-danger {
      background-color: #f8d7da !important;
      border-color: #f5c6cb !important;
      color: #721c24 !important;
    }
    .not-italic {
      font-style: normal
    }
  </style>
</head>
