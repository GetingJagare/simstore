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
                        <div class="big-h1">Как списывается абонентская плата</div>
                        <p>
                            Возможно несколько вариантов:<br />
                            А) Абонентская плата списывается ежемесячно(1-го числа) целиком за месяц вперед;<br />
                            Б) Абонентская плата списывается ежедневно, если при активации вы выбрали тариф с посуточным списанием
                        </p>
                        {{--<div class="info-blocks__additional"><b>работаем <span>с 2006 года</span></b></div>--}}
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">На кого будет оформлен номер</div>
                        <p>
                            Все предлагаемые номера оформлены на юридическое лицо. Абонент, приобретая номер,
                            берет его в бессрочную аренду у нашей компании. Никто не вправе отобрать у Вас номер,
                            если вы не совершаете с него мошеннических действий (подтверждается официальным письмом сотового оператора),
                            или Вы не пользуетесь номером более 30 дней.
                            При активации тарифа оператор спросит у Вас данные на кого оформить номер или кодовое слово.
                            Это необходимо при последующих обращениях в службу поддержки, для Вашей идентификации.
                        </p>
                        {{--<div class="info-blocks__additional"><b><span>2 года</span> гарантии</b></div>--}}
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">Как подключить/отключить дополнительные услуги</div>
                        <p>
                            Все действия по номерам осуществляются через абонентскую службу по тел. 8-800-775-01-66.
                            Наши операторы проконсультируют по всем интересующих Вас вопросам.
                        </p>
                        {{--<div class="info-blocks__additional"><b>позвоните на <span>8 800 700 36 37</span></b></div>--}}
                    </div>
                </div>
            </div>
        </div>

        @include('frontend.parts.question')


    </main>
@endsection