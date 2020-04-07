<template>
    <div>
        <b-card title="Изменить тариф">
            <b-form @submit="editTariff">

                <b-form-group label="Название тарифа:">
                    <b-form-input type="text" v-model="tariff.name" required placeholder="Деловой 1000" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Количество минут:">
                    <b-form-input type="text" v-model="tariff.minutes" required placeholder="1500" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Количество СМС:">
                    <b-form-input type="text" v-model="tariff.sms" required placeholder="1500" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Объем трафика (ГБ):">
                    <b-form-input type="text" v-model="tariff.traffic" placeholder="20" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Абонентская плата (руб):">
                    <b-form-input type="number" v-model="tariff.price" required placeholder="1000" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Соц. сети:">
                    <b-form-input type="text" v-model="tariff.socials" placeholder="VK, OK, FB, Instagram" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Мессенджеры:">
                    <b-form-input type="text" v-model="tariff.messengers" placeholder="What’sApp, Viber, eMotion" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="YouTube:">
                    <b-form-input type="text" v-model="tariff.youtube" placeholder="20 ГБ" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Описание:">
                    <b-form-textarea autocomplete="off" v-model="tariff.description" placeholder="Информация" :rows="3" :max-rows="6"></b-form-textarea>
                </b-form-group>

                <b-form-group label="Параметры:">
                    <b-form-checkbox value="1" unchecked-value="0" v-model="tariff.promo">Акция</b-form-checkbox>
                    <b-form-checkbox value="1" unchecked-value="0" v-model="tariff.no_limit">Безлимитные</b-form-checkbox>
                    <b-form-checkbox value="1" unchecked-value="0" v-model="tariff.no_limit_ru">Безлимит — Россия</b-form-checkbox>
                    <b-form-checkbox value="1" unchecked-value="0" v-model="tariff.for_internet">Для интернета</b-form-checkbox>
                    <b-form-checkbox value="1" unchecked-value="0" v-model="tariff.sale">Тариф дня</b-form-checkbox>
                </b-form-group>

                <b-form-group label="Оператор:">
                    <b-form-radio value="3" name="provider" @change="setProviderId" v-bind:checked="tariff.provider_id === 3">Билайн</b-form-radio>
                    <b-form-radio value="4" name="provider" @change="setProviderId" v-bind:checked="tariff.provider_id === 4">Мегафон</b-form-radio>
                </b-form-group>

                <b-form-group label="Добавить цены номеров" class="mt-5">
                    <b-form-checkbox value="1" unchecked-value="0" v-model="addNumbersPrices"></b-form-checkbox>

                    <b-form-group class="mt-1" v-if="addNumbersPrices == 1">
                        <b-form-input v-model="tariff.number_prices[0]" placeholder="От" class="mb-2"></b-form-input>
                        <b-form-input v-model="tariff.number_prices[1]" placeholder="До"></b-form-input>
                    </b-form-group>
                </b-form-group>

                <b-button type="submit" variant="primary">Обновить</b-button>
            </b-form>
        </b-card>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getTariff();
        },

        data() {

            return {
                addNumbersPrices: 0,
                tariff: {
                    id: 0,
                    name: '',
                    minutes: '',
                    sms: '',
                    traffic: '',
                    price: 0,
                    socials: '',
                    messengers: '',
                    youtube: '',
                    description: '',
                    promo: false,
                    no_limit: false,
                    no_limit_ru: false,
                    for_internet: false,
                    provider_id: null,
                    sale: false,
                    number_prices: []
                }
            }

        },

        methods: {

            setProviderId (checked) {

                this.tariff.provider_id = checked;

            },

            getTariff() {

                this.$http.get('/admin/tariffs/get?id=' + this.$route.params.id).then(response => {

                    this.tariff.id = response.body.tariff.id;
                    this.tariff.name = response.body.tariff.name;
                    this.tariff.minutes = response.body.tariff.minutes;
                    this.tariff.sms = response.body.tariff.sms;
                    this.tariff.traffic = response.body.tariff.traffic;
                    this.tariff.price = response.body.tariff.price;
                    this.tariff.socials = response.body.tariff.socials;
                    this.tariff.messengers = response.body.tariff.messengers;
                    this.tariff.youtube = response.body.tariff.youtube;
                    this.tariff.description = response.body.tariff.description;
                    this.tariff.promo = response.body.tariff.promo;
                    this.tariff.no_limit = response.body.tariff.no_limit;
                    this.tariff.no_limit_ru = response.body.tariff.no_limit_ru;
                    this.tariff.for_internet = response.body.tariff.for_internet;
                    this.tariff.sale = response.body.tariff.sale;
                    this.tariff.number_prices = response.body.tariff.number_prices ? JSON.parse(response.body.tariff.number_prices) : [];
                    this.tariff.provider_id = response.body.tariff.provider_id;

                    this.addNumbersPrices = this.tariff.number_prices.length > 0 ? 1 : 0;

                    const providerRadio = this.$el.querySelector('[name="provider"][value="' + this.tariff.provider_id + '"]');

                    if (providerRadio) {

                        providerRadio.click();

                    }

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            },

            editTariff(evt) {

                evt.preventDefault();

                this.$http.post('/admin/tariffs/edit', this.tariff).then(response => {

                    alert(response.body.message);

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            }

        }
    }
</script>
