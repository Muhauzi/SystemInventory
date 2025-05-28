@use ('Illuminate\Support\Facades\Auth');
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>
    {{$title}}
  </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="{{asset('NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('NiceAdmin/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




  <!-- Template Main CSS File -->
  <link href="{{asset('NiceAdmin/assets/css/style.css')}}" rel="stylesheet">
</head>

<body>
  <x-topbar></x-topbar>
  <x-navbar></x-navbar>
  <main id="main" class="main">
    {{$slot}}
  </main>
  <x-footer></x-footer>
  <x-alert></x-alert>
</body>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{asset('NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/chart.js/chart.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/echarts/echarts.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/quill/quill.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('NiceAdmin/assets/vendor/php-email-form/validate.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('.table-data').DataTable();
  });
</script>

<!-- Template Main JS File -->
<script src="{{asset('NiceAdmin/assets/js/main.js')}}"></script>


</body>

</html>