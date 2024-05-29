<template>
  <div class="d-flex flex-grow-1 flex-column">
    <h1>Welcome to {{ companyname }}</h1>
    <v-row>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Users" class="h-full mt-2" color="#fff" :value="usercount" :percentage="0"
          :percentage-label="''" height="200" pagename="users" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Invoices" class="h-full mt-2" color="#fff" :value="invoiceCount" :percentage="0"
          :percentage-label="''" height="200" pagename="invoices" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Categories" class="h-full mt-2" color="#fff" :value="categoryCount" :percentage="0"
          :percentage-label="''" height="200" pagename="categories" />
      </v-col>
      <v-col cols="12" md="3" lg="3">
        <IconBox label="Total Slots" class="h-full mt-2" color="#fff" :value="slotCount" :percentage="0"
          :percentage-label="''" height="200" pagename="slots" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6" v-if="showchart">
        <v-card>
          <v-card-title>
            Bookings for {{ currentmonth }}
          </v-card-title>
          <v-card-text>
            <apexchart type="area" height="300" :options="chartOptions" :series="series"></apexchart>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="6" v-if="showchart">
        <v-card>
          <v-card-title>
            Revenue for {{ currentmonth }}
          </v-card-title>
          <v-card-text>
            <apexchart type="area" height="300" :options="chartOptions1" :series="series1"></apexchart>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// DEMO Cards for dashboard
import SalesCard from "../../../components/dashboard/SalesCard";
import IconBox from "../../../components/common/IconBox";
import { mapState, mapActions } from "vuex";
import { reactive } from "vue";
export default {
  components: {
    SalesCard,
    IconBox,
  },
  data() {
    return {

      currentmonth: new Date().toLocaleString("default", { month: "long" }),
      loadingInterval: null,
      companyname: JSON.parse(localStorage.getItem("store")).name,
      role: 0,
      usercount: 0,
      invoiceCount: 0,
      categoryCount: 0,
      slotCount: 0,
      bookings: [],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ["created_at"],
        sortDesc: [true],
      },
      showchart: false,
      monthdays: [],
      series: [],
      chartOptions: {},
      series1: [],
      chartOptions1: {},
    };
  },

  mounted() {
    //  this.getBookings();
    this.getUser();
    this.getInvoice();
    this.getCategory();
    this.getSlot();
    this.daySale();
  },
  methods: {
    ...mapActions("app", ["getStoreData"]),
    getUser() {
      axios
        .get("/api/admin/users")
        .then((response) => {
          this.usercount = response.data.users.total;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getInvoice() {
      //console.log(this.monthdays,'monthdays')
      axios
        .get("/api/admin/invoices/list")
        .then((response) => {
          this.invoiceCount = response.data.invoices.total;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getCategory() {
      axios
        .get("/api/admin/category/allcategory")
        .then((response) => {
          this.categoryCount = response.data.categories.total;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getSlot() {
      axios
        .get("/api/admin/slots/all")
        .then((response) => {
          this.slotCount = response.data.slots.total;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    daySale() {
      axios
        .get("/api/admin/bookings/daysale")
        .then((response) => {

          this.dailybooking = response.data.data.dailybooking;
          //console.log(response.data,"all chart")
          var dailyBooked = this.dailybooking.map((daysale) => {
            return daysale.booking_count;
          });
          this.revanue = response.data.data.revanue;
          var dayRevanue = this.revanue.map((rev) => {
            return rev.total_amount;
          });
          var days = this.dailybooking.map((alldate) => {
            return this.formatDate(alldate.date);
          });

          this.series = [
            {
              name: "Booking",
              data: dailyBooked,
            },
          ];
          this.series1 = [{
            name: "Revenue",
            data: dayRevanue,
          }];
          this.chartOptions = {
            xaxis: {
              categories: days,
            },
          };
          this.chartOptions1 = {
            xaxis: {
              categories: days,
            },
          };
          this.showchart = true;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    formatDate(dateString) {
      const date = new Date(dateString);
      if (isNaN(date)) {
        throw new Error("Invalid date format");
      }
      const options = { day: "numeric", month: "long" };
      const formattedDate = date.toLocaleDateString("en-US", options);
      return formattedDate;
    },
    // async getBookings() {
    //   await axios.post('/api/admin/bookings/list', { options: this.options, search: this.search, page: this.options.page })
    //     .then(response => {
    //       this.bookings = response.data.bookings.data;
    //       var chartdata = this.bookings.map((booking) => {
    //         return booking.invoice.amount;
    //       });
    //       this.series = [{
    //         name: 'Amount',
    //         data: chartdata
    //       }];
    //       this.showchart = true;
    //       console.log(this.sparklinedata);
    //     })
    //     .catch(error => {
    //       console.log(error)
    //     })
    // }
  },
};
</script>