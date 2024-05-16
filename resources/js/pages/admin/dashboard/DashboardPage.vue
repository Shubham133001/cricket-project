<template>
  <div class="d-flex flex-grow-1 flex-column">
    <h1>Welcome to {{ companyname }}</h1>
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
      companyname: JSON.parse(localStorage.getItem('store')).name,
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
    // this.getBillingOverview();
    // this.gettodayAppointments();
    // this.getTransactions();

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