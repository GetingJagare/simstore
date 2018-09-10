<!doctype html>
<html lang="ru">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">

    {!! SEO::generate() !!}
</head>
<body>

<script>
    var APP_DOMAIN = '{{ env('APP_DOMAIN') }}';
    var CART = {
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
                        <b class="d-block">Sim-Store</b>
                        <span>Описание что мы предлагаем</span>
                    </div>
                    <select-city :current='{!! json_encode($region) !!}'></select-city>
                </div>
                <div class="header-mobile-menu d-flex align-items-center d-sm-none">
                    <a href="/cart" class="header-mobile__basket"><span class="basket-mobile-count">{{ cartCount() }}</span>Корзина</a>
                    <a href="/profile" class="header-mobile__profile">Профиль</a>
                    <a href="#" class="header-mobile__menu" v-on:click="openMenu">Меню</a>
                </div>
                <div class="flex-nowrap d-none d-sm-flex">
                    <div class="header-phone d-inline-flex align-items-center">
                    <span>
                        8 800 123 45 67
                    </span>
                    </div>
                    <div class="header-divider d-inline-flex align-items-center">
                        <span></span>
                    </div>
                    <div class="header-lk d-inline-flex align-items-center">
                        <a href="/profile"><span class="d-md-none d-sm-none d-lg-inline-block">Войти в аккаунт</span></a>
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
                            <a href="/">Красивые номера</a>
                            <span class="open-dropdown"></span>
                            <ul class="dropdown d-none">
                                <li><a href="/numbers/gold">Золотые</a></li>
                                <li><a href="/numbers/platinum">Платиновые</a></li>
                                <li><a href="/numbers/promo">Акционные</a></li>
                            </ul>
                        </li>
                        <li class="has-dropdown">
                            <a href="/tarify">Тарифы</a>
                            <span class="open-dropdown"></span>
                            <ul class="dropdown d-none">
                                <li><a href="/tarifs/unlimited">Безлимитные</a></li>
                                <li><a href="/tarifs/unlimited-ru">Безлимитные Россия</a></li>
                                <li><a href="/tarifs/internet">Для Интернета</a></li>
                            </ul>
                        </li>
                        <li><a href="/informatsiya">Информация</a></li>
                        <li><a href="/aktsii">Акции</a></li>
                    </ul>
                </div>
                <div class="nav-basket d-none d-sm-inline-flex align-items-center">
                    <a href="/cart">Корзина: <span class="basket-count">{{ cartCount() }}</span></a>
                </div>
            </div>
            <div class="d-block d-md-none">
                <div class="mobile-info text-center">
                    <b>8 800 123 45 67</b><br>
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
                    <span>Минуты и гигабайты не сгорают</span>
                </div>
                <div class="advantages-item ai-4">
                    <span>Более 500 000 номеров на выбор</span>
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
                        <b>SIM-STORE</b><br>
                        <span>Описание что мы предлагаем</span>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 d-flex align-items-center">
                    <div class="footer-socials text-center">
                        <a href="#" class="fb">Мы на Facebook</a>
                        <a href="#" class="tw">Мы в Twitter</a>
                        <a href="#" class="vk">Мы во Вконтакте</a>
                        <a href="#" class="ok">Мы в Одноклассниках</a>
                        <a href="#" class="yt">Мы на YouTube</a>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 d-flex align-items-center justify-content-center justify-content-sm-end">
                    <div class="text-center text-sm-right footer-right">
                        <b>8 800 123 45 67</b><br>
                        Отдел продаж: с 9:00 до 19:00<br>
                        support@sim-store.ru<br>
                        <a href="/policy">Политика конфиденциальности</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <modals-container/>
</div>

<link rel="stylesheet" href="{{ asset('/css/vendor.css') }}">
<script src="{{ asset('/js/app.js?id=27dbec76a89168c47e9d') }}" async></script>
</body>
</html>