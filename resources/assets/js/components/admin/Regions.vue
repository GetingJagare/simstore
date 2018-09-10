<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Регионы</h3></div>
            <div class="col-md-6 text-right"><b-btn v-b-toggle.collapse1 variant="primary">Добавить</b-btn></div>
        </div>

        <div class="mt-4">
            <b-collapse id="collapse1" class="mt-4">
                <b-card title="Добавить регион">

                    <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

                    <b-form @submit="onSubmit" @reset="onReset">

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

                        <b-button type="submit" variant="primary">Добавить</b-button>
                        <b-button type="reset">Очистить</b-button>
                    </b-form>
                </b-card>
            </b-collapse>
        </div>

        <b-table class="mt-4" hover :items="regions" :fields="table_fields" :outlined="true">
            <template slot="subdomain" slot-scope="data">
                <a :href="'//' + data.value + '.' + domain" target="_blank">{{ data.value + '.' + domain }}</a>
            </template>
            <template slot="id" slot-scope="data">
                <router-link :to="'/regions/' + data.value">Изменить</router-link>
            </template>
        </b-table>

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {
        components: {
            Multiselect
        },
        mounted() {
            this.getRegions();
        },

        data () {
            return {
                domain: location.host,
                form: {
                    name: '',
                    city: '',
                    name_pr: '',
                    subdomain: '',
                    codes: []
                },

                codesval: [],

                mess: {
                    success: ''
                },
                regions: [],
                table_fields: [
                {
                    key: 'name',
                    label: 'Название'
                },
                {
                    key: 'city',
                    label: 'Город'
                },
                {
                    key: 'subdomain',
                    label: 'Поддомен'
                },
                {
                    key: 'id',
                    label: 'Опции'
                }
            ]
            }
        },

        methods: {

            addTag(newTag) {

                const tag = {
                    code: newTag,
                };

                this.form.codes.push(tag);
            },

            getRegions() {

                this.$http.get('/admin/regions').then(response => {

                    this.regions = response.body.regions;

                }, response => {

                });

            },

            onReset (evt) {
                evt.preventDefault();
                this.resetForm();
            },

            onSubmit (evt) {

                evt.preventDefault();

                this.$http.post('/admin/regions/add', this.form).then(response => {

                    this.mess.success = response.body.message;
                    this.resetForm();

                }, response => {
                    // error callback
                });

            },

            resetForm () {

                var _that = this;
                this.form.name = '';
                this.form.city = '';
                this.form.codes = [];
                this.form.name_pr = '';
                this.form.subdomain = '';

                setTimeout(function () {
                    _that.mess.success = '';
                }, 4000);

            }

        }
    }
</script>