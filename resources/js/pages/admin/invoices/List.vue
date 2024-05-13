<template>
    <div style="width: 100%">
        <div>
        <div class="display-1">Invoices</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card>
            <v-card-title>
                <!-- <span class="headline">Invoices</span> -->
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
            </v-card-title>
            <v-card-text>
                <v-data-table :headers="headers" :items="invoices" sort-by="id" :options.sync="options"
                    :server-items-length="totalInvoices" :search="search" :loading="loading" class="elevation-1">
                    <template v-slot:item.id="{ item }">
                        <span style="font-weight: bold; cursor: pointer" @click="editItem(item)">#{{ item.id }}</span>
                    </template>
                    <template v-slot:item.patient_id="{ item }">
                        <span>{{ item.patient.name }}</span>
                    </template>
                    <template v-slot:item.status="{ item }">
                        <v-chip color="green" dark v-if="item.status == 1">Paid</v-chip>
                        <v-chip color="red" dark v-if="item.status == 0">Unpaid</v-chip>
                        <v-chip color="orange" dark v-if="item.status == 2">Partial Paid</v-chip>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-btn small fab icon color="primary" @click="editItem(item)"><v-icon
                                small>mdi-pencil</v-icon></v-btn>
                        <v-btn small fab icon color="red" @click="deleteItem(item)"><v-icon
                                small>mdi-delete</v-icon></v-btn>
                        <v-btn small fab icon color="primary" @click="downloadpdf(item.id)"><v-icon
                                small>mdi-download</v-icon></v-btn>

                    </template>
                    <template v-slot:item.amount="{ item }">
                        ₹{{ item.amount }}
                    </template>
                    <template v-slot:item.created_by="{ item }">
                        <div v-if="item.created_by != null">
                            {{ item.created_by.name }}
                        </div>
                        <div v-else>
                            Self By Patient
                        </div>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>
</template>

<script>
import axios from 'axios';
import confirm from '@/components/common/Confirm.vue'
export default {
    components: {
        confirm
    },
    data() {
        return {
            dialog: false,
            dialogDelete: false,
            breadcrumbs: [{
                text: 'Invoices',
                disabled: false,
                to: '/admin/invoices'
            }, {
                text: 'List'
            }],
            options: {},
            loading: false,

            search: '',
            totalInvoices: 0,
            page: 1,
            headers: [{
                text: '#Invoice ID',
                align: 'start',
                value: 'id',
            },
            {
                text: 'User Name',
                sortable: false,
                value: 'user.name',
            },
            {
                text: 'Status',
                value: 'status',
            },
            {
                text: 'Amount',
                value: 'amount',
            },
            {
                text: 'Description',
                value: 'description',
            },
            // {
            //     text: 'Created By',
            //     sortable: false,
            //     value: 'created_by.name',
            // },
            {
                text: 'Created At',
                value: 'created_at',
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false,
            },
            ],
            invoices: [],
            editedIndex: -1,
            editedItem: {
                user_id: '',
                status: '',
                amount: '',
                description: '',
                created_by: '',
            },
            defaultItem: {
                user_id: '',
                status: '',
                amount: '',
                description: '',
                created_by: '',
            },
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Invoice' : 'Edit Invoice'
        },
    },
    watch: {
        options: {
            handler() {
                this.initialize()
            },
            deep: true,
        },
        search: {
            handler() {
                this.getInvoices()
            },
            deep: true,
        },
    },
    methods: {
        initialize() {
            this.loading = true
            this.getInvoices();
        },
        async getInvoices() {
            console.log(this.options);
            await axios.get('/api/admin/invoices/list?page=' + this.options.page + '&limit=' + this.options.itemsPerPage + '&search=' + this.search).then(response => {
                this.invoices = response.data.invoices.data;
                this.totalInvoices = response.data.invoices.total;
                this.loading = false;
            });
        },
        async getDataFromApi() {
            this.loading = true
            await this.fakeApiCall().then(data => { 
                this.invoices = data.items
                this.totalDesserts = data.total
                this.loading = false
            })
        },
        downloadpdf(id) {
            window.open('/api/downloadpdf/' + id);
        },
       
        editItem(item) {
            this.$router.push('/admin/invoice/edit/' + item.id);
        },
        async deleteItem(item) {
            const ok = await this.$refs.confirm.open({
                title: "Delete Invoice",
                message: "Are you sure want to delete invoice?",
                options: {
                    color: "error",
                    width: 290,
                    zIndex: 200,
                },
                icon: "mdi-delete",
            });
            if (ok) {
                axios.post('/api/admin/deleteInvoice/' + item.id).then(response => {
                    if (response.data.success) {
                        this.$toasted.show('Invoice Deleted Succesfully', {
                            type: 'success',
                            duration: 3000
                        });
                        this.initialize();
                    } else {
                        this.$toasted.show('Invoice already paid', {
                            type: 'error',
                            duration: 3000
                        });
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.invoices[this.editedIndex], this.editedItem)
            } else {
                this.invoices.push(this.editedItem)
            }
            this.close()
        },
        deleteItemConfirm() {
            this.invoices.splice(this.editedIndex, 1)
            this.closeDelete()
        }
    }
}
</script>