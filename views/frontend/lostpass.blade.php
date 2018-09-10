<?php
$Request = [];

if(isset($_POST['X_username'])){

    $Request = json_decode(file_get_contents('http://lk.gsmcorp.tarifer.ru/restore_password/'.urlencode($_POST['X_username']).'.json', false,  stream_context_create(array("http"=>array(
        "method"=>"POST",
        "header"=> array(),
        "Content-type: application/x-www-form-urlencoded",
        "content"=>""
    )))), true);
}
?>

@extends('frontend.layout')
@section('content')
    <main class="profile-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-center">

                <div class="profile-login text-center">

                    <div class="big-h1">Восстановление пароля / Регистрация</div>

                    <form class="profile-login__form" method="post" accept-charset="windows-1251">

                        @if(isset($Request['success']))
                            @if($Request['success'])
                                <div style="color: green;margin: 0 0 15px;">{{ $Request['message'] }}</div>
                            @else
                                <div style="color: red;margin: 0 0 15px;">{{ $Request['message'] }}</div>
                            @endif
                        @endif

                        <div>
                            <input name="X_username" class="diler_input_login" id="appleId" type="text" placeholder="Номер телефона (10 цифр)">
                        </div>
                        <button class="button" type="submit">Отправить</button>
                    </form>

                    <div class="abonent_url">
                        <div><a href="/profile">Войти в аккаунт</a></div>
                        <!--<div><a href="/diler/">&lt;&lt; Кабинет дилера</a></div>-->
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection