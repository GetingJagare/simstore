<template>
    <div>
        <div class="modal-close" v-on:click="$emit('close')"></div>
        <div class="modal__content modal__padding-tb">
            <div class="modal__title">Номер под заказ</div>
            <div class="modal__desc">Оставьте контакты, и мы подберем для Вас желаемый номер!</div>
            <form @submit.prevent="submitOrderPopup">
                <div class="modal__form">
                    <div class="modal__form-input">
                        <input type="text" placeholder="Желаемый номер телефона" v-model="number_order.number">
                    </div>
                    <div class="modal__form-input">
                        <input type="text" placeholder="Ваше имя" v-model="number_order.name">
                    </div>
                    <div class="modal__form-input">
                        <input type="text" placeholder="Ваш номер" v-mask="'+7 (###) ###-##-##'" v-model="number_order.phone">
                    </div>
                </div>
                <div class="modal__form-button">
                    <button type="submit">Отправить</button>
                </div>
            </form>
            <div class="modal__policy">
                Отправляя заявку вы соглашаетесь с <a href="/politika-konfidentsialnosti">политикой</a> обработки персональных данных
                </div>
        </div>
    </div>
</template>

<script>
    import InfoPopup from './modals/InfoPopup.vue';
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        data() {
          return {
              number_order: {
                  subject: 'Номер под заказ',
                  number: '',
                  name: '',
                  phone: ''
              }
          }
        },

        methods: {
            submitOrderPopup() {

                if(this.number_order.phone.length < 18) {

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

                var params = Object.assign({}, this.number_order, getUTMTags(), getGACookies());

                this.$http.post('/crm', {fields: params}).then(response => {

                    this.$modal.show(InfoPopup, {
                        title: 'Спасибо за заявку!',
                        text: 'Мы свяжемся с Вами в ближайшее время для уточнения всех деталей'
                    }, {
                        height: 'auto',
                        adaptive: true,
                        width: 400
                    });

                    addToDataLayer({'event':'lead_number_request_form'});

                }, response => {

                    alert('Произошла ошибка.')

                });

                this.number_order = {
                    subject: 'Номер под заказ',
                    number: '',
                    name: '',
                    phone: ''
                };

                this.$emit('close');
            }
        }
    }
</script>