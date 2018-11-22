<template>
    <form class="profile-login__form" method="get" accept-charset="windows-1251">
        <div style="color: green;margin: 0 0 15px;" v-if="success && message.length">{{ message }}</div>
        <div style="color: red;margin: 0 0 15px;" v-if="!success && message.length">{{ message }}</div>
        <div>
            <input name="X_username" class="diler_input_login" id="appleId" type="text"
                   placeholder="Номер телефона (10 цифр)">
        </div>
        <button class="button" type="submit" @click.prevent="lostpass">Отправить</button>
    </form>
</template>

<script>
    export default {
        name: "LostPassForm",
        data() {
            return {
                message: '',
                success: false,
                form: {
                    username: ''
                }
            }
        },

        methods: {
            lostpass() {
                this.$http.post('/lostpass', this.form).then(response => {
                    this.success = response.body.success;
                    this.message = response.body.message;
                })
            }
        }
    }
</script>

<style scoped>

</style>