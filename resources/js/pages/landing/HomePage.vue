<template>
  <div>
    <v-sheet>
      <v-container class="py-lg-6 py-sm-2 pt-lg-1 text-left" style="position: relative; z-index: 1">
        <v-row>
          <v-col cols="12" md="6" class="pt-lg-15 pt-2">
            <div v-html="description(heroText)"></div>
            <!-- <h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue
                with<br /><span class="primary--text ">Smarters Booking Management</span></h1> -->
            <div class="mt-8">
              <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" color="primary"
                :to=btnlink>{{ btntext }}</v-btn>
              <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" 
                @click="opencontactus(btnlink1)">{{ btntext1 }}</v-btn>
            </div>
          </v-col>
          <v-col cols="12" md="6">
            <v-img v-if="bannerImage" :src="bannerImage" class="mx-auto" max-width="100%" max-height="100%" />
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
    <Partners />
    <Stats />
    <v-col cols="12" style="background: #fff;" class="pt-4">
      <h2 class="text-h4 text-sm-h4 text-md-h3 text-lg-h3 text-center">Categories</h2>
      <Categories />
    </v-col>
    <Feature2 />
    <CallToAction />
  </div>
</template>
<script>
import Partners from '@/components/landing/Partners.vue'
import Stats from '@/components/landing/Stats.vue'
//import Pricing from '@/components/landing/Pricing.vue'
//import Feature1 from '@/components/landing/Feature1.vue'
import CallToAction from '@/components/landing/CallToAction.vue'
import Feature2 from '@/components/landing/Feature2.vue'
//import Doctors from '@/components/landing/Doctors.vue'
import Categories from '@/pages/bookings/Bookings.vue'

export default {
  components: {
    Partners,
    Stats,
    //  Pricing,
    // Feature1,
    Feature2,
    CallToAction,
    //  Doctors,
    Categories
  },
  data() {
    return {
      heroText: 'Digitize your sports venue with Smarters Booking Management',
      bannerImage: '/images/cricket.png',
      btntext: '',
      btntext1: '',
      btnlink: '',
      btnlink1: '',
      btncolor: '',
      btncolor1: '',
      btntextcolor: '',
      btntextcolor1: '',
    }
  },
  created() {
    this.clearDoctor();
  },
  methods: {
      description(data) {
        return this.$striphtml(data);
      },
    getthemeoptions() {
      var self = this;
      axios.get('/api/getpageoption')
        .then(function (response) {
          self.heroText = response.data.options.bannertitle;
          self.bannerImage = response.data.options.bannerimage;
          self.btntext = response.data.options.bannerbtntext;
          self.btntext1 = response.data.options.bannerbtntext1;
          self.btnlink = response.data.options.bannerbtnlink;
          self.btnlink1 = response.data.options.bannerbtnlink1;
          self.btncolor = response.data.options.bannerbtncolor;
          self.btncolor1 = response.data.options.bannerbtncolor1;
          self.btntextcolor = response.data.options.bannerbtntextcolor;
          self.btntextcolor1 = response.data.options.bannerbtntextcolor1;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    clearDoctor() {
      localStorage.removeItem('doctor');
    },
    opencontactus(btnlink) {
      window.open(btnlink, '_blank');
    }
  },
  mounted() {
    this.getthemeoptions();
  }
}
</script>
<style>
.bannerhome::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 0;
}
</style>