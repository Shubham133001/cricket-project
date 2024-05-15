<template>
    <div>

        <v-container>
            <v-sheet rounded class="pa-4">
                <v-row>
                    <v-col cols="12" class="d-flex">
                        <div class="mr-4">
                            <p class=""><strong>Invoice #:</strong>137</p>
                        </div>
                        <div class="mr-4">
                            <p class=""><strong>Invoice Created:</strong>137</p>
                        </div>
                        <div>
                            <p class=""><strong>Due Date:</strong>137</p>
                        </div>
                        <v-spacer></v-spacer>
                        <v-btn color="red" v-if="invoice.status == 0" large outlined class="pl-4 pr-4">Unpaid</v-btn>
                        <v-btn color="green" v-else-if="invoice.status == 1" large outlined
                            class="pl-4 pr-4">Paid</v-btn>
                        <v-btn color="warning" v-else-if="invoice.status == 2" large outlined class="pl-4 pr-4">Partial
                            Paid</v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6">
                        <strong>Invoice From</strong>
                        <p>
                            <strong>{{ storename }}</strong><br>
                            {{ storeaddress }}<br>
                            {{ storecontact }}<br>
                            {{ storeemail }}
                        </p>
                    </v-col>
                    <v-col cols="12" md="6" class="text-right">
                        <strong>Invoice To</strong>
                        <p>
                            <strong>{{ invoice.user.name }}</strong><br>
                            {{ invoice.user.email }}<br>
                            {{ invoice.user.phone }}
                        </p>
                        <div class="d-flex" v-if="invoice.status != 1">
                            <v-select v-model="invoice.gateway" :items="gateways" label="Payment Gateway" outlined
                                dense></v-select><v-btn color="primary" class="ml-4" @click="payinvoice">Pay now</v-btn>
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-simple-table>
                            <template v-slot:default>
                                <thead style="background: #ececec">
                                    <tr>
                                        <th class="text-left">Item</th>
                                        <th class="text-left">Description</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in invoice.items" :key="item.id">
                                        <td>{{ item.slots.title }}</td>
                                        <td>{{ item.description }}</td>
                                        <td class="text-right">{{ item.quantity }}</td>
                                        <td class="text-right">{{ item.price }}</td>
                                    </tr>
                                    <tr class="text-right" style="background: #ececec">
                                        <td class="text-right" colspan="3"></td>
                                        <td class="text-right">
                                            <div class=""><span>
                                                    <strong class="text-h6">Total: </strong>{{ invoice.amount }}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span>
                                                    <strong class="text-h6">Balance: </strong>{{ invoice.balance
                                                    }}</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                </v-row>
                <v-row v-if="invoice.payment.length > 0">
                    <v-col cols="12">
                        <h4>Payments</h4>
                        <v-simple-table>
                            <thead style="background: #ececec">
                                <tr>
                                    <th class="text-left">Transaction ID</th>

                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Transaction Fee</th>
                                    <th class="text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payment in invoice.payment" :key="payment.id">
                                    <td>{{ payment.transactionid }}</td>

                                    <td class="text-right">{{ payment.amount }}</td>
                                    <td class="text-right">0.00</td>
                                    <td class="text-right">{{ payment.created_at }}</td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-col>
                </v-row>
            </v-sheet>
        </v-container>

    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            storename: '',
            storeaddress: '',
            storecontact: '',
            storeemail: '',
           
            invoice: {
                user: {
                    name: '',
                    email: '',
                    phone: ''
                },
                items: [],
                payments: [],
                gateways: [],
                 status: '',
            },
            gateways: [],
        }
    },
    created() {
        this.getStore();
        this.getgateways();
        this.getinvoice();
    },
    methods: {
        async getStore() {
            await axios.get('/api/store').then(response => {
                this.storename = response.data.storeDetails.name;
                this.storeaddress = response.data.storeDetails.address;
                this.storecontact = response.data.storeDetails.contact;
                this.storeemail = response.data.storeDetails.email;
            }).catch(error => {
                console.log(error);
            });
        },
        async getinvoice() {
            await axios.get('/api/getinvoicebyid/' + this.$route.params.id).then(response => {
                this.invoice = response.data.invoice;
                this.showloading = false;
                this.isAdmin = this.$route.path.includes('admin');
                
            }).catch(error => {
                console.log(error);
            });
        },
        async getgateways() {
            await axios.get('/api/getgateways').then(response => {
                this.gateways = response.data.gateways;
            }).catch(error => {
                console.log(error);
            });
        },

        async payinvoice() {
            await axios.post('/api/changegateway', {
                invoice_id: this.invoice.id,
                gateway: this.invoice.gateway
            }).then(response => {
                if (response.data.success) {
                    this.$router.push('/pay/' + this.invoice.id);
                }
            }).catch(error => {
                console.log(error);
            });
        }

    }
}
</script>