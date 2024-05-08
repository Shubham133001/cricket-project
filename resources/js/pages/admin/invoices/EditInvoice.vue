<template>
    <div style="width: 100%">

        <v-card>
            <v-card-title>
                <span class="headline">Edit Invoice</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="downloadpdf">Download PDF <v-icon>mdi-file-pdf</v-icon></v-btn>
            </v-card-title>
            <v-card-text>
                <v-simple-table>
                    <tbody>
                        <tr>
                            <th>
                                Invoice ID
                            </th>
                            <td>
                                #{{ invoice.id }}
                            </td>
                            <th>
                                Invoice Date
                            </th>
                            <td>
                                {{ invoice.created_at }}
                            </td>
                            <th>
                                Balance Due
                            </th>
                            <td>
                                ₹{{ invoice.balance }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <b>User Name:</b>
                            </th>
                            <td>
                                {{ invoice.user.name }}
                            </td>
                            <th>
                                <b>User Phone:</b>
                            </th>
                            <td>
                                {{ invoice.user.phone }}
                            </td>
                            <td>
                                <v-select v-model="invoice.status" @change="updateinvoicestatus" :items="invoicestatus"
                                    label="Status" required></v-select>
                            </td>
                        </tr>

                    </tbody>
                </v-simple-table>

                <v-row>
                    <v-col cols="12" md="12">
                        <h3>Invoice Items:</h3>
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">Slot Description</th>
                                        <th class="text-left">Slot Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in invoice.items" :key="item.id">
                                        <td>{{ item.description }}</td>
                                        <td>₹{{ item.total }}</td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                    <v-col cols="12" md="12" v-if="(invoice.payment != undefined)">
                        <h3>Payment Details:</h3>
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">ID</th>
                                        <th class="text-left">Payment ID</th>
                                        <th class="text-left">Payment Amount</th>
                                        <th class="text-left">Payment Method</th>
                                        <th class="text-left">Payment Date</th>
                                        <th class="text-left">Payment Status</th>
                                        <th class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="payment in invoice.payment" :key="payment.id">
                                        <td>{{ payment.id }}</td>
                                        <td>{{ payment.transactionid }}</td>
                                        <td>₹{{ payment.amount }}</td>
                                        <td>{{ payment.method }}</td>
                                        <td>{{ payment.created_at }}</td>
                                        <td><v-chip color="green" dark v-if="payment.status == 'paid'">Paid</v-chip>
                                            <v-chip color="red" dark v-if="payment.status == 'unpaid'">Unpaid</v-chip>
                                        </td>
                                        <td>
                                            <v-btn color="red" icon fab small @click="deletepayment(payment.id)"><v-icon
                                                    small>mdi-delete</v-icon></v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                    <v-col cols="12" md="12" v-else>
                        <v-select v-model="payment_method" label="Payment Method" :items="payment_methods"
                            required></v-select>
                        <v-text-field v-model="payment_id" :counter="50" label="Payment ID" required></v-text-field>
                        <v-btn color="primary" @click="pay">Mark as Paid</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="cancel">Cancel</v-btn>
            </v-card-actions>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>
</template>
<script>
import axios from 'axios';
import moment from 'moment';
import confirm from '@/components/common/Confirm.vue';
export default {
    components: {
        confirm
    },
    data() {
        return {
            invoice: {
                id: 0,
                user: {},
                amount: 0.00,
                description: '',
                status: 0,
            },
            valid: true,
            payment_id: '',
            payment_method: 'Cash',
            payment_methods: [
                'Cash',
                'UPI',
            ],
            invoicestatus: [{
                text: 'Unpaid',
                value: 0
            },
            {
                text: 'Paid',
                value: 1
            },
            {
                text: 'Partial Paid',
                value: 2
            },
            ]
        }
    },
    methods: {
        async invoiceData() {
            const id = this.$route.params.id;
            await axios.get('/api/getinvoicebyid/' + id).then(response => {
                this.invoice = response.data.invoice;
                this.invoice.created_at = moment(this.invoice.created_at).format('DD-MM-YYYY, h:mm:ss a');
                let todaydate = moment().format('DD-MM-YYYY, h:mm:ss a');
                this.payment_id = '' + this.invoice.user.name + ' on ' + todaydate;



            }).catch(error => {
                console.log(error);
            });
        },
        async updateinvoicestatus() {
            const id = this.$route.params.id;
            await axios.post('/api/admin/invoices/update', {
                status: this.invoice.status,
                id: id
            }).then(response => {
                if (response.data.success) {
                    this.$toasted.success(response.data.message, {
                        duration: 2000,
                        type: 'success'
                    });
                }
                else {
                    this.$toasted.error(response.data.message, {
                        duration: 2000,
                        type: 'error'
                    });
                }
                this.invoiceData();
            }).catch(error => {
                console.log(error);
            });
        },
        async deletepayment(id) {
            const ok = await this.$refs.confirm.open({
                title: 'Are you sure?',
                message: 'This will delete the payment transaction',
                okButtonText: 'Yes',
                cancelButtonText: 'No'
            });
            if (!ok) return;
            axios.post('/api/admin/deletetransaction/' + id).then(response => {
                this.invoiceData();
            }).catch(error => {
                console.log(error);
            });
        },

        cancel() {
            this.$router.push('/admin/invoices');
        },
        downloadpdf() {
            window.open('/admin/invoice/downloadpdf/' + this.invoice.id, '_blank');
        },
        pay() {
            const id = this.$route.params.id;
            // Determine the source of payment initiation
            const source = this.source;
            axios.post('/api/admin/invoices/payinvoice', {
                id: id,
                payment_id: this.payment_id,
                method: this.payment_method,
                user_id: this.invoice.user.id,
                currency: 'INR',
                amount: this.invoice.balance,

            }).then(response => {
                const redirect = localStorage.getItem('redirect');
                this.$router.push(redirect);
            }).catch(error => {
                console.log(error);
            });
        },
    },
    created() {
        this.invoiceData();
    }
}
</script>