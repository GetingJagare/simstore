<template>
    <form class="profile-login__form" method="get">
        <div style="color: red;margin: 0 0 15px;" v-if="wrongNumber">Номер или пароль не верен</div>
        <div>
            <input v-model="form.phone" class="diler_input_login" id="appleId" type="text" placeholder="Логин">
        </div>
        <div>
            <input v-model="form.password" id="pwd" type="password" class="diler_input_pass" placeholder="Пароль">
        </div>
        <button class="button" type="submit" @click.prevent="login">Авторизация</button>
    </form>
</template>

<script>
    export default {
        name: "LoginForm",
        data() {
            return {
                wrongNumber: false,
                form: {
                    phone: '',
                    password: '',
                }
            }
        },

        methods: {
            login() {
                this.$http.post('/login?mobile=false&phone=' + this.form.phone +
                    '&password=' + this.form.password, this.form)
                    .then(response => {
                        if (response.body.success) {
                            this.wrongNumber = false;
                            window.location.href = response.body.redirect_url;
                        } else {
                            this.wrongNumber = true;
                        }
                    }, response => {
                        this.wrongNumber = true;
                    })
            }
        }
    }
</script>

<style scoped>

</style>