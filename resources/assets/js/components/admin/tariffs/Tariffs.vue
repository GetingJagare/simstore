<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Тарифы</h3></div>
            <div class="col-md-6 text-right">
                <b-btn variant="danger" :disabled="!selectedTariffs.length" @click="deleteTariffs">Удалить</b-btn>
                <b-btn v-b-toggle.collapse1 variant="primary">Добавить</b-btn>
            </div>
        </div>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" @change="selectAll" v-model="allSelected"/>
                </th>
                <th>Название</th>
                <th>Минут</th>
                <th>СМС</th>
                <th>Интернет</th>
                <th>Цена</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(tariff, index) in tariffs">
                <td>
                    <input type="checkbox" :value="{id: tariff.id, index: index}" v-model="selectedTariffs"/>
                </td>
                <td>{{ tariff.name }}</td>
                <td>{{ tariff.minutes }}</td>
                <td>{{ tariff.sms }}</td>
                <td>{{ tariff.traffic }} Гб</td>
                <td>{{ tariff.price }}</td>
                <td><router-link :to="'/tariffs/' + tariff.id">Изменить</router-link></td>
            </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <b-collapse id="collapse1" class="mt-4">
                <b-card title="Добавить тариф">

                    <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

                    <b-form @submit="onSubmit" @reset="onReset">

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

                        <b-form-group label="Добавить цены номеров" class="mt-5">
                            <b-form-checkbox value="1" unchecked-value="0" v-model="addNumbersPrices"></b-form-checkbox>

                            <b-form-group class="mt-1" v-if="addNumbersPrices == 1">
                                <b-form-input v-model="tariff.number_prices[0]" placeholder="От" class="mb-2"></b-form-input>
                                <b-form-input v-model="tariff.number_prices[1]" placeholder="До"></b-form-input>
                            </b-form-group>
                        </b-form-group>

                        <b-button type="submit" variant="primary">Добавить</b-button>
                        <b-button type="reset">Очистить</b-button>
                    </b-form>
                </b-card>
            </b-collapse>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {

            this.getTariffs();

        },

        data() {

            return {

                allSelected: false,

                selectedTariffs: [],

                addNumbersPrices: 0,

                mess: {
                    success: ''
                },

                tariff: {
                    name: '',
                    minutes: '',
                    sms: '',
                    traffic: '',
                    price: 0,
                    socials: '',
                    messengers: '',
                    youtube: '',
                    description: '',
                    promo: '0',
                    no_limit: '0',
                    no_limit_ru: '0',
                    for_internet: '0',
                    sale: '0',
                    number_prices: []
                },

                tariffs: []

            }

        },

        methods: {

            getTariffs() {


                this.$http.get('/admin/tariffs').then(response => {

                    this.tariffs = response.body.tariffs;

                }, response => {
                    // error callback
                });


            },

            onSubmit(evt) {

                evt.preventDefault();

                this.$http.post('/admin/tariffs/add', this.tariff).then(response => {

                    this.mess.success = response.body.message;
                    this.resetForm();

                }, response => {
                    // error callback
                });

            },

            resetForm () {

                var _that = this;
                this.tariff.name = '';
                this.tariff.minutes = '';
                this.tariff.sms = '';
                this.tariff.traffic = '';
                this.tariff.price = 0;
                this.tariff.socials = '';
                this.tariff.messengers = '';
                this.tariff.youtube = '';
                this.tariff.description = '';
                this.tariff.promo = '0';
                this.tariff.no_limit = '0';
                this.tariff.no_limit_ru = '0';
                this.tariff.for_internet = '0';

                setTimeout(function () {
                    _that.mess.success = '';
                }, 4000);

            },

            onReset (evt) {
                evt.preventDefault();
                this.resetForm();
            },

            deleteTariffs() {
                this.$http.post('/admin/tariffs/delete', {tariffs: this.selectedTariffs})
                    .then(response => {
                        if (response.body.success) {

                            var rows = this.$el.querySelector('.table').querySelector('tbody').querySelectorAll('tr');
                            response.body.tariffs.forEach(tariff => {
                                rows[parseInt(tariff.index)].remove();
                            });
                        }
                    });
            },

            selectAll(e) {
                if (e.srcElement.checked) {
                    var _selected_tariffs = [];
                    this.tariffs.forEach((tariff, index) => {
                        _selected_tariffs.push({id: tariff.id, index: index});
                    });
                    this.selectedTariffs = _selected_tariffs;
                } else {
                    this.selectedTariffs = [];
                }
            }

        },

        watch: {
            selectedTariffs: function (value) {
                this.allSelected = value.length === this.tariffs.length;
            }

        }
    }
</script>
