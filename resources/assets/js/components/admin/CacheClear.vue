<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Очистить кэш</h3></div>
        </div>

        <b-alert variant="danger" :show="!result.success && result.message.length > 0">{{ result.message }}</b-alert>
        <b-alert variant="success" :show="result.success && result.message.length > 0">{{ result.message }}</b-alert>

        <div class="mt-3">
            <b-form-checkbox v-model="allSelected" @change="selectAll" v-bind:style="{'margin-right': 0}"></b-form-checkbox>
            <span v-bind:style="{'vertical-align': 'top'}">{{ allSelected ? 'Отменить' : 'Отметить все' }}</span>
        </div>

        <b-form @submit.prevent="clearCache">
            <b-form-checkbox-group :options="options" v-model="selected" stacked class="ml-4 mt-1 mb-3"></b-form-checkbox-group>

            <b-button type="submit" variant="primary">Применить</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getItems();
        },
        data() {
            return {
                result: {success: 0, message: ''},
                allSelected: false,
                selected: [],
                options: []
            }
        },
        methods: {
            clearCache() {
                if (this.selected.length) {
                    this.$http.post('/admin/cache/clear', {items: this.selected}).then(response => {
                        this.result = response.body;

                        var _this = this;
                        setTimeout(function () {
                            _this.result.message = '';
                        }, 3000);
                    });
                }
            },

            selectAll(checked) {
                if (checked) {
                    var selectedOptions = [];
                    this.options.forEach(function (opt) {
                        selectedOptions.push(opt.value);
                    });
                    this.selected = selectedOptions;
                } else {
                    this.selected = [];
                }
            },

            getItems() {
                this.$http.get('/admin/cache/get-items').then(response => {
                   this.options = response.body;
                });
            }
        },
        watch: {
            selected: function (value) {
                this.allSelected = value.length === this.options.length;
            }
        },
    }
</script>

<style>

</style>