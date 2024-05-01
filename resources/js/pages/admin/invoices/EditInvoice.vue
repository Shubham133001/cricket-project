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
                        </tr>
                        <tr>
                            <th>
                                <b>Patient Name:</b>
                            </th>
                            <td>
                                {{ invoice.user.name }}
                            </td>
                            <th>
                                <b>Patient Phone:</b>
                            </th>
                            <td>
                                {{ invoice.user.phone }}
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
                                    <tr>
                                        <td>{{ invoice.description }}</td>
                                        <td>₹{{ invoice.amount }}</td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                    <v-col cols="12" md="12" v-if="invoice.payment != null">
                        <h3>Payment Details:</h3>
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">ID</th>
                                        <th class="text-left">Payment ID</th>
                                        <th class="text-left">Payment Amount</th>
                                        <th class="text-left">Payment Date</th>
                                        <th class="text-left">Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ invoice.payment.id }}</td>
                                        <td>{{ invoice.payment.payment_id }}</td>
                                        <td>₹{{ invoice.payment.amount }}</td>
                                        <td>{{ invoice.payment.created_at }}</td>
                                        <td><v-chip color="green" dark
                                                v-if="invoice.payment.status == 'Paid'">Paid</v-chip>
                                            <v-chip color="red" dark
                                                v-if="invoice.payment.status == 'Unpaid'">Unpaid</v-chip>
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

    </div>
</template>
<script>
import axios from 'axios';
import moment from 'moment';
export default {
    data() {
        return {
            invoice: {
                patient: {},
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
            axios.post('/api/admin/payinvoice', {
                id: id,
                payment_id: this.payment_id,
                method: this.payment_method,
                patient_phonenumber: this.invoice.patient.phonenumber,
                doctor_id: this.invoice.doctor_id,
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