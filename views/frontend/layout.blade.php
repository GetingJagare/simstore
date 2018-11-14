<!doctype html>
<html lang="ru">
<head>
    @include('counters.gtm')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&amp;subset=cyrillic-ext"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css?v=2.0.6') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">

    {!! SEO::generate() !!}
</head>
<body>
@include('counters.body-counters.gtm')
<script>
    var APP_DOMAIN = '{{ env('APP_DOMAIN') }}',
        CART = {
            numbers: {!! json_encode(getNumbersIdsInCart()) !!},
            tariffs: {{ json_encode(getTariffsIdsInCart()) }}
        };
</script>

<div id="app">
    <header>
        <div class="container">
            <div class="d-flex flex-nowrap justify-content-between">
                <div class="d-flex flex-nowrap">
                    <div class="header-logo" data-href="/">
                        <img src="/images/logo.png?v=2.0" alt="" class="header-logo__image"/>
                        {{--<b class="d-block">Sim-Store</b>
                        <span>Мы предоставляем в пользование статусные номера золотой и серебряной серий.</span>--}}
                    </div>
                    <select-city :current='{!! json_encode($region) !!}'></select-city>
                </div>
                <div class="header-mobile-menu d-flex align-items-center d-sm-none">
                    <a href="/korzina" class="header-mobile__basket"><span
                                class="basket-mobile-count">{{ cartCount() }}</span>Корзина</a>
                    <a href="/kabinet-abonenta" class="header-mobile__profile">Профиль</a>
                    <a href="#" class="header-mobile__menu" v-on:click="openMenu">Меню</a>
                </div>
                <div class="flex-nowrap d-none d-sm-flex">
                    <div class="header-phone d-inline-flex align-items-center">
                        <span class="header-phone__label">Отдел продаж:</span>
                        <span>8 800 100 87 18</span>
                    </div>
                    <div class="header-divider d-inline-flex align-items-center">
                        <span></span>
                    </div>
                    <div class="header-lk d-inline-flex align-items-center">
                        <a href="/kabinet-abonenta"><span
                                    class="d-md-none d-sm-none d-lg-inline-block">Войти в аккаунт</span></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <nav>
        <div class="container">
            <div class="d-flex justify-content-between">
                <div>
                    <ul class="d-flex flex-wrap flex-sm-nowrap nav-menu">
                        <li class="has-dropdown">
                            <a href="/nomera/krasivye">Красивые номера</a>
                            <span class="open-dropdown"></span>
                            <ul class="dropdown d-none">
                                <li><a href="/nomera/zolotye">Золотые</a></li>
                                <li><a href="/nomera/serebrjanye">Серебряные</a></li>
                                <li><a href="/nomera/bronzovye">Бронзовые</a></li>
                                {{--<li><a href="/nomera/platinovye">Платиновые</a></li>--}}
                                <li><a href="/nomera/aktsionnye">Акционные</a></li>
                                <li><a href="/nomera/arenda-nomera">Аренда</a></li>
                                <li><a href="/nomera/kupit-simkarti">Сим-карты</a></li>
                                <li><a href="/nomera/nomer-na-zakaz">Номер на заказ</a></li>
                            </ul>
                        </li>
                        <li class="has-dropdown">
                            <a href="/tarify">Тарифы</a>
                            <span class="open-dropdown"></span>
                            <ul class="dropdown d-none">
                                <li><a href="/tarify/bezlimitnye">Безлимитные</a></li>
                                <li><a href="/tarify/dlja-zvonkov-po-rossii">Безлимитные Россия</a></li>
                                <li><a href="/tarify/internet">Для Интернета</a></li>
                                <li>
                                    <a href="/tarify/dlja-zvonkov-po-{{ str_slug($region['name_dat']) }}">
                                        Для звонков по {{ $region['name_dat'] }}
                                    </a>
                                </li>
                                <li><a href="/tarify/vygodnye">Выгодные</a></li>
                                <li><a href="/tarify/dlja-zvonkov-za-granicu">Для звонков за границу</a></li>
                            </ul>
                        </li>
                        <li><a href="/informatsiya">Информация</a></li>
                        <li><a href="/aktsii">Акции</a></li>
                    </ul>
                </div>
                <div class="nav-basket d-none d-sm-inline-flex align-items-center">
                    <a href="/korzina">Корзина: <span class="basket-count">{{ cartCount() }}</span></a>
                </div>
            </div>
            <div class="d-block d-md-none">
                <div class="mobile-info text-center">
                    <b>8 800 100 87 18</b><br>
                    Отдел продаж: с 9:00 до 19:00<br>
                    support@sim-store.ru
                </div>
            </div>
        </div>
    </nav>

    <div class="advantages">
        <div class="container">
            <div class="advantages-wrap d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center">
                <div class="advantages-item ai-1">
                    <span>Можно перейти со своим номером</span>
                </div>
                <div class="advantages-item ai-2">
                    <span>Бесплатная доставка по всей России</span>
                </div>
                <div class="advantages-item ai-3">
                    <span>Скидки до 50% на номера</span>
                </div>
                <div class="advantages-item ai-4">
                    <span>Более 15 000 номеров на выбор</span>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <footer>
        <div class="container">
            <div class="footer-wrap row">
                <div class="col-sm-4 col-md-4 d-flex align-items-center justify-content-center justify-content-sm-start text-center text-sm-left">
                    <div class="footer-logo">
                        <img src="/images/logo_footer.png" alt="" class="footer-logo__image"/>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 d-flex align-items-center">
                    <div class="footer-socials text-center">
                        <a href="#" class="fb">Мы на Facebook</a>
                        <a href="#" class="tw">Мы в Twitter</a>
                        <a href="https://vk.com/sim_store_rus" class="vk">Мы во Вконтакте</a>
                        <a href="#" class="ok">Мы в Одноклассниках</a>
                        <a href="#" class="yt">Мы на YouTube</a>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 d-flex align-items-center justify-content-center justify-content-sm-end">
                    <div class="text-center text-sm-right footer-right">
                        <b>8 800 100 87 18</b><br>
                        Отдел продаж: с 10:00 до 21:00<br>
                        support@sim-store.ru<br>
                        <a href="/politika-konfidentsialnosti">Политика конфиденциальности</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <modals-container/>
</div>

<link rel="stylesheet" href="{{ asset('/css/vendor.css') }}">
<script src="{{ asset('/js/app.js?v=2.0.5') }}" async></script>
@include('counters.ya')
</body>
</html>