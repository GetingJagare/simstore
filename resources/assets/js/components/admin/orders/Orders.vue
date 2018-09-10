<template>
    <div>
        <div class="row">
            <div class="col-md-12"><h3>Заказы</h3></div>
        </div>

        <table class="mt-4 table b-table table-hover border">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Сумма заказа</th>
                <th>Дата</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="order in orders">
                <td>{{ order.id }}</td>
                <td>{{ order.name }}</td>
                <td>{{ order.phone }}</td>
                <td>{{ order.summ }}</td>
                <td>{{ order.created_at }}</td>
                <td><router-link :to="'/orders/' + order.id">Детали</router-link></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getOrders();
        },

        data() {
            return {
                orders: []
            }
        },

        methods: {

            getOrders() {

                this.$http.get('/admin/orders', this.form).then(response => {

                    this.orders = response.body.orders;

                }, response => {

                    alert('Произошла ошибка при загрузке данных');

                });

            }

        }
    }
</script>
