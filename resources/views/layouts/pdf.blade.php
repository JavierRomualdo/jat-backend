<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="main.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <center>
                <img class="rounded mx-auto d-block  img-fluid" src="img/JAT.png" width="100" alt="First slide">
                <h3><b>Inmobiliaria Jat</b></h3><br/>
            </center>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-xs-12">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
</body>
</html>