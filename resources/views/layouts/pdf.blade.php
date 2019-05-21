<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte</title>
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="main.css" /> -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <!-- <script src="main.js"></script> -->
    <style>
    /* footer .pagenum:before {
      content: counter(page);
    } */
    /* .pagenum:before {
    content: counter(page);
    } */
    @page { margin: 50px 50px; }
    /* header { position: fixed; left: 0px; top: -150px; right: 0px; height: 150px;  text-align: center; } */
    footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 150px; text-align: right;  }
    footer .page:after { content: counter(page); } /*counter(page, upper-roman)*/
</style>
</head>
<body>
    <header class="text-center">
        <img class="rounded mx-auto d-block  img-fluid" src="img/JAT.png" width="100" alt="First slide">
        <h4><b>Inmobiliaria Jat</b></h4><br/>
    </header>
    <footer>
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-left"><strong>Fecha: </strong>{{$fechaActual}}</h6>
            </div>
            <div class="col-md-8">
                <h6><strong>Pag. <span class="page"></span></strong></h6>
            </div>
        </div>        
    </footer>
    <main id="content">
        @yield('content')
    </main>
</body>
</html>