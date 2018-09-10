<template>
    <div>
        <h4>Авторизация</h4>
        <b-form class="mt-4" @submit="onSubmit">
            <b-form-group>
                <b-form-input type="email" v-model="form.email" required placeholder="E-mail"></b-form-input>
            </b-form-group>

            <b-form-group>
                <b-form-input type="password" v-model="form.password" required placeholder="Пароль"></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary">Войти</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        mounted() {

        },

        data() {
            return {
                form: {
                    email: '',
                    password: ''
                }
            }
        },

        methods: {

            onSubmit(evt) {
                evt.preventDefault();
                this.$http.post('/admin/login', this.form).then(response => {

                    location.replace('/admin');

                }, response => {

                    alert(response.body.message);

                });

            }

        }
    }
</script>
