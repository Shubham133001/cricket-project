<template>
  <div class="d-flex flex-grow-1 flex-column">
    <h1>Welcome to {{ companyname }}</h1>
    <v-row>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Clients" class="h-full mt-2" color="#fff" :value="10" :percentage="0"
          :percentage-label="''" height="200" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Clients" class="h-full mt-2" color="#fff" :value="10" :percentage="0"
          :percentage-label="''" height="200" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Clients" class="h-full mt-2" color="#fff" :value="10" :percentage="0"
          :percentage-label="''" height="200" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Clients" class="h-full mt-2" color="#fff" :value="10" :percentage="0"
          :percentage-label="''" height="200" />
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

  },
  methods: {
    ...mapActions('app', ['getStoreData']),

    getAdminRoles() {

      axios.get('/api/admin/getcurretadmingroup')
        .then(response => {
          this.role = response.data.data.admin_group_id;
          // console.log(response.data.data.admin_group_id);
        })
        .catch(error => {
          // console.log(error)
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