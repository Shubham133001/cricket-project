<template>
  <div>

    <v-container class="py-6">
      <v-row>
        <v-col cols="12" md="6">
          <h2 class="display-2 font-weight-bold">About Us</h2>
          <p class="text--primary">We are a team of talented professionals who are passionate about
          </p>
        </v-col>
        <v-col cols="12" md="6">
          <v-img src="https://cdn.vuetifyjs.com/images/cards/desert.jpg" aspect-ratio="2.75"></v-img>
        </v-col>

      </v-row>

    </v-container>
    <v-row style="background: #fff;">
      <v-col cols="12">
        <Stats />
      </v-col>
    </v-row>
  </div>
</template>
<script>
import config from '@/configs'
import Stats from '@/components/landing/Stats.vue'
import {
  mapState,
  mapActions
} from "vuex";
export default {
  components: {
    Stats
  },
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
}
</style>