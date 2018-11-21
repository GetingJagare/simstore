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
                        <b-form-input type="text" v-model="page.name" required placeholder="Информация"
                                      autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Альтернативное название:">
                        <b-form-input type="text" v-model="page.alter_name" placeholder="Информация"
                                      autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Показывать на сайте">
                        <b-form-checkbox @change="isShowOnSiteChecked" v-model="showOnSite"></b-form-checkbox>
                    </b-form-group>

                    <b-form-group label="Исключить из sitemap">
                        <b-form-checkbox v-model="excludeFromSitemap"
                                         @change="excludeFromSitemapChange"></b-form-checkbox>
                    </b-form-group>

                    <b-form-group label="Адрес:">
                        <b-input-group>
                            <b-input-group-text slot="prepend">{{ domain }}/</b-input-group-text>
                            <b-form-input type="text" v-model="page.slug" autocomplete="off"></b-form-input>
                        </b-input-group>
                    </b-form-group>

                    <b-form-group label="SEO-заголовок:"
                                  description="Вместо {region} автоматически подставляется название региона.<br />Вместо {region2} автоматически подставляется название региона в дательном падеже.">
                        <b-form-input type="text" v-model="page.seo_title" placeholder="Информация"
                                      autocomplete="off"></b-form-input>
                    </b-form-group>

                    <b-form-group label="SEO-описание:"
                                  description="Вместо {region} автоматически подставляется название региона.<br />Вместо {region2} автоматически подставляется название региона в дательном падеже">
                        <b-form-textarea autocomplete="off" v-model="page.seo_description" placeholder="Информация"
                                         :rows="3" :max-rows="6">
                        </b-form-textarea>
                    </b-form-group>

                    <b-form-group label="Содержимое страницы:">
                        <textarea id="content" cols="30" rows="10">{{ page.content }}</textarea>
                    </b-form-group>

                    <b-form-group label="Добавить небольшое описание">
                        <b-form-checkbox v-model="addSmallDesc" @change="isSmallDescChecked"></b-form-checkbox>
                    </b-form-group>

                    <b-form-group label="Описание:" v-if="addSmallDesc">
                        <textarea id="small_desc" cols="30" rows="5">{{ page.small_desc }}</textarea>
                    </b-form-group>

                    <b-form-group label="Добавить фильтры">
                        <b-form-checkbox v-model="showFilters" @change="isShowFiltersChecked"></b-form-checkbox>
                    </b-form-group>

                    <b-form-group label="Выбрать тип фильтра" v-if="showFilters">
                        <b-form-select :options="{'numbers': 'Номера', 'tariffs': 'Тарифы'}"
                                       v-model="chosenFilter"></b-form-select>
                    </b-form-group>

                    <b-form-group>
                        <b-form-group v-if="chosenFilter === 'numbers'">
                            <b-form-input type="number" placeholder="От" v-model="page.filters.value[0]"
                                          class="mt-2"></b-form-input>
                            <b-form-input type="number" placeholder="До" v-model="page.filters.value[1]"
                                          class="mt-2"></b-form-input>
                            <b-form-group label="Акция" class="mt-2">
                                <b-form-checkbox v-model="page.filters.promo"></b-form-checkbox>
                            </b-form-group>
                        </b-form-group>
                        <b-form-group v-else-if="chosenFilter === 'tariffs'">
                            <b-form-checkbox-group :options="tariffTypesOptions"
                                                   v-model="page.filters.value"></b-form-checkbox-group>
                        </b-form-group>
                    </b-form-group>

                    <b-form-group label="Шаблон:" class="mt-2">
                        <b-form-select :options="templates" class="mt-0 mb-0" v-model="page.template"></b-form-select>
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
    import 'tinymce/plugins/media';
    import 'tinymce/plugins/link';
    import 'tinymce/plugins/advlist';

    export default {
        mounted() {
            this.getPage(this.$route.params.id);
        },
        data() {

            return {
                domain: location.host,
                addSmallDesc: false,
                showOnSite: false,
                showFilters: false,
                excludeFromSitemap: false,
                savedFilters: {},
                chosenFilter: '',
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
                    content: '',
                    template: '',
                    show_on_site: 0,
                    small_desc: '',
                    exclude_from_sitemap: 0,
                    filters: {
                        name: '',
                        value: [],
                        promo: false,
                    }
                },
                templates: {},
                tariffTypesOptions: [
                    {value: 'unlimited', text: 'Без ограничений'},
                    {value: 'unlimited_ru', text: 'Для звонков по России'},
                    {value: 'for_internet', text: 'Интернет'}
                ],
            }
        },

        methods: {
            initEditor(selector, field) {
                tinymce.init({
                    selector: selector,
                    plugins: ['image autoresize code paste media advlist'],
                    language: 'ru',
                    paste_as_text: true,
                    paste_data_images: true,
                    image_advtab: true,
                    image_title: true,
                    automatic_uploads: true,
                    images_upload_url: '/upload',
                    file_picker_types: 'image',
                    init_instance_callback: (editor) => {
                        editor.on('KeyUp', (e) => {
                            this.page[field] = editor.getContent();
                        });

                        editor.on('Change', (e) => {
                            this.page[field] = editor.getContent();
                        });
                    }
                });

                tinymce.get(field).setContent(this.page[field]);
            },

            getPage(id) {

                this.$http.get('/admin/pages/get?id=' + id).then(response => {

                    this.page.id = response.body.page.id;
                    this.page.name = response.body.page.name;
                    this.page.alter_name = response.body.page.alter_name;
                    this.page.slug = response.body.page.slug;
                    this.page.seo_title = response.body.page.seo_title ? response.body.page.seo_title : '';
                    this.page.seo_description = response.body.page.seo_description ? response.body.page.seo_description : '';
                    this.page.content = response.body.page.content ? response.body.page.content : '';
                    this.page.template = response.body.page.template ? response.body.page.template : 'frontend.static';
                    this.page.show_on_site = response.body.page.show_on_site;
                    this.showOnSite = !!response.body.page.show_on_site;
                    this.page.small_desc = response.body.page.small_desc ? response.body.page.small_desc : '';
                    this.page.exclude_from_sitemap = response.body.page.exclude_from_sitemap;
                    this.excludeFromSitemap = !!response.body.page.exclude_from_sitemap;

                    this.savedFilters = response.body.page.filters ? JSON.parse(response.body.page.filters) : {
                        name: '',
                        value: [],
                        promo: false,
                    };

                    this.showFilters = this.savedFilters.value.length > 0 || this.savedFilters.promo;
                    this.isShowFiltersChecked(this.showFilters);

                    this.initEditor('#content', 'content');

                    this.templates = response.body.templates;

                    if (this.page.small_desc.length) {
                        this.addSmallDesc = true;
                    }

                }, response => {

                });

            },

            onSubmit(evt) {

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

            isShowOnSiteChecked(checked) {
                this.page.show_on_site = checked ? 1 : 0;
                this.showOnSite = checked;
            },

            isSmallDescChecked(checked) {
                this.addSmallDesc = checked;
            },

            isShowFiltersChecked(checked) {
                this.showFilters = checked;
                if (!checked) {
                    this.chosenFilter = '';
                } else {
                    this.chosenFilter = this.savedFilters.value.length > 0 || this.savedFilters.promo ? this.savedFilters.name : '';
                }
            },

            excludeFromSitemapChange(checked) {
                this.page.exclude_from_sitemap = checked ? 1 : 0;
                this.excludeFromSitemap = checked;
            },

            resetFilters() {
                this.page.filters = {name: '', value: [], promo: false};
                this.savedFilters = {name: '', value: [], promo: false};
            },

        },

        watch: {
            addSmallDesc: function (value) {
                if (!value && tinymce.editors.small_desc) {
                    tinymce.editors.small_desc.remove();
                }
            },
            chosenFilter: function (value) {
                if (!value || !value.length) {
                    this.resetFilters();
                } else {
                    this.page.filters = this.savedFilters;
                    if (this.page.filters.name !== value) {
                        this.resetFilters();
                    }
                    this.page.filters.name = value;
                }
            }
        },

        updated() {
            if (this.addSmallDesc) {
                this.initEditor('#small_desc', 'small_desc');
            }
        },

        beforeRouteLeave(to, from, next) {

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
        border: 0 solid #ced4da !important
    }

    .mce-tinymce {
        box-shadow: none !important
    }
</style>