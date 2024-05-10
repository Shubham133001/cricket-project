<template>
    <div style="width: 100%">
        <div>
        <div class="display-1">Today Transactions</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card>
            <v-card-title>
               
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="success" @click="exporttocsv" class="mr-6">
                    Export CSV <i class="mdi mdi-export-variant" aria-hidden="true"></i>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table :headers="headers" :items="transactions" :search="search" :loading="loading"
                    :items-per-page="10" class="elevation-1">

                    <template v-slot:item.user.name="{ item }">
                        <span style="font-weight: bold; cursor: pointer" @click="openClient(item.user)">{{
                    item.user.name }}</span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon color="error" @click="deleteItem(item)">mdi-delete</v-icon>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>
</template>

<script>
import axios from 'axios';
import confirm from '../../components/common/Confirm.vue'
export default {
    components: {
        confirm
    },
    data() {
        return {
            transactions: [],
            loading: false,
            breadcrumbs: [{
                text: 'Transactions',
                disabled: false,
                to: '/admin/todaytransactions'
            }, {
                text: 'List'
            }],
            search: '',
            dates: [],
            menu: false,
            closeonclick: false,
            headers: [{
                text: 'ID',
                align: 'start',
                value: 'id'
            }, {
                text: 'User',
                align: 'start',
                value: 'user.name',
                sortable: false
            }, {
                text: 'Payment ID',
                value: 'transactionid',
                 sortable: false
            },
            {
                text: 'Phone Number',
                value: 'user.phone',
                 sortable: false
            },
            {
                text: 'Amount',
                value: 'amount',
                 sortable: false
            },
            {
                text: 'Status',
                value: 'status',
                 sortable: false
                
            },
            {
                text: 'Method',
                value: 'method',
                 sortable: false
            },
            {
                text: 'Payment Date',
                value: 'created_at',
                 sortable: false
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false,
            },
            ],
        }
    },
    mounted() {
        this.getTransactions();
    },
    methods: {

        getTransactions() {
            this.loading = true;
            this.menu = false;
            console.log(this.dates.length);
            if (this.dates.length == 0) {
                axios.get('/api/admin/gettodaypayments')
                    .then(response => {
                        this.transactions = response.data.payments;
                        this.loading = false;
                    })
                    .catch(error => {
                        if (error.response.status == 403) {
                            this.$router.push("/admin/unauthorized");
                        }
                        console.log(error);

                    });
            } else {
                axios.get('/api/admin/gettodaypayments/?dates=' + this.dates)
                    .then(response => {
                        this.transactions = response.data.payments;
                        this.loading = false;
                    })
                    .catch(error => {
                        if (error.response.status == 403) {
                            this.$router.push("/admin/unauthorized");
                        }
                        this.loading = false;
                    });
            }
            this.loading = false;
        },
        async deleteItem(item) {
            const ok = await this.$refs.confirm.open({
                title: "Delete transaction",
                message: "Are you sure want to delete transaction ?",
                options: {
                    color: "error",
                    width: 290,
                    zIndex: 200,
                },
                icon: "mdi-delete",
            });
            if (ok) {
                axios.post('/api/admin/deletetransaction/' + item.id).then(response => {
                    if (response.data.success) {
                        this.$toasted.show(response.data.message, {
                            type: 'success',
                            duration: 3000
                        });
                        this.getTransactions();
                    } else {
                        this.$toasted.show(response.data.message, {
                            type: 'error',
                            duration: 3000
                        });
                    }
                }).catch(error => {
                    console.log(error);
                    this.$toasted.show(response.data.message, {
                        type: 'error',
                        duration: 3000
                    });
                });
            }
        },
        exporttocsv() {
            this.loading = true;
            // export json to csv from datatable
            const items = this.transactions

            const desiredOrder = [
                'Payment ID',
                'user Name',
                'User Phone',
                'Method',
                'Amount',
                'Payment Date',
            ];
            const keyMapping = {
                'transactionid': 'Payment ID',
                'amount': 'Amount',
                'status': 'Status',
                'method': 'Method',
                'user_phone': 'User No',
                'created_at': 'Payment Date',
                'name': 'user Name',
            };
            const filteredItems = items.map(item => {
                const newItem = {};
                desiredOrder.forEach(desiredKey => {
                    for (const originalKey in keyMapping) {
                        if (keyMapping[originalKey] === desiredKey) {
                            if (originalKey === 'name' && item.user) {
                                newItem[desiredKey] = item.user.name;
                            } else if (originalKey === 'user_phone' && item.user) {
                                newItem[desiredKey] = item.user.phone;

                            } else {
                                newItem[desiredKey] = item[originalKey];
                            }
                        }
                    }
                });
                return newItem;
            });

            if (filteredItems.length === 0) {
                this.loading = false;
                return; // No data to export
            }
            this.loading = false;
            const replacer = (key, value) => value === null ? '' : value // specify how you want to handle null values here
            const header = Object.keys(filteredItems[0])
            let csv = filteredItems.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','))
            csv.unshift(header.join(','))
            csv = csv.join('\r\n')

            // download generated csv file
            const blob = new Blob([csv], {
                type: 'text/csv'
            })
            const data = window.URL.createObjectURL(blob)
            let link = document.createElement('a')
            link.href = data
            link.download = 'transactions.csv'
            link.click()

        },
    }
}
</script>