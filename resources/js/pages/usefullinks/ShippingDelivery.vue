<template>
  <div>
    <v-row style="display: flex;justify-content: center;align-items: center;">
      <v-col cols="12" md="8">

        <h2 style="text-align: center;color: #0096c7;margin-bottom: 25px;">Shipping and Delivery Policy of {{
          storeDetails.name }}</h2>

        <p>We want to clarify that {{
          storeDetails.name }} is an appointment-based website for medical services, and as such,
          we do not provide shipping or delivery services for physical products.</p>

        <p><strong>1. Appointment Scheduling</strong></p>
        <p style="padding-left: 20px;">1.1. Our website allows you to schedule appointments with our medical
          professionals for your ENT healthcare needs.</p>
        <p style="padding-left: 20px;">1.2. No physical products are shipped or delivered through our website.</p>

        <p><strong>2. Contact Us</strong></p>
        <p style="padding-left: 20px;">If you have any questions or require further information, please feel free to
          contact us at <b>{{
          storeDetails.email }}</b>.</p>

      </v-col>
    </v-row>
  </div>
</template>
<script>
import config from '@/configs'
import {
  mapState,
  mapActions
} from "vuex";
export default {
  data() {
    return {
      isAdmin: false,
      storeDetails: {
        name: "",
        address: "",
        contact: "",
        email: "",
        logo: "",
      },
    }
  },
  methods: {
    ...mapActions("app", ["getStoreData"]),
    getStoreData() {
      this.isAdmin = (localStorage.getItem('adminData')) ? true : false;
      axios.get("/api/store").then((response) => {
        this.storeDetails = response.data.storeDetails;
        // set store details to vuex
        // this.$store.commit("app/setStoreDetails", response.data.storeDetails);
      });
    },
    openPage(page) {
      if (page == 'bookappointment') {
        this.getalldoctors();
      }
      this.$router.push({
        name: page
      });
    },
    openAdmin() {
      this.$router.push({
        name: 'admin-dashboard'
      });
    },
    getalldoctors() {
      axios.get("/api/getalldoctors").then((response) => {
        if (response.data.success) {
          if (response.data.doctors.length > 0) {
            localStorage.setItem('doctor', JSON.stringify(response.data.doctors[0]));
          }
        }
      });
    },
  },
  mounted() {
    this.getStoreData();
  },
}
</script>