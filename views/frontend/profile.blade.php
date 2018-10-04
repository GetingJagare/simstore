<?php

$WrongNumber = '';
if(isset($_POST['X_username']) && isset($_POST['X_password'])){
    $Request = json_decode(file_get_contents('http://lk.gsmcorp.tarifer.ru/get_access_token.json?mobile=false&phone='.urlencode($_POST['X_username']).'&password='.urlencode($_POST['X_password'])), true);
    if($Request['success']){
        header('Location: http://lk.gsmcorp.tarifer.ru/login_with_token?token='.$Request['access_token']);
    }else{
        $WrongNumber = '<div style="color: red;margin: 0 0 15px;">Пароль не верен</div>';
    }
} ?>

@extends('frontend.layout')
@section('content')
    <main class="profile-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-center">

                <div class="profile-login text-center">

                    <div class="big-h1">Кабинет абонента</div>
                    <div class="diler_text">
                        <p>Центр обслуживания клиентов.<br>Тут вы можете найти онлайн - счета и другую полезную информацию.</p>
                    </div>

                    <form class="profile-login__form" method="post" accept-charset="windows-1251">
                        {!! $WrongNumber !!}
                        <div>
                            <input name="X_username" class="diler_input_login" id="appleId" type="text"  placeholder="Логин">
                        </div>
                        <div>
                            <input id="pwd" name="X_password" type="password"  class="diler_input_pass" placeholder="Пароль">
                        </div>
                        <button class="button" type="submit">Авторизация</button>
                    </form>

                    <div class="abonent_url">
                        <div><a href="/vosstanovlenie-dostupa-registratsiya">Восстановление пароля / Регистрация</a></div>
                        <!--<div><a href="/diler/">&lt;&lt; Кабинет дилера</a></div>-->
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection