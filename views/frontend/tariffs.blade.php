@extends('frontend.layout')
@section('content')
    <main class="home-page">
        <tariffs :cart="cart" {!! isset($params['for_internet']) ? ':for_internet="true"' : '' !!} {!! isset($params['unlimited']) ? ':unlimited="true"' : '' !!} {!! isset($params['unlimited_ru']) ? ':unlimited_ru="true"' : '' !!}></tariffs>
        <section class="slider">
            <div class="slider-wrap owl-slider-home owl-carousel">
                <div class="slider-item container">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <div class="d-inline-flex order-2 order-sm-1 align-content-end">
                            <img src="/images/slider-item1.png" class="slider-item__image" alt="Бесплатная доставка при покупке в интернет-магазине SIM-STORE">
                        </div>
                        <div class="d-inline-flex order-1 order-sm-2 align-items-center">
                            <div class="slider-item__text">
                                <b>Бесплатная доставка</b><br>
                                <span>при покупке в интернет-магазине SIM-STORE</span>
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
                        <div class="big-h1">Плюсы индивидуального обслуживания</div>

                        <div class="row">
                            <div class="advantages-block__item col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div><img src="/images/advantage-icon.png" alt="Короткий заголовок плюса"></div>
                                <b>Короткий заголовок плюса</b><br>
                                <span>Подробное описание данное плюса очень подробное</span>
                            </div>
                        </div>

                        <div class="advantages-block__button">
                            <a href="#" class="button" v-on:click.prevent="openCallbackPopup('Подключить')">Подключить</a>
                        </div>

                    </div>
                    <div class="d-none d-lg-block">
                        <img src="/images/advantages-2.png" class="advantages-block__image" alt="Плюсы индивидуального обслуживания">
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.parts.delivery-payment')

        @include('frontend.parts.question')

    </main>


@endsection