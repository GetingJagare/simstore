<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Изменение страницы</h3></div>
        </div>

        <div class="mt-4">
            <b-card>

                <b-alert variant="success" :show="mess.success.length > 0">{{ mess.success }}</b-alert>

                <b-form @submit="onSubmit">

                    <b-form-group label="Название:">
                        <b-form-input type="text" v-model="page.name" required placeholder="Информация" autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Альтернативное название:">
                        <b-form-input type="text" v-model="page.alter_name" placeholder="Информация" autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Адрес:">
                        <b-input-group>
                            <b-input-group-text slot="prepend">{{ domain }}/</b-input-group-text>
                            <b-form-input type="text" v-model="page.slug" autocomplete="off"></b-form-input>
                        </b-input-group>
                    </b-form-group>

                    <b-form-group label="SEO-заголовок:" description="Вместо {region} автоматически подставляется название региона.">
                        <b-form-input type="text" v-model="page.seo_title" placeholder="Информация" autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="SEO-описание:" description="Вместо {region} автоматически подставляется название региона.">
                        <b-form-textarea autocomplete="off" v-model="page.seo_description" placeholder="Информация" :rows="3" :max-rows="6">
                        </b-form-textarea>
                    </b-form-group>

                    <b-form-group label="Содержимое страницы:">
                        <textarea id="content" cols="30" rows="10">{{ page.content }}</textarea>
                    </b-form-group>

                    <b-button type="submit" variant="primary">Обновить информацию</b-button>
                </b-form>
            </b-card>
        </div>

    </div>
</template>

<script>
    import tinymce from 'tinymce/tinymce'
    import 'tinymce/themes/modern/theme';
    import 'tinymce/plugins/image';
    import 'tinymce/plugins/autoresize';
    import 'tinymce/plugins/code';
    import 'tinymce/plugins/paste';

    export default {
        mounted() {
            this.getPage(this.$route.params.id);
        },
        data() {

            return {
                domain: location.host,
                mess: {
                    success: ''
                },
                page: {
                    id: '',
                    name: '',
                    alter_name: '',
                    slug: '',
                    seo_title: '',
                    seo_description: '',
                    content: ''
                }
            }
        },

        methods: {

            getPage(id) {

                this.$http.get('/admin/pages/get?id=' + id).then(response => {

                    this.page.id = response.body.page.id;
                    this.page.name = response.body.page.name;
                    this.page.alter_name = response.body.page.alter_name;
                    this.page.slug = response.body.page.slug;
                    this.page.seo_title = response.body.page.seo_title ? response.body.page.seo_title : '';
                    this.page.seo_description = response.body.page.seo_description ? response.body.page.seo_description : '';
                    this.page.content = response.body.page.content ? response.body.page.content : '';

                    tinymce.init({
                        selector: '#content',
                        plugins: ['image', 'autoresize', 'code', 'paste'],
                        language: 'ru',
                        paste_as_text: true,
                        init_instance_callback: (editor) => {
                            editor.on('KeyUp', (e) => {
                                this.page.content = editor.getContent();
                            });

                            editor.on('Change', (e) => {
                                this.page.content = editor.getContent();
                            });
                        }
                    });

                    tinymce.get('content').setContent(this.page.content);

                }, response => {

                });

            },

            onSubmit (evt) {

                evt.preventDefault();
                this.$http.post('/admin/pages/edit', this.page).then(response => {

                    this.mess.success = response.body.message;

                    var _that = this;
                    setTimeout(function () {
                        _that.mess.success = '';
                    }, 4000);

                }, response => {
                    // error callback
                });

            },

        },

        beforeRouteLeave (to, from, next) {

            tinymce.remove("#content");
            //tinymce.get('content').setContent('dss');

            // called when the route that renders this component is about to
            // be navigated away from.
            // has access to `this` component instance.

            next()

        }

    }
</script>

<style>
    .mce-panel {
        border: 0 solid #ced4da!important
    }

    .mce-tinymce {
        box-shadow: none!important
    }
</style>