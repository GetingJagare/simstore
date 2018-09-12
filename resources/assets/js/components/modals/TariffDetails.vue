<template>
    <div>
        <div class="modal-close" v-on:click="$emit('close')"></div>
        <div class="tariffs-result" style="margin: 0">
            <div class="tariffs-item" style="margin: 0; box-shadow: none">
                <div class="tariffs-item__header d-flex flex-nowrap align-items-center">
                    <div class="tariffs-item__operator">
                        <div class="tariffs-icon beeline"></div>
                    </div>
                    <div class="tariffs-item__name">{{ tariff.name }}</div>
                </div>
                <div style="margin: 20px 0 0" v-if="tariff.description">{{ tariff.description }}</div>
                <div class="tariffs-item__content">
                    <div class="tariffs-item__line time">{{ tariff.minutes }} мин. по России</div>
                    <div class="tariffs-item__line sms">{{ tariff.sms }} SMS по России</div>
                    <div class="tariffs-item__line net">{{ tariff.traffic }} ГБ интернета</div>
                    <div class="tariffs-item__line vk" v-if="tariff.socials">{{ tariff.socials }}</div>
                    <div class="tariffs-item__line wp" v-if="tariff.messengers">{{ tariff.messengers }}</div>
                    <div class="tariffs-item__line yt" v-if="tariff.youtube">{{ tariff.youtube }} YouTube</div>
                </div>
                <div class="tariffs-item__price">
                    <span><b>{{ tariff.final_price }} ₽</b> / месяц</span><br>
                    из любой точки России
                </div>
                <div class="tariffs-item__buttons d-flex flex-nowrap">
                    <a href="#" class="button tariffs-item__buttons-more" v-on:click.prevent="addToCart(tariff)">Подключить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InfoPopup from './InfoPopup.vue';
    export default {

        props: ['tariff'],

        mounted() {
            console.log('Component mounted.')
        },

        methods: {



            addToCart(item) {

                this.$http.post('/cart/add-tariff', {id: item.id}).then(response => {

                    //this.numbers = response.body.numbers;

                    this.$modal.show(InfoPopup, {
                        title: 'Добавлено в корзину',
                        text: 'Выбранный тариф добавлен в корзину. Вы можете перейти к оформлению заказа или продолжить покупки.',
                        link: {name: 'Перейти в корзину', href: '/cart'}
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    document.getElementsByClassName('basket-count')[0].innerHTML = response.body.count;
                    document.getElementsByClassName('basket-mobile-count')[0].innerHTML =  response.body.count;

                    addToDataLayer({'event':'lead_set_tariff_form'});

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            }

        }
    }
</script>
