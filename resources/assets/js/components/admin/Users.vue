<template>
    <div>

        <div class="row">
            <div class="col-md-6"><h3>Пользователи</h3></div>
            <div class="col-md-6 text-right"><b-btn v-b-toggle.collapse1 variant="primary">Добавить</b-btn></div>
        </div>

        <b-table class="mt-4" hover :items="users" :fields="table_fields" :outlined="true" ></b-table>

        <div class="mt-4">
            <b-collapse id="collapse1" class="mt-4">
                <b-card title="Добавить пользователя">

                    <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

                    <b-form @submit="onSubmit" @reset="onReset">
                        <b-form-group label="Имя:">
                            <b-form-input type="text" v-model="form.name" required placeholder="Имя пользователя"></b-form-input>
                        </b-form-group>

                        <b-form-group label="E-mail:" description="Используется для входа в систему.">
                            <b-form-input type="email" v-model="form.email" required placeholder="Укажите почту"></b-form-input>
                        </b-form-group>

                        <b-form-group label="Пароль:">
                            <b-form-input type="password" v-model="form.password" required placeholder="Придумайте пароль"></b-form-input>
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
            //console.log('Component mounted.')
            this.getUsers();

        },
        data () {
            return {
                form: {
                    email: '',
                    name: '',
                    password: '',
                },
                mess: {
                    success: ''
                },
                users: [],
                table_fields: [
                    {
                        key: 'name',
                        label: 'Имя'
                    },
                    {
                        key: 'email',
                        label: 'E-mail'
                    }
                ]
            }
        },
        methods: {

            getUsers() {

                this.$http.get('/admin/users').then(response => {

                    this.users = response.body.users;

                }, response => {

                });

            },

            onSubmit (evt) {
                evt.preventDefault();

                this.$http.post('/admin/users/add', this.form).then(response => {

                    this.mess.success = response.body.message;
                    this.resetAddUserForm();

                }, response => {
                    // error callback
                });
            },

            onReset (evt) {
                evt.preventDefault();
                this.resetAddUserForm();
            },

            resetAddUserForm() {

                var _that = this;

                this.form.email = '';
                this.form.name = '';
                this.form.password = '';

                setTimeout(function () {
                    _that.mess.success = '';
                }, 4000);

            }
        }
    }
</script>
