<template>
  <div>
    <v-row style="display: flex;justify-content: center;align-items: center;">
      <v-col cols="12" md="8">

        <h2 style="text-align: center;color: #0096c7;margin-bottom: 25px;">Privacy Policy for {{ storeDetails.name }}
        </h2>

        <p style="padding-left: 20px;">Your privacy is important to us. This Privacy Policy explains how {{
          storeDetails.name }}
          collects, uses, and protects your personal information when you use our website and services. By using our
          services, you consent to the practices described in this policy.</p>

        <p><strong>1. Information We Collect</strong></p>
        <p style="padding-left: 20px;">We may collect the following information:</p>
        <ul style="padding-left: 40px;">
          <li>Your name, contact information, and date of birth.</li>
          <li>Medical history, records, and health-related information you provide to us.</li>
          <li>Information about your use of our website, including IP address, browser, and device information.</li>
        </ul>

        <p><strong>2. How We Use Your Information</strong></p>
        <p style="padding-left: 20px;">We may use your information for the following purposes:</p>
        <ul style="padding-left: 40px;">
          <li>Appointment scheduling and communication with you.</li>
          <li>Improving our website and services.</li>
          <li>Compliance with legal and regulatory requirements.</li>
        </ul>

        <p><strong>3. Information Security</strong></p>
        <p style="padding-left: 20px;">We implement reasonable security measures to protect your personal information.
          However, no method of transmission over the internet or electronic storage is completely secure, and we cannot
          guarantee absolute security.</p>

        <p><strong>4. Sharing Your Information</strong></p>
        <p style="padding-left: 20px;">We do not share your personal information with third parties unless required by
          law
          or as necessary to provide our services.</p>

        <p><strong>5. Cookies and Tracking</strong></p>
        <p style="padding-left: 20px;">Our website may use cookies and similar tracking technologies to enhance your
          user
          experience. You can manage your cookie preferences in your browser settings.</p>

        <p><strong>6. Links to Other Websites</strong></p>
        <p style="padding-left: 20px;">Our website may contain links to external websites. We are not responsible for
          the
          content or privacy practices of these websites. We encourage you to review their privacy policies.</p>

        <p><strong>7. Your Choices</strong></p>
        <p style="padding-left: 20px;">You have the right to access, correct, or delete your personal information. If
          you
          have questions or requests, please contact us using the information provided below.</p>

        <p><strong>8. Changes to this Policy</strong></p>
        <p style="padding-left: 20px;">We may update this Privacy Policy from time to time. Any changes will be posted
          on
          our website, and the date of the last update will be revised accordingly.</p>

        <p><strong>9. Contact Us</strong></p>
        <p style="padding-left: 20px;">If you have questions or concerns about this Privacy Policy, please contact us at
          <b>{{ storeDetails.email }}</b>.
        </p>

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