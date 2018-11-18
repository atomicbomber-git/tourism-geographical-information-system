<template>
    <div>
        <v-select placeholder="Pencarian Situs" v-model="selected" label="name" :options="options" :onSearch="onSearch">
            <template slot="option" slot-scope="option">
                {{ option.name }}
                <span class="badge badge-primary"> {{ option.site.category.name }} </span>
            </template>

            <span slot="no-options"> Maaf, hasil tidak ditemukan. </span>
        </v-select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            options: [],
            selected: null
        }
    },

    watch: {
        selected: function (oldSelected, newSelected) {
            window.location.replace(`/site/detail/${oldSelected.site.id}`);
        }
    },

    methods: {
        onSearch(param, loading) {
            loading(true)

            axios.get(`/site/search?keyword=${param}`)
                .then(response => {
                    console.log(response.data)
                    this.options = response.data
                    loading(false)
                })
                .catch(error => {})
        }
    }
}
</script>

<style>

</style>
