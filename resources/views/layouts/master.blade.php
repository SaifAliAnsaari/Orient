<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Orient</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/css/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/css/select2-bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=2.1.2.3">
    <link rel="stylesheet" type="text/css" href="/css/dropify.min.css" />
    <link href="/css/datepicker.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/dropify.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/dropzone.css" />

</head>

<body id="page-top">

    <div class="overlay"></div>
    
    <div id="notifDiv" style="z-index:10000; display: none; background: green; font-weight: 450; width: 350px; position: fixed; top: 80%; left: 5%; color: white; padding: 5px 20px">
    </div>

    @include('includes.nav')
    @include('includes.modals')
    <div id="wrapper">
        @include('includes.sidebar-menu')
        
        <div id="content-wrapper">
            @include('includes.alerts')
            <div class="container">
                @yield('data-sidebar')
                @yield('content')
                @include('includes.footer')
            </div>
        </div>
    </div>
    
{{-- Yeh script saifaliansaari wali id say generate hoe hai --}}
{{-- <script src="https://www.gstatic.com/firebasejs/5.8.1/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBuZmby-7-Q7UXubpJoeuL78Himbj4ZCRg",
    authDomain: "finewater-937ea.firebaseapp.com",
    databaseURL: "https://finewater-937ea.firebaseio.com",
    projectId: "finewater-937ea",
    storageBucket: "finewater-937ea.appspot.com",
    messagingSenderId: "528529030015"
  };
  firebase.initializeApp(config);
</script> --}}

{{-- Yeh Script allomate wali id say generate hoe hai --}}
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAuIIvZDR_w4nMaXVWz56_rMkCO1epYdKc",
    authDomain: "fine-water.firebaseapp.com",
    databaseURL: "https://fine-water.firebaseio.com",
    projectId: "fine-water",
    storageBucket: "",
    messagingSenderId: "972188091611"
  };
  firebase.initializeApp(config);
</script>


    <script src="/js/jquery-3.3.1.slim.min.js"></script>
    <script src="/js/popper.min.js" ></script>
    <script src="/js/bootstrap.min.js" ></script>
    <script src="/js/datatables.min.js" ></script>
    <script src="/js/select2.min.js" ></script>
    <script src="/js/dropify.min.js"></script>
    <script src="/js/form-file-upload-data.js"></script>
    <script src="/js/custom.js" ></script>
    <script src="/js/master.js?v=1.0" ></script>
    <script src="/js/jquery.form.min.js" ></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    {{-- <script type = "text/javascript" src"https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script> --}}
     <script src="/js/dropzone.js"></script>
    {{--<script src="/js/dropzone-data.js"></script> --}}
    @if($controller == "Customer")
        <script src="/js/custom/customer.js?v=1.0" ></script>
        </script>
    @elseif($controller == "RegisterController")
        <script src="/js/custom/employee.js?v=1.0" ></script>
   @elseif($controller == "ReportManagment")
        <script src="/js/reports_managment/reports.js?v=1.0" ></script>
    @elseif($controller == "Categories")
        <script src="/js/categories/categories.js?v=1.0" ></script>
    @elseif($controller == "NotificationCenter")
        <script src="/js/notif/notif_pref.js?v=1.0" ></script>
    @elseif($controller == "AccessRights")
        <script src="/js/access_rights/access_rights.js?v=1.0" ></script>
    @endif

</body>

</html>