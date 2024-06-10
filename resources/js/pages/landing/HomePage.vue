<template>
  <div>
    <v-sheet>
      <v-container class="py-lg-6 py-sm-2 pt-lg-1 text-left" style="position: relative; z-index: 1">
        <v-row>
          <v-col cols="12" md="6" class="pt-lg-15 pt-2">
            <div v-html="description(bannertitle)"></div>
            <!-- <h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue
                with<br /><span class="primary--text ">Smarters Booking Management</span></h1> -->
            <div class="mt-8">
              <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" color="primary" :to=bannerbtnlink>{{
                bannerbtntext }}</v-btn>
              <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" @click="opencontactus(bannerbtnlink1)">{{
                bannerbtntext1
                }}</v-btn>
            </div>
          </v-col>
          <v-col cols="12" md="6">
            <v-img v-if="bannerimage" :src="bannerimage" class="mx-auto" max-width="100%" max-height="100%" />
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
    <Feature2 :features="features" />
    <CallToAction :callaction="callaction" />
  </div>
</template>
<script>
import Partners from '@/components/landing/Partners.vue'
import Stats from '@/components/landing/Stats.vue'
import CallToAction from '@/components/landing/CallToAction.vue'
import Feature2 from '@/components/landing/Feature2.vue'
import Categories from '@/pages/bookings/Bookings.vue'
export default {
  components: {
    Partners,
    Stats,
    Feature2,
    CallToAction,
    Categories
  },
  data() {
    return {
      bannertitle: '',
      bannerbtntext: '',
      bannerbtntext1: '',
      bannerbtnlink: '',
      bannerbtnlink1: '',
      bannerimageshow: '',
      bannerimage: '',
      callaction: {
        calltotitle: '',
        calltobutton: '',
      },

      abouteTitle: '',
      aboutexcerpts: '',
      whyusimage: '',
      features: [],
    }
  },
  mounted() {
    this.pagedata();
  },
  methods: {
    description(data) {
      return this.$striphtml(data);
    },
    opencontactus(btnlink) {
      window.open(btnlink, '_blank');
    },
    pagedata() {
      var self = this;
      axios.get('/api/getpageoption')
        .then(function (response) {
          self.bannertitle = response.data.options.bannertitle;
          self.bannerbtntext = response.data.options.bannerbtntext;
          self.bannerbtntext1 = response.data.options.bannerbtntext1;
          self.bannerbtnlink = response.data.options.bannerbtnlink;
          self.bannerbtnlink1 = response.data.options.bannerbtnlink1;
          self.bannerimageshow = response.data.options.bannerimageshow;
          self.bannerimage = response.data.options.bannerimage;
          self.callaction.calltotitle = response.data.options.calltotitle;
          self.callaction.calltobutton = response.data.options.calltobutton;
          self.abouteTitle = response.data.options.abouteTitle;
          self.aboutexcerpts = response.data.options.aboutexcerpts;
          self.whyusimage = response.data.options.whyusimage;
          self.features = JSON.parse(response.data.options.features);
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  },

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