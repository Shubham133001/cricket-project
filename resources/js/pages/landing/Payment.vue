<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12" md="6" offset-md="3">
                    <v-card :loading="showloading">
                        <v-card-title>
                            <h2 class="text-center">Payment</h2>
                        </v-card-title>
                        <v-card-text>
                            <div class="text-center">
                                <v-btn color="primary" class="mt-3" @click="sendToPay" :loading="showloading"
                                    :disabled="showloading">Pay now</v-btn>
                            </div>
                        </v-card-text>
                        <div class="text-center mt-3 mb-2" v-if="isAdmin == false">
                            <router-link to="/" class="btn btn-primary mb-2">Back to Home Page</router-link>
                        </div>
                        <div class="text-center mt-3" v-else>
                            <router-link to="/admin/appointments" class="btn btn-primary">Back to
                                Appointments</router-link>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
export default {
    data() {
        return {
            // check if url contains admin

            showloading: true,
            isAdmin: false,
            invoice: {
                id: '',
                gateway: '',
            },
            iframeurl: '',
            showpaymentbox: false
        }
    },
    created() {
        // this.getAppointment();
        this.getinvoice();
    },
    methods: {
        async getinvoice() {
            await axios.get('/api/user/editinvoice/' + this.$route.params.id).then(response => {
                this.invoice = response.data.invoice;
                this.showloading = false;
                this.sendToPay();
            });
        },
        printInvoice() {
            window.open('/admin/downloadReceipt/' + this.appointment.id, '_blank');
        },

        sendToPay() {
            this.showloading = true;
            let gateway = this.invoice.gateway;
            if (gateway == null) {
                this.showloading = false;
                this.$toast.error('Payment Gateway Not Selected. Please select a payment gateway');
            } else {
                axios.post('/api/user/pay', {
                    invoiceid: this.$route.params.id,
                    gateway: gateway
                }).then(response => {
                    if (response.data.success) {
                        window.location.href = response.data.data.original.url;

                    } else {
                        this.$toast.error(response.data.message);
                    }
                    this.showloading = false;
                });
            }

        }
    }
}
</script>