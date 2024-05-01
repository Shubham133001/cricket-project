<template>
  <div>
    <v-row style="display: flex;justify-content: center;align-items: center;">
      <v-col cols="8" md="3">
        <div class="text-h6 text-lg-h5 font-weight-bold">Contact Us</div>
        <div style="width: 80px; height: 2px" class="mb-5 mt-1 primary" />
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2">mdi-map-marker-outline</v-icon>
          S.C.F 24-25 FF, Phase 5, Sector 59, Sahibzada Ajit Singh Nagar, Punjab 160059,
          Tel. 2261900,5096333
        </div>
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2">mdi-phone-outline</v-icon>
          <a href="#" class="text-decoration-none text--primary">Mobile: 9815222424</a>
        </div>
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2">mdi-email-outline</v-icon>
          <a href="#" class="text-decoration-none text--primary">Email Address: mohalientclinic@gmail.com</a>
        </div>
      </v-col>
      <v-col cols="8" md="6">
        <div class="google-map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d17686.429938517038!2d76.70020601741778!3d30.719464701041623!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fee89da6755df%3A0xbe752be3d76d2e95!2sMohali%20ENT%20Clinic!5e0!3m2!1sen!2sin!4v1698146729435!5m2!1sen!2sin"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
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
<style scoped>
.google-map {
  padding-bottom: 50%;
  position: relative;
}

.google-map iframe {
  height: 100%;
  width: 100%;
  left: 0;
  top: 0;
  position: absolute;
}</style>