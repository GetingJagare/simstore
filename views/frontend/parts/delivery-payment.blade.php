<section class="delivery-payment">
    <div class="container">
        {{--<div class="delivery-payment__lines"></div>--}}
        <div class="row">
            <div class="order-2 order-sm-1 order-xl-1 order-xs-1 col-xl-7">
                <div class="delivery-payment__row d-flex flex-wrap flex-sm-nowrap">
                    <div class="delivery-payment__item item-1 technology">
                        1. Выберите номер<br />
                        2. Выберите тариф
                    </div>
                    {{--<div class="delivery-payment__item item-2 discuss-issue">
                        Получите ответы о тарифах, сроках и условиях доставки
                    </div>--}}
                    <div class="delivery-payment__item delivery-payment__item_right-line item-2 check-form">
                        3. Оставьте заявку
                    </div>
                </div>
                <div class="delivery-payment__row row-2 d-flex flex-wrap flex-sm-nowrap">
                   {{-- <div class="delivery-payment__item item-4 check-form">
                        Оформите заявку на вызов курьера по телефону или на сайте
                    </div>--}}
                    <div class="delivery-payment__item delivery-payment__item_left-line item-3 delivery">
                        5. Бесплатная курьерская доставка, в удобное вам время и прямо до вашей двери
                    </div>
                    <div class="delivery-payment__item item-4 text-line-form">
                        4. В течение 5 минут с вами свяжется менеджер для уточнения деталей заказа
                    </div>
                </div>
                <div class="delivery-payment__row row-3 d-flex flex-wrap flex-sm-nowrap">
                    <div class="delivery-payment__item delivery-payment__item_no-line item-5 airplane">
                        6. Пользуйтесь вашим номером с удовольствием!
                    </div>
                    {{--<div class="delivery-payment__item item-5 world-wide-web">
                        Гарантпост обрабатывает отправление в Сортировочном центре
                    </div>
                    <div class="delivery-payment__item item-7 delivery">
                        Курьер доставляет отправление получателю
                    </div>--}}
                </div>
            </div>
            <div class="delivery-payment__right-col order-1 order-sm-2 order-xl-2 col-xl-5">
                <div class="delivery-payment__right">
                    <div class="big-h1">Условия доставки и оплаты</div>
                    <ol>
                        <li>Выберите номер</li>
                        <li>Выберите тариф</li>
                        <li>Оставьте заявку</li>
                        <li>В течение 5 минут с вами свяжется менеджер для уточнения деталей заказа</li>
                        <li>Бесплатная курьерская доставка, в удобное вам время и прямо до вашей двери</li>
                        <li>Пользуйтесь вашим номером с удовольствием!</li>
                    </ol>

                    Бесплатная доставка по РФ.
                    Мы осуществляем доставку сим карт компанией СДЭК.
                    Вам, просто, нужно выбрать удобное время и встретить курьера.
                    Доставка бесплатна по всей территории России.
                </div>
            </div>
        </div>

        <div class="d-flex flex-nowrap">
            <div class="delivery-payment__left">

            </div>

        </div>
    </div>

    @include('frontend.parts.small-desc', ['desc' => $desc])
</section>