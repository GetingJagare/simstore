@extends('frontend.layout')
@section('content')
    <main class="profile-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-center">

                <div class="profile-login text-center">

                    <h1 class="big-h1">{{ $page->alter_name ?: $page->name }}</h1>

                    <lostpass-form></lostpass-form>

                    <div class="abonent_url">
                        <div><a href="/kabinet-abonenta">Войти в аккаунт</a></div>
                        <!--<div><a href="/diler/">&lt;&lt; Кабинет дилера</a></div>-->
                    </div>

                </div>

            </div>
        </div>

    </main>
@endsection