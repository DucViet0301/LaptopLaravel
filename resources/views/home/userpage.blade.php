<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>StoreLapTop</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
       
        <link href="home/css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- header-->
        @include('home.header')
        <!-- bander-->
        @include('home.banner')
        <!-- Section-->
        @include('home.section')

        @include('home.comment',['comment'=>$comment,'reply'=>$reply])
        
        @include('home.botman')
        <!-- Footer-->
        @include('home.footer')
        <!-- Bootstrap core JS-->
        @include('home.js')
    </body>
</html>
