<template>
    <div style="width: 100%">
        <v-card>
            <v-card-title>
                <span class="headline">Add Invoice</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="8">
                        <v-form ref="form" v-model="valid" lazy-validation>
                        <!-- <v-autocomplete v-model="doctor_id" :items="doctors" item-text="name" item-value="id" label="Doctor" outlined dense></v-autocomplete> -->
                        <v-autocomplete v-model="patient" :items="patients" item-text="name" item-value="id" label="Patient" :rules="[rules.required]"  outlined dense return-object></v-autocomplete>
                        <v-autocomplete v-model="service" :items="services" item-text="name" item-value="id" label="Service" :rules="[rules.required]"  outlined dense return-object></v-autocomplete>
                        <v-text-field v-model="priceoverride" label="Price Overrider" outlined dense></v-text-field>
                        <!-- <v-select v-model="status" :items="statuses" return-object item-text="name" item-value="id" label="Status" outlined dense></v-select> -->
                    </v-form>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Invoice Details</span>
                            </v-card-title>
                            <v-card-text>
                                <p style="font-size: 18px; line-height: 25px;">
                                    <b>Patient Name:</b> {{ patient != null ? patient.name : '' }}<br>
                                    <b>Patient Phone:</b> {{ patient != null ? patient.phonenumber : '' }}<br>
                                    <b>Patient address:</b> {{ patient != null ? patient.address : '' }}<br><br />
                                    <b>Service Name:</b> {{ service != null ? service.name : '' }}<br>
                                    <b>Service Price:</b> ₹{{ service != null ? service.price : '' }}<br><br>
                                    <b>Status:</b> Unpaid<br>
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="primary" @click="addInvoice">Add Invoice</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                patient: null,
                service: null,
                valid: true,
                status: '',
                patients: [],
                services: [],
                doctors: [],
                doctor_id: null,
                payment_id: null,
                priceoverride: '',
                // payment_method: '',
                statuses: [{
                        id: 0,
                        name: 'Unpaid'
                    },
                    
                ],
                payment_methods: [{
                        id: 0,
                        name: 'Cash'
                    },
                    {
                        id: 1,
                        name: 'UPI'
                    }
                ],
                rules: {
                    required: value => !!value || 'Required.',
                },
            }
        },
        methods: {
            addInvoice() {
                if (this.$refs.form.validate()) {
                    if(this.priceoverride != '') {
                        this.service.price = this.priceoverride;
                    }
                    axios.post('/api/createInvoice', {
                        patient_id: this.patient.id,
                        status: 0,
                        amount: this.service.price,
                        description: this.service.name,
                        created_by: JSON.parse(atob(localStorage.getItem('adminData'))).user.id,
                        payment_id: this.payment_id,
                        doctor_id: this.doctor_id,
                        priceoverride: this.priceoverride,
                    }).then(res => {
                        const $invoiceid = res.data.invoices.id;
                        localStorage.removeItem('redirect');
                        localStorage.setItem('redirect', '/admin/invoices');
                        this.$router.push('/admin/invoice/edit/' + $invoiceid + '');
                    }).catch(err => {
                        console.log(err)
                    })
                }else{

                    for (
                    let index = 0;
                        index < Object.keys(this.$refs.form.inputs).length;
                        index++
                    ) {
                        if (
                            this.$refs.form.inputs[index].hasError == true &&
                            this.$refs.form.inputs[index].type != undefined
                        ) {
                            this.$refs.form.inputs[index].focus();
                            break;
                        }
                    }
                }
                
            },
            getPatients() {
                console.log(JSON.parse(atob(localStorage.getItem('adminData'))).user.id);
                axios.get('/api/admin/getPatients').then(res => {
                    this.patients = res.data.data
                }).catch(err => {
                    console.log(err)
                })
            },
            getServices() {
                axios.get('/api/getallservices').then(res => {
                    this.services = res.data.services
                }).catch(err => {
                    console.log(err)
                })
            },
            // get all doctors
            getDoctors() {
                axios.get('/api/getalldoctors').then(res => {
                    this.doctors = res.data.doctors
                    if (this.doctors.length > 0) {
                        this.doctor_id = this.doctors[0].id
                    }
                   
                }).catch(err => {
                    console.log(err)
                })
            },  
        },
        clear(e) {
            this.$refs.form.reset();
        },
    mounted() {
            this.getDoctors()
            this.getPatients()
            this.getServices()
        }
    }
</script>