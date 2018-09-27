@extends('frontend.layout')
@section('content')
    <main class="home-page">
        <div class="container">
            <div class="main-wrap d-flex flex-wrap flex-lg-nowrap justify-content-between">
                <div class="main-content">
                    <numbers :cart="cart" :price_max="{{ maxNumbersPrice() }}" {!! isset($params['promo']) ? ':promo="true"' : '' !!} {!! isset($params['price_range']) ? ':price_range="' . json_encode($params['price_range']) .'"' : '' !!}></numbers>
                </div>
                <div class="main-sidebar d-sm-flex d-md-flex d-lg-block justify-content-between">

                    <promo-numbers :cart="cart"></promo-numbers>

                    <div class="sidebar-block">
                        <div class="sidebar-block__header">
                            <div class="big-h1">Успей купить!</div>
                            <b>тариф дня со <span>скидкой</span></b>
                        </div>
                        <promo-tariffs :cart="cart"></promo-tariffs>
                    </div>
                </div>
            </div>
        </div>

        <section class="slider home-slider">
            <div class="slider-wrap owl-slider-home owl-carousel">
                <div class="slider-item container">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <div class="d-inline-flex order-2 order-sm-1 align-content-end">
                            <img src="/images/slider-item1.png" class="slider-item__image" alt="Бесплатная доставка при покупке в интернет-магазине SIM-STORE">
                        </div>
                        <div class="d-inline-flex order-1 order-sm-2 align-items-center home-slider__text-col">
                            <div class="slider-item__text">
                                <b>Честно и прозрачно</b><br>
                                <span>Главное преимущество аренды номера в Sim-Store: НЕТ ДОПОЛНИТЕЛЬНЫХ ПЛАТЕЖЕЙ,
                                    Вы платите только абонентскую плату по выбранному тарифу.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="advantages-block">
            <div class="container">
                <div class="d-flex flex-nowrap justify-content-between">
                    <div class="advantages-block__info">
                        <div class="big-h1">Какие бывают дополнительные платежи:</div>

                        <div>
                            <table class="advantages-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="advantages-tabl__th-param">В других магазинах:</th>
                                    <th class="advantages-tabl__th-param">У нас:</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="advantages-tabl__td-name">Обслуживание</td>
                                    <td class="advantages-tabl__td-n1">500 руб./мес.</td>
                                    <td class="advantages-tabl__td-n2">Бесплатная поддержка 24/7</td>
                                </tr>
                                <tr>
                                    <td class="advantages-tabl__td-name">Доставка</td>
                                    <td class="advantages-tabl__td-n1">от 300 до 2000 руб.</td>
                                    <td class="advantages-tabl__td-n2">Бесплатная по РФ</td>
                                </tr>
                                <tr>
                                    <td class="advantages-tabl__td-name">Аренда</td>
                                    <td class="advantages-tabl__td-n1">100 руб./мес.</td>
                                    <td class="advantages-tabl__td-n2">Включена в стоимость тарифа</td>
                                </tr>
                                <tr>
                                    <td class="advantages-tabl__td-name small">Восстановление<br/>сим карты</td>
                                    <td class="advantages-tabl__td-n1">500 руб.</td>
                                    <td class="advantages-tabl__td-n2">Бесплатно</td>
                                </tr>
                                <tr>
                                    <td class="advantages-tabl__td-name small">СМС информирование</td>
                                    <td class="advantages-tabl__td-n1">от 20 до 100 руб./мес.</td>
                                    <td class="advantages-tabl__td-n2">Бесплатно</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!--<div class="row">
                            <div class="advantages-block__item col-sm-6 col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-sm-6 col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-sm-6 col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-sm-6 col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                        </div>-->

                        <div class="advantages-block__button">
                            <a href="#" class="button" v-on:click.prevent="openCallbackPopup('Подключить')">Подключить</a>
                        </div>

                    </div>
                    <div class="d-none d-lg-block">
                        <img src="/images/advantages.png" class="advantages-block__image" alt="Плюсы индивидуального обслуживания">
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.parts.delivery-payment')

        @include('frontend.parts.question')
    </main>
@endsection