<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 404</title>
    <style>
        .container{
            display: flex; justify-content: center;
        }
        .btn{
            padding: 20px;
            font-size: 18px;
            /* background: #89daf1;
            border: solid 1px #404f69; */
            background: #404f69;
            border: solid 1px #373c5a;
            border-radius: 15px;
            cursor: pointer;
            color: #fff;
        }
        .btn:hover{
            /* background: #7fcae5; */
            background: #363c5a;
        }
    </style>
</head>
<body>
    <div class="container"><img src="{{ asset('recursos/404.gif') }}" height="500px"></div>
    <div class="container">
        <button type="button" class="btn" onclick="cerrarVentana();">Cerrar ventana</button>
    </div>
    <script>
        function cerrarVentana(){
            window.close();
        }
    </script>
</body>
</html>