<template>
  <div class="d-flex flex-grow-1 flex-column">

    <v-row class="flex-grow-0" dense>
      <v-col cols="4" v-if="role == 1">
        <v-card :loading="isLoadingOverview">
          <v-card-title>
            <span class="text-h5">Billing Overview</span>
          </v-card-title>
          <v-card-text>
            <div class="d-flex">
              <v-col cols="12" md="6" class="elevation-2 pa-0">
                <v-card-title class="text-h6">Today</v-card-title>
                <v-card-text class="text-h4">₹{{totalToday}} Rs.</v-card-text>
              </v-col>
              <v-col cols="12" md="6" class="elevation-2 pa-0">
                <v-card-title class="text-h6">This Month</v-card-title>
                <v-card-text class="text-h4">₹{{totalThisMonth}} Rs.</v-card-text>
              </v-col>
            </div>
            <div class="d-flex">
              <v-col cols="12" md="6" class="elevation-2 pa-0">
                <v-card-title class="text-h6">This Year</v-card-title>
                <v-card-text class="text-h4">₹{{totalThisYear}} Rs.</v-card-text>
              </v-col>
              <v-col cols="12" md="6" class="elevation-2 pa-0">
                <v-card-title class="text-h6">All Time</v-card-title>
                <v-card-text class="text-h4">₹{{totalAllTime}} Rs.</v-card-text>
              </v-col>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
      <template v-if="role == 1">
        <v-col cols="12" md="8">
          <v-card :loading="isLoadingAppointments">
            <v-card-title>
              <span class="text-h5">Today's Appointments</span>
              <v-spacer></v-spacer>
              <v-btn small color="primary" @click="$router.push({name: 'admin-appointments'})">View All</v-btn>
            </v-card-title>

            <v-data-table :headers="appointmrntsHeaders" :items="todayAppointments" :items-per-page="5" class="elevation-1">
            <template v-slot:item.patient.name="{ item }">
                <span style="font-weight: bold; cursor: pointer" @click="editPatient(item.patient.id)">#{{ item.patient.id }} {{ item.patient.name }}</span>
                <span>{{item.patient.phonenumber}}</span>
              </template>
              <template v-slot:item.booking_date="{ item }">
                <span>{{ item.booking_date }} {{ item.time_slot }}</span>
              </template>
                
            <template v-slot:item.status="{ item }">
                <v-chip v-if="item.status == 0" small color="primary" text-color="white">Not-Confirmed</v-chip>
                <v-chip v-if="item.status == 1" small color="success" text-color="white">Waiting</v-chip>
                <v-chip v-if="item.status == 2" small color="teal" text-color="white">Engaged</v-chip>
                <v-chip v-if="item.status == 3" small color="green" text-color="white">Checked Out</v-chip>
                <v-chip v-if="item.status == 4" small color="error" text-color="white">Cancelled</v-chip>
              </template>
              <template v-slot:item.action="{ item }">
                <v-icon small color="primary" icon fab dense class="mr-2" @click="editAppointment(item.id)">mdi-pencil</v-icon>
              </template>
            </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </template>
      <template v-else> 
        <v-col cols="12" md="12">
          <v-card :loading="isLoadingAppointments">
            <v-card-title>
              <span class="text-h5">Today's Appointments</span>
              <v-spacer></v-spacer>
              <v-btn small color="primary" @click="$router.push({name: 'admin-appointments'})">View All</v-btn>
            </v-card-title>

            <v-data-table :headers="appointmrntsHeaders" :items="todayAppointments" :items-per-page="5" class="elevation-1">
            <template v-slot:item.patient.name="{ item }">
                <span style="font-weight: bold; cursor: pointer" @click="editPatient(item.patient.id)">#{{ item.patient.id }} {{ item.patient.name }}</span>
                <span>{{item.patient.phonenumber}}</span>
              </template>
              <template v-slot:item.booking_date="{ item }">
                <span>{{ item.booking_date }} {{ item.time_slot }}</span>
              </template>
                
            <template v-slot:item.status="{ item }">
                <v-chip v-if="item.status == 0" small color="primary" text-color="white">Not-Confirmed</v-chip>
                <v-chip v-if="item.status == 1" small color="success" text-color="white">Waiting</v-chip>
                <v-chip v-if="item.status == 2" small color="teal" text-color="white">Engaged</v-chip>
                <v-chip v-if="item.status == 3" small color="green" text-color="white">Checked Out</v-chip>
                <v-chip v-if="item.status == 4" small color="error" text-color="white">Cancelled</v-chip>
              </template>
              <template v-slot:item.action="{ item }">
                <v-icon small color="primary" icon fab dense class="mr-2" @click="editAppointment(item.id)">mdi-pencil</v-icon>
              </template>
            </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>

      </template>
      
      <v-col cols="12" md="12">
        <v-card :loading="isLoadingTransactions">
          <v-card-title>
            <span class="text-h5">Transactions</span>
          </v-card-title>
          <v-card-text>
            <v-data-table :headers="trabsactionsHeader" :items="transactions" :items-per-page="5" class="elevation-1">
            <template v-slot:item.patient.name="{ item }">
              <span style="font-weight: bold; cursor: pointer" @click="editPatient(item.patient.id)">#{{ item.patient.id }} {{ item.patient.name }}</span>
            </template>
              <template v-slot:item.status="{ item }">
                <v-chip :color="item.status == 'Unpaid' ? 'error' : 'success'" dark small>{{item.status}}</v-chip>
              </template>
              
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
  // DEMO Cards for dashboard
  import SalesCard from '../../../components/dashboard/SalesCard'
  import {
    mapState,
    mapActions
  } from 'vuex';
  export default {
    components: {
      SalesCard
    },
    data() {
      return {
        loadingInterval: null,

        isLoadingOverview: true,
        isLoadingTransactions: true,
        isLoadingAppointments: true,
        role: 0,
        totalToday: 0,
        totalThisMonth: 0,
        totalThisYear: 0,
        totalAllTime: 0,
        todayAppointments: [],
        transactions: [],
        appointmrntsHeaders: [{
            text: 'Patient Name',
            align: 'start',
            sortable: false,
            value: 'patient.name'
          },
          {
            text: 'Doctor Name',
            value: 'doctor.name'
          },
          {
            text: 'Booking',
            value: 'booking_date'
          },
          {
            text: 'Payment',
            value: 'invoice.payment.status'
          },
          {
            text: 'Status',
            value: 'status'
          },
          {
            text: 'Action',
            value: 'action',
            sortable: false
          }
        ],
        trabsactionsHeader: [{
            text: 'Pateint',
            align: 'start',
            value: 'patient.name',
            sortable: false
          }, {
            text: 'Payment ID',
            value: 'payment_id',
          },
          {
            text: 'Patient Phone Number',
            value: 'patient_phonenumber'
          },
          {
            text: 'Order ID',
            value: 'order_id'
          },
          {
            text: 'Amount',
            value: 'amount'
          },
          {
            text: 'Currency',
            value: 'currency'
          },
          {
            text: 'Status',
            value: 'status'
          },
          {
            text: 'Method',
            value: 'method'
          },
          {
            text: 'Payment Date',
            value: 'created_at'
          },

        ]
      }
  },
  beforeMount() {
      this.getAdminRoles();  
    },
    mounted() {
      this.getBillingOverview();
      this.gettodayAppointments();
      this.getTransactions();
     
    },
    methods: {
      ...mapActions('app', ['getStoreData']),

      getAdminRoles() {

        axios.get('/api/admin/getcurretadmingroup')
          .then(response => {
            this.role = response.data.data.admin_group_id;
            console.log(response.data.data.admin_group_id);
          })
        .catch(error => {
          console.log(error)
        })
      },
      getBillingOverview() {
        axios.get('/api/admin/getpaymentsoverview')
          .then(response => {
            this.isLoading1 = false
            if (response.data.success) {
              this.totalAllTime = response.data.data.allpaymentsAmount;
              this.totalThisYear = response.data.data.thisyearAmount;
              this.totalThisMonth = response.data.data.thismonthAmount;
              this.totalToday = response.data.data.todayAmount;
            }
          })
          .catch(error => {
            console.log(error)
          })
          this.isLoadingOverview = false;
      },
      gettodayAppointments() {
        axios.get('/api/admin/gettodaywaittingappointments')
          .then(response => {
            this.isLoading1 = false
            if (response.data.success) {
              this.todayAppointments = response.data.records.data;
            }
          })
          .catch(error => {
            console.log(error)
          })
          this.isLoadingAppointments = false;
      },
      getTransactions() {
        axios.get('/api/admin/getpayments?limit=10')
          .then(response => {
            this.isLoading1 = false
            if (response.data.success) {
              this.transactions = response.data.data;
            }
          })
          .catch(error => {
            console.log(error)
          })
          this.isLoadingTransactions = false;
      },
      editPatient($id) {
        this.$router.push({
          name: 'admin-edit-patient',
          params: {
            id: $id
          }
        })
      },
      editAppointment($id) {
        this.$router.push({
          name: 'admin-appointment-edit',
          params: {
            id: $id
          }
        })
      },

    }
  }
</script>