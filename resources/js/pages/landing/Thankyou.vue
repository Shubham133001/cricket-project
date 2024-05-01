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
                            <v-icon color="success" class="mb-3" size="100">mdi-check-circle</v-icon>
                            <h1>#{{ appointment.id }} Appointment Booked Successfully</h1>

                        </div>
                        <div class="pa-2 elevation-1 mt-3" style="max-width: 450px; margin: auto; border: solid 1px #efefef; background: #f8f8f8; font-size: 18px; text-align: center;">
                            <p style="border-bottom: solid 2px #a8a8a8; font-weight: 600" class="pb-1">Your Booking Details are as below.</p>


                            <p><strong>Appointment Date:</strong> {{ appointment.booking_date }} {{ appointment.time_slot }}</p>

                            <p><strong>Doctor Name:</strong> {{ appointment.doctor.name }}</p>
                            <p><strong>Patient Name:</strong> {{ appointment.patient.name }}</p>
                            <p><strong>Patient Phone Number:</strong> {{ appointment.patient.phonenumber }}</p>
                            <p><strong>Your Booking Status:</strong>
                                <v-chip class="ma-0" small color="primary" v-if="appointment.status == '0'">Waiting</v-chip>
                                <v-chip class="ma-0" small color="success" v-if="appointment.status == '1'">Confirmed</v-chip>
                                <v-chip class="ma-0" small color="teal" v-if="appointment.status == '2'">Engaged</v-chip>
                                <v-chip class="ma-0" small color="success" v-if="appointment.status == '3'">Checkedout</v-chip>
                                <v-chip class="ma-0" small color="errro" v-if="appointment.status == '4'">Cancelled</v-chip>
                            </p>
                            <p><strong>Your Payment Status:</strong> <v-chip class="ma-0" small color="success">Paid</v-chip></p>
                            <p><strong>Payment ID:</strong> {{ appointment.invoice.payment.payment_id }}</p>
                        </div>
                        <h3 class="mt-2 text-center">Thank you for your Booking </h3>
                        <div class="text-center"><v-btn color="primary" class="mt-3" @click="printInvoice">Download PDF <v-icon>mdi-file-pdf</v-icon></v-btn></div>
                    </v-card-text>
                    <div class="text-center mt-3 mb-2" v-if="isAdmin == false">
                        <router-link to="/" class="btn btn-primary mb-2">Back to Home Page</router-link>
                    </div>
                    <div class="text-center mt-3" v-else>
                        <router-link to="/admin/appointments" class="btn btn-primary">Back to Appointments</router-link>
                    </div>

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

                showloading: true,
                isAdmin: false,
                appointment: {
                    id: 1,
                    patient: {
                        name: 'John Doe'
                    },
                    booking_date: '2021-01-01',
                    time_slot: '04:30:00 - 05:00:00',
                    doctor: {
                        name: 'Dr. John Doe'
                    }
                }
            }
        },
        created() {
            this.getAppointment();
        },
        methods: {
            async getAppointment() {
                this.isAdmin = this.$route.path.includes('admin');

                var appointmentid = this.$route.params.id;
                await axios.get('/api/appointments/getappointment/' + appointmentid + '').then(response => {
                    this.appointment = response.data.data;
                    this.appointment.booking_date = moment(this.appointment.booking_date).format('DD-MM-YYYY');
                    this.showloading = false;
                });
            },
            printInvoice() {
                window.open('/admin/downloadReceipt/' + this.appointment.id, '_blank');
            },
            sendToPay() {
                this.$router.push('/admin/invoices/add/' + this.appointment.id);
            }
        }
    }
</script>