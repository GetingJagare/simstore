<template>
    <div>
        <b-card title="Изменить регион">

            <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

            <b-form @submit="onSubmit">

                <b-form-group label="Название региона:">
                    <b-form-input type="text" v-model="form.name" required placeholder="Московская область"
                                  autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Административный центр:"
                              description="По этому городу будет работать автоопределение">
                    <b-form-input type="text" v-model="form.city" required placeholder="Москва"
                                  autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Поддомен:">
                    <b-input-group>
                        <b-input-group-text slot="append">.{{ domain }}</b-input-group-text>
                        <b-form-input type="text" v-model="form.subdomain" required placeholder="moscow"
                                      autocomplete="off"></b-form-input>
                    </b-input-group>
                </b-form-group>

                <b-form-group label="Коды города:"
                              description="Укажите телефонные коды, чтобы пользователю с этого региона показывались только номера его региона.">
                    <multiselect v-model="form.codes" placeholder="Укажите коды города"
                                 tag-placeholder="Нажмите Enter, чтобы добавить код" :options="form.codes" label="code"
                                 track-by="code" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                </b-form-group>

                <b-form-group label="Подставляемое название региона:"
                              description="Текст, который будет подставляться вместо шорткода {region}. По-умолчанию нужно указать название региона в предложном падеже.">
                    <b-form-input type="text" v-model="form.name_pr" required placeholder="Москве"
                                  autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Подставляемое название региона (дат. падеж):"
                              description="Текст, который будет подставляться в УРЛы типа 'Для звонков по...'.">
                    <b-form-input type="text" v-model="form.name_dat" required placeholder="Москве"
                                  autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Юр.адрес" class="mt-3">
                    <b-form-input v-model="form.address" autocomplete="off"></b-form-input>
                </b-form-group>

                <b-form-group label="Коды верификации" class="mt-3">
                    <div v-for="(code, index) in form.verif_codes" class="mt-2 d-flex">
                        <b-form-input v-model="code.meta_name" class="mr-3 d-inline-block"
                                      placeholder="Название meta"></b-form-input>
                        <b-form-input v-model="code.meta_content" class="mr-3 d-inline-block"
                                      placeholder="Содержимое meta"></b-form-input>
                        <b-button variant="danger" @click="deleteVerifCode(index)">Удалить</b-button>
                    </div>
                    <b-button variant="warning" class="mt-3" @click="addVerifCode">Добавить код верификации</b-button>
                </b-form-group>

                <b-form-group label="Гугл-карта" class="mt-3">
                    <gmap-autocomplete :componentRestrictions='{country: "ru"}' @place_changed="updatePlace"
                                       placeholder="Введите местоположение"
                                       class="form-control place-control" v-if="regionLoaded"></gmap-autocomplete>
                    <gmap-map :center="form.geo" :zoom="14" style="width: 100%; height: 300px;"
                              ref="map" v-if="regionLoaded" class="region-map">
                    </gmap-map>
                </b-form-group>

                <b-button type="submit" variant="primary">Обновить</b-button>
            </b-form>
        </b-card>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    import * as VueGoogleMaps from 'vue2-google-maps';

    Vue.use(VueGoogleMaps, {
        load: {
            key: 'AIzaSyA4ec8KnKv4HjLFHKcq-TD_iffaZaSufYo',
            libraries: 'places'
        }
    });

    export default {

        components: {
            Multiselect
        },

        mounted() {

            this.getRegion();

        },

        data() {

            return {
                regionLoaded: false,
                domain: location.host,
                form: {
                    name: '',
                    geo: {},
                    city: '',
                    name_pr: '',
                    name_dat: '',
                    subdomain: '',
                    verif_codes: [],
                    codes: [],
                    address: '',
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
                    this.regionLoaded = true;

                }, response => {

                });

            },

            onSubmit(evt) {

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

            },

            addVerifCode() {
                let codesCount = 0;
                if (!this.form.verif_codes) {
                    this.form.verif_codes = [];
                } else {
                    codesCount = this.form.verif_codes ? this.form.verif_codes.length : 0;
                }

                this.$set(this.form.verif_codes, codesCount, {meta_name: '', meta_content: ''});
            },

            deleteVerifCode(index) {
                this.form.verif_codes.splice(index, 1);
            },

            boundsChanged() {
                const map = this.$refs.map.$mapObject;

                console.log(this.form.geo);
            },

            updateCenter (location) {
                this.form.geo = {
                    lat: location.lat(),
                    lng: location.lng(),
                }
            },

            updatePlace(place) {

                console.log(place);
                this.updateCenter(place.geometry.location);
            }
        }
    }
</script>

<style scoped>
    .place-control {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
</style>