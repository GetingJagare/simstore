<?php
$Request = [];

if (isset($_REQUEST['X_username'])) {

    $curl = curl_init('http://lk.gsmcorp.tarifer.ru/restore_password/' . urlencode($_REQUEST['X_username']) . '.json');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Accept: application/json"]);

    $Request = json_decode(curl_exec($curl), true);
    curl_close($curl);
}
?>

@extends('frontend.layout')
@section('content')
    <main class="profile-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-center">

                <div class="profile-login text-center">

                    <h1 class="big-h1">{{ $page->alter_name ?: $page->name }}</h1>

                    <form class="profile-login__form" method="get" accept-charset="windows-1251">

                        @if(isset($Request['success']))
                            @if($Request['success'])
                                <div style="color: green;margin: 0 0 15px;">{{ $Request['message'] }}</div>
                            @else
                                <div style="color: red;margin: 0 0 15px;">{{ $Request['message'] }}</div>
                            @endif
                        @elseif(isset($Request['status']) && $Request['status'] == '404')
                            <div style="color: red;margin: 0 0 15px;">Такой пользователь не зарегистрирован</div>
                        @endif

                        <div>
                            <input name="X_username" class="diler_input_login" id="appleId" type="text"
                                   placeholder="Номер телефона (10 цифр)">
                        </div>
                        <button class="button" type="submit">Отправить</button>
                    </form>

                    <div class="abonent_url">
                        <div><a href="/kabinet-abonenta">Войти в аккаунт</a></div>
                        <!--<div><a href="/diler/">&lt;&lt; Кабинет дилера</a></div>-->
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection