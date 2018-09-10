<template>
    <div>
        <div class="row">
            <div class="col-md-12"><h3>Настройки</h3></div>
        </div>

        <div class="mt-4">

            <b-form @submit="" @reset="" v-if="show">

                <b-card>
                    <b-form-group label="Скидка на номера в каталоге (в процентах):">
                        <b-form-input type="number" v-model="settings.catalog_numbers_discount.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>
                </b-card>

                <b-card class="mt-3">
                    <b-form-group label="Скидка на номера для распродажи (в процентах):">
                        <b-form-input type="number" v-model="settings.catalog_numbers_sale.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>
                </b-card>

                <b-card class="mt-3">
                    <b-form-group label="Скидка на тарифы для распродажи:">
                        <b-form-input type="number" v-model="settings.tariffs_discount.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Тип скидки:">
                        <b-form-checkbox v-model="settings.tariffs_discount_type.setting_value" value="rubles" unchecked-value="0">Рубли</b-form-checkbox>
                        <b-form-checkbox v-model="settings.tariffs_discount_type.setting_value" value="percent" unchecked-value="0">Процент</b-form-checkbox>
                    </b-form-group>
                </b-card>


                <b-card class="mt-3">
                    <b-form-group label="Наценка:">
                        <b-form-input type="number" v-model="settings.markup.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Тип наценки:">
                        <b-form-checkbox v-model="settings.markup_type.setting_value" value="rubles" unchecked-value="0">Рубли</b-form-checkbox>
                        <b-form-checkbox v-model="settings.markup_type.setting_value" value="percent" unchecked-value="0">Процент</b-form-checkbox>
                    </b-form-group>
                </b-card>

                <!--<b-card class="mt-3">
                    <b-form-group label="Скидка на акционные номера:">
                        <b-form-input type="number" v-model="settings.numbers_discount.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Тип скидки:">
                        <b-form-checkbox v-model="settings.numbers_discount_type.setting_value" value="rubles" unchecked-value="0">Рубли</b-form-checkbox>
                        <b-form-checkbox v-model="settings.numbers_discount_type.setting_value" value="percent" unchecked-value="0">Процент</b-form-checkbox>
                    </b-form-group>
                </b-card>

                <b-card class="mt-3">
                    <b-form-group label="Скидка на акционные тарифы:">
                        <b-form-input type="number" v-model="settings.tariffs_discount.setting_value" required autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Тип скидки:">
                        <b-form-checkbox v-model="settings.tariffs_discount_type.setting_value" value="rubles" unchecked-value="0">Рубли</b-form-checkbox>
                        <b-form-checkbox v-model="settings.tariffs_discount_type.setting_value" value="percent" unchecked-value="0">Процент</b-form-checkbox>
                    </b-form-group>
                </b-card>-->

                <b-button class="mt-3" type="submit" variant="primary" v-on:click.prevent="saveSettings">Обновить настройки</b-button>
            </b-form>

        </div>

    </div>
</template>

<script>
    export default {
        mounted() {

            this.getSettings();

        },

        data() {

            return {

                settings: [],
                show: false

            }

        },

        methods: {

            getSettings() {

                this.$http.get('/admin/settings').then(response => {

                    this.settings = response.body.settings;
                    this.show = true;
                }, response => {

                });

            },

            saveSettings() {

                this.$http.post('/admin/settings/save', {settings: this.settings}).then(response => {



                }, response => {

                });

            }

        }
    }
</script>
