<template>
    <div>
        <div class="row">
            <div class="col-md-12"><h3>Импорт номеров</h3></div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <b-form @submit.prevent="importNumbers" class="mt-2" enctype="mutlipart/form-data">
                    <b-form-file class="mb-2 mr-sm-4 mb-sm-0" placeholder="Выберите файл .xls, .xlsx" v-model="file" ref="fileinput"
                        name="numbers" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></b-form-file>
                    <b-alert variant="danger" class="d-block mt-3" :show="wrongExtension" dismissible
                             @dismissed="wrongExtension=false;">
                        Неверное расширение файла!
                    </b-alert>
                    <div class="d-block mt-3 mb-3">Выбранный файл: {{file && file.name}}</div>
                    <b-button variant="primary" type="submit">Сохранить</b-button>
                    <loading :active.sync="isLoading"></loading>
                </b-form>
            </div>
        </div>
    </div>
</template>

<script>
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        mounted() {
            console.log('Component mounted.');
        },

        data() {
            return {
                isLoading: false,
                wrongExtension: false,
                file: null
            }
        },

        components: {Loading},

        methods: {
            importNumbers() {
                if (!this.file) {
                    return;
                }

                var formData = new FormData(), $vm = this;
                formData.append('numbers', this.file);
                this.isLoading = true;
                this.$http.post('admin/numbers/import', formData).then(response => {
                    $vm.isLoading = false;
                }, response => {
                    $vm.isLoading = false;
                });
            },
        },

        watch: {
            file: function (value) {
                if (value && value.name) {
                    if (!/.xls|xlsx$/.test(value.name)) {
                        this.wrongExtension = true;
                        this.$refs.fileinput.reset();
                    } else {
                        this.wrongExtension = false;
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>