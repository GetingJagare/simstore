<template>
    <div>
        <div class="row">
            <div class="col-md-6"><h3>Заказ #{{ $route.params.id }}</h3></div>
        </div>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ order.id }}</td>
                <td>{{ order.name }}</td>
                <td>{{ order.phone }}</td>
                <td>{{ order.address }}</td>
                <td>{{ order.created_at }}</td>
            </tr>
            </tbody>
        </table>

        <h5>Заказанные номера</h5>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Цена</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="number in order.numbers">
                <td>{{ number.value }}</td>
                <td>{{ number.price }}</td>
            </tr>
            </tbody>
        </table>

        <h5>Заказанные тарифы</h5>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th>Название</th>
                <th>Цена</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="tariff in order.tariffs">
                <td>{{ tariff.name }}</td>
                <td>{{ tariff.price }}</td>
            </tr>
            </tbody>
        </table>

        <h5>Сумма заказа: {{ order.summ }} ₽</h5>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getOrder();
        },

        data() {

            return {
                order: []
            }

        },

        methods: {

            getOrder() {

                this.$http.get('/admin/orders/get?id=' + this.$route.params.id).then(response => {

                    this.order = response.body.order;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            }

        }
    }
</script>
