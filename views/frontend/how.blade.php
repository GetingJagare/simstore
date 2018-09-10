@extends('frontend.layout')
@section('content')
    <main>
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
                            <a href="#" class="button">Подключить</a>
                        </div>

                    </div>
                    <div class="d-none d-lg-block">
                        <img src="/images/advantages-2.png" class="advantages-block__image" alt="Плюсы индивидуального обслуживания">
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.parts.delivery-payment')

        <div class="info-blocks">
            <div class="container">
                <div class="row">

                    <div class="col-md-4">
                        <div class="big-h1">О компании</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b>работаем <span>с 2006 года</span></b></div>
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">Гарантии</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b><span>2 года</span> гарантии</b></div>
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">Возврат товара</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b>позвоните на <span>8 800 700 36 37</span></b></div>
                    </div>
                </div>
            </div>
        </div>

        @include('frontend.parts.question')


    </main>
@endsection