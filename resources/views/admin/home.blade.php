<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    <!-- plugins:css -->
    @include('admin.css')
    <!-- End layout styles -->

  </head>
  <body>
    <div class="container-scroller">

      @include('admin.sidebar')

      <div class="container-fluid page-body-wrapper">

        @include('admin.header')

        @include('admin.body')

      </div>

    </div>

    @include('admin.js')

  </body>
</html>