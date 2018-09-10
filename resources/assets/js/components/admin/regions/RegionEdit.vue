<template>
    <div>
        <b-card title="Изменить регион">

            <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

            <b-form @submit="onSubmit">

                <b-form-group label="Название региона:">
                    <b-form-input type="text" v-model="form.name" required placeholder="Московская область" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Административный центр:" description="По этому городу будет работать автоопределение">
                    <b-form-input type="text" v-model="form.city" required placeholder="Москва" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Поддомен:">
                    <b-input-group>
                        <b-input-group-text slot="append">.{{ domain }}</b-input-group-text>
                        <b-form-input type="text" v-model="form.subdomain" required placeholder="moscow" autocomplete="off"></b-form-input>
                    </b-input-group>
                </b-form-group>

                <b-form-group label="Коды города:" description="Укажите телефонные коды, чтобы пользователю с этого региона показывались только номера его региона.">
                    <multiselect v-model="form.codes" placeholder="Укажите коды города" tag-placeholder="Нажмите Enter, чтобы добавить код" :options="form.codes" label="code" track-by="code" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                </b-form-group>

                <b-form-group label="Подставляемое название региона:" description="Текст, который будет подставляться вместо шорткода {region}. По-умолчанию нужно указать название региона в предложном падеже.">
                    <b-form-input type="text" v-model="form.name_pr" required placeholder="Москве" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-button type="submit" variant="primary">Обновить</b-button>
            </b-form>
        </b-card>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        components: {
            Multiselect
        },

        mounted() {

            this.getRegion();

        },

        data() {

            return {
                domain: location.host,
                form: {
                    name: '',
                    city: '',
                    name_pr: '',
                    subdomain: '',
                    codes: []
                },

                mess: {
                    success: ''
                },
            }

        },

        methods: {

            addTag(newTag) {

                const tag = {
                    code: newTag,
                };

                this.form.codes.push(tag);
            },

            getRegion() {

                this.$http.get('/admin/regions/get?id=' + this.$route.params.id).then(response => {

                    this.form = response.body.region;

                }, response => {

                });

            },

            onSubmit (evt) {

                evt.preventDefault();
                var _that = this;

                this.$http.post('/admin/regions/add', this.form).then(response => {

                    this.mess.success = response.body.message;

                    setTimeout(function () {
                        _that.mess.success = '';
                    }, 4000);

                }, response => {
                    // error callback
                });

            }

        }
    }
</script>
