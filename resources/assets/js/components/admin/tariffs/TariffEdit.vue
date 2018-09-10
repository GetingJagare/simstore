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
                tariff: []
            }

        },

        methods: {

            getTariff() {

                this.$http.get('/admin/tariffs/get?id=' + this.$route.params.id).then(response => {

                    this.tariff = response.body.tariff;

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
