<!doctype html>
<html lang="ru">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/css/admin.css') }}">
</head>
<body>

<div id="app">

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-4">


                </div>
                <div class="col-md-4">
                    <div class="inner-box">
                        <auth></auth>
                    </div>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
    </main>


</div>


<script src="{{ asset('/js/admin.js') }}"></script>
</body>
</html>