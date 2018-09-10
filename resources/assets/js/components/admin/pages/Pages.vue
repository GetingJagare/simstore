<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Страницы</h3></div>
            <div class="col-md-6 text-right"><b-btn v-b-toggle.collapse1 variant="primary">Добавить</b-btn></div>
        </div>

        <b-table class="mt-4" hover :items="pages" :fields="table_fields" :outlined="true">
            <template slot="slug" slot-scope="data">
                <a :href="'/' + data.value" target="_blank">Перейти</a>
            </template>
            <template slot="id" slot-scope="data">
                <router-link :to="'/pages/' + data.value">Изменить</router-link>
            </template>

        </b-table>

        <div class="mt-4">
            <b-collapse id="collapse1" class="mt-4">
                <b-card title="Добавить страницу">

                    <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

                    <b-form @submit="onSubmit" @reset="onReset">

                        <b-form-group label="Название страницы:" description="Дополнительные поля станут доступны после добавления страницы.">
                            <b-form-input type="text" v-model="form.name" required placeholder="Информация" autocomplete="off"></b-form-input>
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
            this.getPages();
        },

        data () {
            return {
                form: {
                    name: '',
                },
                mess: {
                    success: ''
                },
                pages: [],
                table_fields: [
                    {
                        key: 'name',
                        label: 'Название'
                    },
                    {
                        key: 'slug',
                        label: 'Ссылка'
                    },
                    {
                        key: 'id',
                        label: 'Опции'
                    },
                ]
            }
        },

        methods: {

            getPages() {

                this.$http.get('/admin/pages').then(response => {

                    this.pages = response.body.pages;

                }, response => {

                });

            },

            onReset (evt) {
                evt.preventDefault();
                this.resetForm();
            },

            onSubmit (evt) {

                evt.preventDefault();


                this.$http.post('/admin/pages/add', this.form).then(response => {

                    this.$router.push({ name: 'page_edit', params: { id: response.body.id }});
                    this.resetForm();

                }, response => {
                    // error callback
                });

            },

            resetForm () {

                var _that = this;
                this.form.name = '';

                setTimeout(function () {
                    _that.mess.success = '';
                }, 4000);

            }

        }
    }
</script>
