<template>
  <div>
    <v-row style="display: flex;justify-content: center;align-items: center;">
      <v-col cols="12" md="8">
        <h2 style="text-align: center;color: #0096c7;margin-bottom: 25px;">Terms and Conditions for {{
          storeDetails.name }} Appointment Website</h2>
        <p><strong>1. Acceptance of Terms</strong></p>
        <p style="padding-left: 20px;">By using the services provided by this {{
          storeDetails.name }} appointment website, you
          agree
          to abide by and accept the following terms and conditions. If you do not agree with these terms, you should
          not
          use
          this website.</p>

        <p><strong>2. Website Usage</strong></p>
        <p style="padding-left: 20px;">2.1. This website provides information about the hospital's services and allows
          users
          to request and schedule appointments.</p>
        <p style="padding-left: 20px;">2.2. Users must provide accurate and up-to-date information when using the
          website's
          services.</p>

        <p><strong>3. Appointment Requests</strong></p>
        <p style="padding-left: 20px;">3.1. All appointment requests are subject to availability and approval by the
          hospital.
        </p>
        <p style="padding-left: 20px;">3.2. The website provides estimated appointment times, but actual appointment
          times
          may
          vary based on hospital availability and scheduling.</p>

        <p><strong>4. Patient Responsibility</strong></p>
        <p style="padding-left: 20px;">4.1. Patients are responsible for providing accurate medical history and personal
          information.</p>
        <p style="padding-left: 20px;">4.2. Patients are responsible for arriving on time for their appointments and
          following
          the hospital's guidelines for preparation and conduct.</p>

        <!-- Add the remaining points here following the same structure -->

        <p><strong>5. Privacy</strong></p>
        <p style="padding-left: 20px;">5.1. The hospital will protect patient information as per its Privacy Policy. By
          using
          the website, you agree to the collection and use of your data in accordance with this policy.</p>

        <p><strong>6. Cancellation and Rescheduling</strong></p>
        <p style="padding-left: 20px;">6.1. Patients may cancel or reschedule their appointments as per the hospital's
          guidelines.</p>
        <p style="padding-left: 20px;">6.2. The hospital reserves the right to cancel or reschedule appointments due to
          unforeseen circumstances and will make reasonable efforts to notify patients.</p>

        <p><strong>7. Fees and Payments</strong></p>
        <p style="padding-left: 20px;">7.1. Any fees associated with hospital services will be communicated to patients
          before
          the appointment.</p>
        <p style="padding-left: 20px;">7.2. Payment policies are outlined in the billing section of the website and must
          be
          followed.</p>

        <p><strong>8. Intellectual Property</strong></p>
        <p style="padding-left: 20px;">8.1. All content, including text, images, and logos on this website, is protected
          by
          copyright and other intellectual property laws.</p>

        <p><strong>9. Disclaimers</strong></p>
        <p style="padding-left: 20px;">9.1. The hospital provides information on this website for general informational
          purposes only. It does not constitute medical advice or diagnosis.</p>

        <p><strong>10. Limitation of Liability</strong></p>
        <p style="padding-left: 20px;">10.1. The hospital is not liable for any direct, indirect, or consequential
          damages
          resulting from the use or inability to use this website.</p>

        <p><strong>11. Changes to Terms</strong></p>
        <p style="padding-left: 20px;">11.1. The hospital may update these terms and conditions at any time. Users are
          responsible for regularly reviewing the terms.</p>

        <p><strong>12. Governing Law</strong></p>
        <p style="padding-left: 20px;">12.1. These terms are governed by the laws.</p>

        <p><strong>13. Contact Information</strong></p>
        <p style="padding-left: 20px;">13.1. If you have any questions or concerns about these terms and conditions,
          please
          contact us at <b>{{
          storeDetails.email }}</b></p>

        <p style="padding-left: 20px;">By using this {{
          storeDetails.name }} appointment website, you acknowledge that you
          have
          read
          and understood these terms and agree to be bound by them.</p>
        <p style="padding-left: 20px;">Last Updated: <b>24/Oct/2023</b></p>
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
