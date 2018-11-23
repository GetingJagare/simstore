@extends('frontend.layout')
@section('content')
    <main class="home-page page">
        <div class="container">
            <h1 class="big-h1">{{ $page->alter_name ?: $page->name }}</h1>
            <tariffs :cart="cart" {!! isset($params['for_internet']) ? ':for_internet="true"' : '' !!} {!! isset($params['unlimited']) ? ':unlimited="true"' : '' !!} {!! isset($params['unlimited_ru']) ? ':unlimited_ru="true"' : '' !!}></tariffs>
        </div>
        <section class="slider">
            <div class="slider-wrap owl-slider-home owl-carousel">
                <div class="slider-item container">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <div class="d-inline-flex order-2 order-sm-1 align-content-end">
                            <img src="/images/slider-item1.png" class="slider-item__image" alt="Бесплатная доставка при покупке в интернет-магазине SIM-STORE">
                        </div>
                        <div class="d-inline-flex order-1 order-sm-2 align-items-center slider__text-col">
                            <div class="slider-item__text">
                                <b>Условия подключения</b><br>
                                <span>Что бы пользоваться любым представленным номером:</span><br/><br/>
                                1. Подключите подходящий тариф <br />
                                2. Внесите единоразовый платеж 1500 - 3000 руб. в зависимости от номера.
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
                                <div class="advantages-block__item-icon personal"></div>
                                <b>Персональное обслуживание</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div class="advantages-block__item-icon sale"></div>
                                <b>Продажа красивых телефонных номеров!!</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div class="advantages-block__item-icon connect"></div>
                                <b>Подключения к корпоративным тарифам.</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div class="advantages-block__item-icon unlimited"></div>
                                <b>Безлимитная связь.</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="advantages-block__item col-md-6">
                                <div class="advantages-block__item-icon choose"></div>
                                <b>Подбор номера.</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                            <div class="advantages-block__item col-md-6">
                                <div class="advantages-block__item-icon delivery"></div>
                                <b>Доставка курьером.</b>{{--<br>
                                <span>Подробное описание данное плюса очень подробное</span>--}}
                            </div>
                        </div>

                        <div class="advantages-block__button">
                            <a href="#" class="button" v-on:click.prevent="openCallbackPopup('Подключить', 'lead_upper_popup_form')">Подключить</a>
                        </div>

                    </div>
                    <div class="d-none d-lg-block">
                        <img src="/images/advantages-2.png" class="advantages-block__image" alt="Плюсы индивидуального обслуживания">
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.parts.delivery-payment', ['desc' => $page->small_desc])

        @include('frontend.parts.question')

    </main>


@endsection