<template>
    <div class="modal__content modal__padding-tb">
        <div class="modal__title">{{ subject }}</div>
        <div class="modal-close" v-on:click="$emit('close')"></div>
        <div class="modal__desc">Оставьте контакты, и мы свяжемся с Вами, при первой возможности!</div>
        <form @submit.prevent="submit">
            <div class="modal__form">
                <div class="modal__form-input">
                    <input type="text" placeholder="Ваше имя" v-model="form.name">
                </div>
                <div class="modal__form-input">
                    <input type="text" placeholder="Ваш номер" v-model="form.phone" v-mask="'+7 (###) ###-##-##'">
                </div>
            </div>
            <div class="modal__form-button">
                <button type="submit">Отправить</button>
            </div>
        </form>
        <div class="modal__policy">
            Отправляя заявку вы соглашаетесь с <a href="/policy">политикой</a> обработки персональных данных
            </div>
    </div>
</template>

<script>
    import InfoPopup from './InfoPopup.vue';
    export default {
        props: ['subject', 'leadName'],

        data() {

            return {
                form: {
                    subject: this.subject,
                    leadName: this.leadName,
                    name: '',
                    phone: ''
                }
            }

        },

        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            submit() {

                if(this.form.phone.length < 18) {

                    this.$modal.show(InfoPopup, {
                        title: 'Ошибка!',
                        text: 'Проверьте корректность введенного номера телефона'
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    return false;
                }

                this.$http.post('/crm', {fields: this.form}).then(response => {

                    this.$modal.show(InfoPopup, {
                        title: 'Спасибо за заявку!',
                        text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей'
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    if (this.leadName) {
                        addToDataLayer({event: this.leadName});
                    }

                }, response => {

                    alert('Произошла ошибка.')

                });


                this.form = {
                    subject: this.subject,
                    name: '',
                    phone: ''
                };

                this.$emit('close');

            }
        }
    }
</script>
