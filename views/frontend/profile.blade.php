@extends('frontend.layout')
@section('content')
    <main class="profile-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-center">

                <div class="profile-login text-center">

                    <h1 class="big-h1">{{ $page->alter_name ?: $page->name }}</h1>
                    <div class="diler_text">
                        <p>Центр обслуживания клиентов.<br>Тут вы можете найти онлайн - счета и другую полезную информацию.</p>
                    </div>

                    <login-form></login-form>

                    <div class="abonent_url">
                        <div><a href="/vosstanovlenie-dostupa-registratsiya">Восстановление пароля / Регистрация</a></div>
                        <!--<div><a href="/diler/">&lt;&lt; Кабинет дилера</a></div>-->
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection