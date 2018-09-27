<template>
    <div>
        <div class="row">
            <div class="col-md-12"><h3>Номера</h3></div>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <b-form @submit.prevent="searchNumbers" inline class="mt-2">
                    <b-form-input class="mb-2 mr-sm-4 mb-sm-0" type="text" placeholder="Поиск номера" autocomplete="off" v-model="filter.search"></b-form-input>
                    <b-form-input class="mb-2 mr-sm-2 mb-sm-0" type="text" placeholder="Цена от" autocomplete="off" v-model="filter.price_from" style="width: 90px"></b-form-input>
                    <b-form-input class="mb-2 mr-sm-4 mb-sm-0" type="text" placeholder="Цена до" autocomplete="off" v-model="filter.price_to" style="width: 90px"></b-form-input>
                    <b-form-select class="mr-sm-2" v-model="filter.option">
                        <option :value="null">Поиск по свойству</option>
                        <option value="discount">Продаются по скидкам</option>
                        <option value="sale">На распродаже</option>
                        <option value="gold-partner">Золотые (по цене партнёра)</option>
                        <option value="platinum-partner">Платиновые (по цене партнёра)</option>
                        <option value="gold-self">Золотые (по цене сайта)</option>
                        <option value="platinum-self">Платиновые (по цене сайта)</option>
                        <option value="saled">Проданные</option>
                    </b-form-select>
                    <b-button variant="primary" type="submit">Поиск</b-button>
                </b-form>
            </div>

            <div class="col-sm-12" v-if="checked_numbers.length">
                <b-form @submit="changeOption" class="mt-2" inline>
                    <b-form-select class="mr-sm-2" v-model="checked_option">
                        <option :value="null">Сделать с выбранными...</option>
                        <option value="price">Изменить цену</option>
                        <option value="add-discount">Добавить скидку</option>
                        <option value="remove-discount">Убрать скидку</option>
                        <option value="sale-add">Добавить в блок распродажи</option>
                        <option value="sale-remove">Убрать из блока распродажи</option>
                        <option value="saled-add">Отметить проданными</option>
                        <option value="saled-remove">Отметить не проданными</option>
                    </b-form-select>
                    <b-button variant="primary" type="submit" class="mr-sm-2">ОК</b-button>
                    <b-button variant="outline-secondary" @click.prevent="clearCheckedNumbers">Снять выделение</b-button>
                </b-form>
            </div>
        </div>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Номер</th>
                <th>Цена партнера</th>
                <th>Цена на сайте</th>
                <th>Цена номера</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(number, index) in numbers">
                <td><input type="checkbox" :value="{id: number.id, index: index}" v-model="checked_numbers"></td>
                <td>{{ number.id }}</td>
                <td>
                    <span>{{ number.value }}</span>
                    <span v-if="number.discount" style="background: #f7d23e;font-weight: 600;padding: 0 6px;margin: 0 0 0 10px;border-radius: 3px;font-size: 14px;">Акция</span>
                    <span v-if="number.on_sale" style="background: #d6e5ff;font-weight: 600;padding: 0 6px;margin: 0 0 0 10px;border-radius: 3px;font-size: 14px;">На распродаже</span>
                    <span v-if="number.saled" style="background: #e2e2e2;font-weight: 600;padding: 0 6px;margin: 0 0 0 10px;border-radius: 3px;font-size: 14px;">Продано</span>
                </td>
                <td>
                    <span>
                        {{ number.price }}
                    </span>
                </td>
                <td>
                    <div v-if="edit.id == number.id">

                        <div class="input-group">
                            <input type="text" class="form-control" style="width: auto" v-model="edit.price">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" v-on:click.prevent="setPrice(index)">Сохранить</button>
                                <button class="btn btn-outline-secondary" type="button" v-on:click.prevent="cancelEditNumber">Отмена</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <span v-bind:class="{ textlined: number.discount_price }">{{ number.price_new }}</span>
                        <span v-if="number.discount_price">
                            {{ number.discount_price }}
                        </span>
                        <a class="ml-3" href="#" v-on:click.prevent="editNumber(number)">Изменить</a>
                    </div>
                </td>
                <td>
                    <div v-if="rentalPriceEdit.id == number.id">

                        <div class="input-group">
                            <input type="text" class="form-control" style="width: auto" v-model="rentalPriceEdit.price">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" v-on:click.prevent="setRentalPrice(index)">Сохранить</button>
                                <button class="btn btn-outline-secondary" type="button" v-on:click.prevent="cancelEditRentalPrice">Отмена</button>
                            </div>
                        </div>

                    </div>
                    <div v-else>
                        <span>{{ number.price_rental }} руб/мес</span>
                        <a class="ml-3" href="#" v-on:click.prevent="editRentalPrice(number)">Изменить</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <b-button v-if="prev_page_url" v-on:click="getNumbers(true)">Предыдущая страница</b-button>
        <b-button v-if="next_page_url" v-on:click="getNumbers(false)" variant="success">Следующая страница</b-button>

        <!-- Модаль массового изменения цены -->
        <b-modal ref="massPriceChangeModal" title="Массовое изменение цены" @ok="massPriceChangeModal">
            <form>
                <b-form-input type="text" placeholder="Введите новую цену" v-model="mass_price"></b-form-input>
            </form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.getNumbers();
        },

        data() {

            return {
                checked_numbers: [],
                checked_option: null,
                mass_price: null,

                filter: {
                    search: '',
                    price_from: null,
                    price_to: null,
                    option: null
                },

                edit: {
                    id: null,
                    price: null
                },

                rentalPriceEdit: {
                    id: null,
                    price: null
                },

                numbers: [],
                next_page_url: '/admin/numbers',
                prev_page_url: '',
                search: '',

                table_fields: [
                    {
                        key: 'id',
                        label: 'ID'
                    },
                    {
                        key: 'value',
                        label: 'Номер'
                    },
                    {
                        key: 'price',
                        label: 'Цена'
                    }
                ]

            }

        },

        methods: {

            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            },

            changeOption(evt) {
                evt.preventDefault();

                if(this.checked_numbers.length === 0) {
                    this.checked_option = null;
                    return alert('Вы не выбрали номера телефонов для изменения');
                }

                if(this.checked_option === 'price') {
                    return this.$refs.massPriceChangeModal.show();
                } else if(this.checked_option === 'add-discount') {
                    return this.massDiscountSet('add-discount');
                } else if(this.checked_option === 'remove-discount') {
                    return this.massDiscountSet('remove-discount');
                } else if(this.checked_option === 'sale-add') {
                    return this.massToSale('sale-add');
                } else if(this.checked_option === 'sale-remove') {
                    return this.massToSale('sale-remove');
                } else if(this.checked_option === 'saled-add') {
                    return this.massSaledNumbers('saled-add');
                } else if(this.checked_option === 'saled-remove') {
                    return this.massSaledNumbers('saled-remove');
                }

            },

            massPriceChangeModal() {

                var _that = this;
                this.$http.post('/admin/numbers/mass-price-edit', {price: this.mass_price, numbers: this.checked_numbers}).then(response => {

                    _that.checked_numbers.forEach(function(item, i, arr) {
                        _that.numbers[item.index].price_new = _that.mass_price;
                    });

                    _that.checked_numbers = [];
                    _that.checked_option = null;

                }, response => {

                    //this.$refs.massPriceChangeModal.hide();

                });
            },

            massSaledNumbers(action) {

                var _that = this;
                this.$http.post('/admin/numbers/saled', {action: action, numbers: this.checked_numbers}).then(response => {

                    _that.checked_numbers.forEach(function(item, i, arr) {
                        _that.numbers[item.index] = response.body.numbers[item.id];
                    });

                    if(action === 'saled-add') {
                        alert('Выбранные номера отмечены проданными и не будут отображаться в каталоге');
                    } else if(action === 'saled-remove') {
                        alert('Выбранные номера отмечены не проданными и будут отображаться в каталоге');
                    }

                    _that.checked_numbers = [];
                    _that.checked_option = null;

                }, response => {

                    //this.$refs.massPriceChangeModal.hide();

                });

            },

            massDiscountSet(action) {

                var _that = this;
                this.$http.post('/admin/numbers/discount', {action: action, numbers: this.checked_numbers}).then(response => {

                    _that.checked_numbers.forEach(function(item, i, arr) {
                        _that.numbers[item.index] = response.body.numbers[item.id];
                    });

                    if(action === 'add-discount') {
                        alert('Выбранные номера теперь будут продаваться по скидке, указанной в настройках');
                    } else if(action === 'remove-discount') {
                        alert('Выбранные номера теперь будут продаваться базовой цене');
                    }

                    _that.checked_numbers = [];
                    _that.checked_option = null;

                }, response => {

                    //this.$refs.massPriceChangeModal.hide();

                });

            },

            massToSale(action) {

                var _that = this;
                this.$http.post('/admin/numbers/sale', {action: action, numbers: this.checked_numbers}).then(response => {

                    _that.checked_numbers.forEach(function(item, i, arr) {
                        _that.numbers[item.index] = response.body.numbers[item.id];
                    });

                    _that.checked_numbers = [];
                    _that.checked_option = null;

                }, response => {

                    //this.$refs.massPriceChangeModal.hide();

                });

            },

            getNumbers(prev) {
                this.$http.get(prev ? this.prev_page_url : this.next_page_url).then(response => {

                    this.numbers = response.body.numbers.data;
                    this.next_page_url = response.body.numbers.next_page_url;
                    this.prev_page_url = response.body.numbers.prev_page_url;

                    //this.settings = response.body.settings;
                    //this.show = true;
                }, response => {

                });
            },

            searchNumbers() {

                if(this.filter.option === 'gold-partner') {
                    this.filter.price_from = 10000;
                    this.filter.price_to = 100000;
                } else if (this.filter.option === 'platinum-partner') {
                    this.filter.price_from = 100000;
                    this.filter.price_to = 100000;
                } else if(this.filter.option === 'gold-self') {
                    this.filter.price_from = 10000;
                    this.filter.price_to = 100000;
                } else if (this.filter.option === 'platinum-self') {
                    this.filter.price_from = 100000;
                    this.filter.price_to = 100000;
                }

                this.$http.get('/admin/numbers?' + this.serialize(this.filter)).then(response => {

                    this.numbers = response.body.numbers.data;
                    this.next_page_url = response.body.numbers.next_page_url;
                    this.prev_page_url = response.body.numbers.prev_page_url;

                }, response => {

                });

            },

            editNumber(number) {

                this.edit.id = number.id;
                this.edit.price = number.price_new;

            },

            editRentalPrice(number) {
                this.rentalPriceEdit.id = number.id;
                this.rentalPriceEdit.price = number.price_rental;
            },

            cancelEditRentalPrice() {

                this.rentalPriceEdit = {
                    id: null,
                    price: null
                };

            },

            cancelEditNumber() {
                this.edit = {
                    id: null,
                    price: null
                };
            },

            setPrice(index) {

                this.$http.post('/admin/numbers/edit', this.edit).then(response => {

                    this.numbers[index].price_new = this.edit.price;
                    this.cancelEditNumber();

                }, response => {

                });

            },

            setRentalPrice(index) {

                this.$http.post('/admin/numbers/edit-rental-price', this.rentalPriceEdit).then(response => {

                    this.numbers[index].price_rental = this.edit.price_rental;
                    this.cancelEditRentalPrice();

                }, response => {

                });

            },

            clearCheckedNumbers() {
                this.checked_numbers = [];
            }

        }
    }
</script>

<style>
    span.textlined {
        text-decoration: line-through;
    }
</style>