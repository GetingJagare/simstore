<template>
    <div class="header-city d-none d-sm-flex align-items-center">
        <multiselect class="d-inline-block" :allow-empty="false" @select="setRegion" v-model="value" :options="regions" track-by="name" label="name" :searchable="false" :close-on-select="false" :show-labels="false">
            <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
        </multiselect>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        props: ['current'],

        mounted() {
            this.getRegions();
        },

        data() {
            return {
                regions: [],
                value: this.current
            }
        },

        methods: {

            getRegions() {

                this.$http.get('/regions').then(response => {
                    this.regions = response.body.regions;
                }, response => {

                });

            },

            setRegion(selectedOption, id) {
                console.log(selectedOption);
                location.href = '//' + (selectedOption.subdomain !== 'moscow' ? selectedOption.subdomain + '.' : '') + APP_DOMAIN + location.pathname;
            }

        }
    }
</script>

<style>


    .header-city .multiselect__element {
        font-size: 14px;
        letter-spacing: -0.5px;
    }

    .header-city .multiselect__content-wrapper {
        overflow-x: hidden;
    }
</style>