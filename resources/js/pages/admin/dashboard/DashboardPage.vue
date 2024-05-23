<template>
  <div class="d-flex flex-grow-1 flex-column">
    <h1>Welcome to {{ companyname }}</h1>
    <v-row>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Users" class="h-full mt-2" color="#fff" :value="usercount" :percentage="0"
          :percentage-label="''" height="200" pagename="users"  />
          
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Invoices" class="h-full mt-2" color="#fff" :value="invoiceCount" :percentage="0"
          :percentage-label="''" height="200" pagename="invoices"  />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Categories" class="h-full mt-2" color="#fff" :value="categoryCount" :percentage="0"
          :percentage-label="''" height="200" pagename="categories"  />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Slots" class="h-full mt-2" color="#fff" :value="slotCount" :percentage="0"
          :percentage-label="''" height="200" pagename="slots"  />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="12" v-if="showchart">

        <apexchart type="area" height="300" :options="chartOptions" :series="series"></apexchart>
      </v-col>

    </v-row>

  </div>
</template>

<script>
// DEMO Cards for dashboard
import SalesCard from '../../../components/dashboard/SalesCard'
import IconBox from '../../../components/common/IconBox'
import {
  mapState,
  mapActions
} from 'vuex';
import { reactive } from 'vue';
export default {
  components: {
    SalesCard,
    IconBox
  },
  data() {
    return {
      loadingInterval: null,
      companyname: JSON.parse(localStorage.getItem('store')).name,
      role: 0,
      usercount:0,
      invoiceCount:0,
      categoryCount:0,
      slotCount:0,
      bookings: [],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ['created_at'],
        sortDesc: [true]
      },
      showchart: false,

      series: [],
      chartOptions: {
        chart: {
          id: 'vuechart-example'
        },
        xaxis: {
          categories: [1000, 1500, 2000, 2500, 3000, 3500, 4000, 5000, 6000, 7000]
        },
      }
    }
  },
  beforeMount() {
    // this.getAdminRoles();
  },
  mounted() {
    this.getBookings();
    this.getUser();
    this.getInvoice();
    this.getCategory();
    this.getSlot();

  },
  methods: {
    ...mapActions('app', ['getStoreData']),
    getUser(){
       axios.get('/api/admin/users')
        .then(response => {
          this.usercount = response.data.users.total;
        })
        .catch(error => {
           console.log(error)
        })
    },

    getInvoice(){
      axios.get('/api/admin/invoices/list')
        .then(response => {
          this.invoiceCount = response.data.invoices.total;
        })
        .catch(error => {
           console.log(error)
        })
    },

    getCategory(){
      axios.get('/api/admin/category/allcategory')
        .then(response => {
          this.categoryCount = response.data.categories.total;
        })
        .catch(error => {
           console.log(error)
        })
    },

    getSlot(){
      axios.get('/api/admin/slots/all')
        .then(response => {
          this.slotCount = response.data.slots.total;
        })
        .catch(error => {
           console.log(error)
        })
    },
    async getBookings() {
      await axios.post('/api/admin/bookings/list', { options: this.options, search: this.search, page: this.options.page })
        .then(response => {
          this.bookings = response.data.bookings.data;
          var chartdata = this.bookings.map((booking) => {
            return booking.invoice.amount;
          });
          this.series = [{
            name: 'Amount',
            data: chartdata
          }];
          this.showchart = true;
          console.log(this.sparklinedata);
        })
        .catch(error => {
          console.log(error)
        })
    }

  }
}
</script>