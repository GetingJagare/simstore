<template>
    <div>
        <div class="order-h1 big-h1">Ваши контакты</div>
        <div class="order-form">
            <div class="d-flex">
                <div class="order-form__input first">
                    <div class="order-form__label">Ваше имя</div>
                    <input type="text" placeholder="Иван">
                </div>
                <div class="order-form__input">
                    <div class="order-form__label">Ваш номер телефона</div>
                    <input type="text" placeholder="+7 (999) 999-99-99">
                </div>
            </div>
            <div class="order-form__input last">
                <div class="order-form__label">Адресс доставки</div>
                <input type="text" placeholder="г. Москва, ул. Тверская, д. 30, кв. 5">
            </div>
        </div>
        <div class="d-flex order-form__bottom">
            <div class="order-form__checkbox">
                <label class="checkbox d-flex">
                    <input type="checkbox" />
                    <span class="checkbox__text">Нажимая кнопку, Вы принимаете <a href="/politika-konfidentsialnosti">условия</a></span>
                </label>
            </div>
            <div>
                <button class="button" :disabled="disabled">Оформить заказ</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        mounted() {

        },

        data() {

            return {
                disabled: true
            }

        }
    }
</script>