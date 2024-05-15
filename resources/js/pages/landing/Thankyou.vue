<template>
    <div style="width: 100%;">
        <v-row>
            <v-col cols="12" md="12" class="pt-0">
                <v-card>
                    <v-card-text v-if="showloading">
                        <br /><br />
                        <div class="text-center" style="margin: 0 auto !important">
                            <v-progress-circular :size="200" color="primary" indeterminate>Please wait.... </v-progress-circular><br /><br />
                        </div>
                    </v-card-text>
                    <v-card-text v-else>

                        <div class="text-center" style="max-width: 550px; margin: auto;">
                            <v-icon v-if="appointment.status == 'Approved'" color="success" class="mb-3" size="100">mdi-check-circle</v-icon>
                            <v-icon v-if="appointment.status == 'Pending'" color="error" class="mb-3" size="100">mdi-close-circle</v-icon>
                            <h1>Slot Booked Successfully</h1>

                        </div>
                        <div class="pa-2 elevation-1 mt-3" style="max-width: 450px; margin: auto; border: solid 1px #efefef; background: #f8f8f8; font-size: 18px; text-align: center;">
                            <p style="border-bottom: solid 2px #a8a8a8; font-weight: 600" class="pb-1">Your Booking Details are as below.</p>


                            <p><strong>Booking  Date:</strong> {{ appointment.booking_date }}</p>
                            <p><strong>Booking  Slot Time:</strong>  {{ appointment.time_slot }}</p>
                            <p><strong>Your Booking Status:</strong>
                                <v-chip class="ma-0" small color="warning" v-if="appointment.status == 'Pending'">Pending</v-chip>
                                <v-chip class="ma-0" small color="success" v-if="appointment.status == 'Approved'">Approved</v-chip>
                                <v-chip class="ma-0" small color="errro" v-if="appointment.status == 'Cancelled'">Cancelled</v-chip>
                            </p>
                            <p><strong>Your Payment Status:</strong> 
                            <v-chip class="ma-0" small color="warning" v-if="appointment.payment_status == 'Pending'">{{ appointment.payment_status }}</v-chip>
                            <v-chip class="ma-0" small color="success" v-if="appointment.payment_status == 'Paid'">{{ appointment.payment_status }}</v-chip>
                            <v-chip class="ma-0" small color="errro" v-if="appointment.payment_status == 'Cancelled'">{{ appointment.payment_status }}</v-chip>
                            </p>
                            <!-- <p><strong>Payment ID:</strong> {{ appointment.invoice.payment.payment_id }}</p> -->
                        </div>
                        <h3 class="mt-2 text-center">Thank you for your Booking </h3>
                        <div class="text-center"><v-btn color="primary" class="mt-3" @click="viewInvoice">View Invoice </v-btn></div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
    import moment from 'moment';
    export default {
        data() {
            return {
                // check if url contains admin

                showloading: false,
               // isAdmin: false,
                appointment: {
                    id: 1,
                    status: "",
                    booking_date: '2021-01-01',
                    time_slot: '04:30:00 - 05:00:00',
                    payment_status: '',
                }
            }
        },
        mounted() {
            this.getAppointment();
        },
        methods: {
             getAppointment() {
                var invoiceid = this.$route.params.id;
                 axios.get('/api/user/bookingdetails?id='+ invoiceid).then(response => {
                    console.log(response.data);
                    this.appointment.booking_date = response.data.bookings.date;
                    this.appointment.time_slot = response.data.bookings.slot.start_time + "-" +response.data.bookings.slot.end_time;
                    this.appointment.status = response.data.bookings.status;
                    this.appointment.payment_status = response.data.bookings.payment_status;
                });
            },
            viewInvoice() {
                var invoiceid = this.$route.params.id;
                this.$router.push({
                    path: '/invoice/'+invoiceid,
                });
            },
        }
    }
</script>