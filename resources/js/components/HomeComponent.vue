<template>
    <b-container fluid class="p-0">

        <b-table
            show-empty
            :items="getProducts"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            hover
            bordered
            head-variant="dark"
        >

            <template slot="row-details" slot-scope="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>

        <b-row>
            <b-col sm="5" md="6" class="my-1">
                <b-form-group
                    label="Per page"
                    label-cols-sm="6"
                    label-cols-md="4"
                    label-cols-lg="3"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="perPageSelect"
                    class="mb-0"
                >
                    <b-form-select
                        v-model="perPage"
                        id="perPageSelect"
                        size="sm"
                        :options="pageOptions"
                    ></b-form-select>
                </b-form-group>
            </b-col>

            <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>


    </b-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        computed: {
            ...mapGetters([
                'getProducts'
            ])
        },
        data() {
            return {
                fields: [
                    {key: 'number', label: 'ID', sortable: true, sortDirection: 'desc'},
                    {key: 'name', label: 'Product', sortable: true, class: 'text-center'},
                    {key: 'rd', label: 'RD', sortable: true, class: 'text-center'},
                    {key: 'wd1', label: 'WD1', sortable: true, class: 'text-center'},
                    {key: 'wd2', label: 'WD2', sortable: true, class: 'text-center'},
                    {key: 'PCF_STRINGER', label: 'SW', sortable: true, class: 'text-center'},
                    {key: 'PCF_TENNISNU', label: 'TN', sortable: true, class: 'text-center'},
                    {key: 'PCF_APOLLOLE', label: 'AP', sortable: true, class: 'text-center'},
                    {key: 'PCF_TENNISPO', label: 'TP', sortable: true, class: 'text-center'},
                    {key: 'PCF_SMASHINN', label: 'SI', sortable: true, class: 'text-center'},
                    {key: 'PCF_TENNISWA', label: 'TWE', sortable: true, class: 'text-center'},
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 10,
                pageOptions: [5, 10, 15],

                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],
                infoModal: {
                    id: 'info-modal',
                    title: '',
                    content: ''
                }
            }
        },
        created() {
            this.$store.dispatch('getProducts');
        },
        watch: {
            getProducts(val) {
                this.totalRows = this.getProducts.length
            }
        },
        methods: {
            info(item, index, button) {
                this.infoModal.title = `Row index: ${index}`
                this.infoModal.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            }
        },
    }
</script>
