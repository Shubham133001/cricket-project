<template>
  <div>
    <v-row style="display: flex;justify-content: center;align-items: center;">
      <v-col cols="12" md="8">
        <h2 style="text-align: center;color: #0096c7;margin-bottom: 25px;">Cancellation and Refund Policy of
          {{ storeDetails.name }}</h2>

        <p>At {{ storeDetails.name }}, we understand that there may be times when you need to cancel or reschedule your
          appointment. Please review our Cancellation and Refund Policy below:</p>

        <p><strong>1. Cancellation</strong></p>
        <p style="padding-left: 20px;">1.1. You have the right to cancel your appointment with us.</p>
        <p style="padding-left: 20px;">1.2. We kindly request that you provide us with at least [notice period] notice
          prior to your scheduled appointment time if you wish to cancel.</p>
        <p style="padding-left: 20px;">1.3. Failure to provide the required notice may result in a cancellation fee.</p>

        <p><strong>2. Rescheduling</strong></p>
        <p style="padding-left: 20px;">2.1. We understand that unforeseen circumstances can arise. If you need to
          reschedule your appointment, please contact us as soon as possible.</p>
        <p style="padding-left: 20px;">2.2. We will make every effort to accommodate your rescheduling request, based on
          our availability.</p>

        <p><strong>3. Refunds</strong></p>
        <p style="padding-left: 20px;">3.1. We do not provide refunds for canceled appointments. Instead, we encourage
          you
          to reschedule your appointment for a more suitable time.</p>
        <p style="padding-left: 20px;">3.2. In the event of an exceptional circumstance, please contact us to discuss
          your
          situation, and we will consider your request on a case-by-case basis.</p>

        <p><strong>4. Contact Us</strong></p>
        <p style="padding-left: 20px;">If you have questions or concerns about our Cancellation and Refund Policy,
          please
          contact us at <b>{{ storeDetails.email }}</b>.</p>
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