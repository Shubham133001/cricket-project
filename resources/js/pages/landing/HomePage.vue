<template>
  <div>
    <v-sheet>
      <v-row>
        <v-container class="py-6 pt-lg-1 text-left" style="position: relative; z-index: 1">
          <v-row>
            <v-col cols="12" md="6" class="pt-lg-15">
              <h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue
                with<br /><span class="primary--text ">Smarters Booking Management</span></h1>
              <!-- <h2 class="text-h6 text-sm-h5 mt-4 w-full w-md-8-12 w-xl-half mx-auto white--text">Lorem ipsum dolor sit,
              amet
              consectetur
              adipisicing elit. Commodi ex facilis ad atque natus tenetur debitis qui quisquam iure amet.</h2> -->
              <div class="mt-8">
                <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" color="primary">Start free trial</v-btn>
                <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto">Learn more</v-btn>
              </div>
            </v-col>
            <v-col cols="12" md="6">
              <v-img src="/images/cricket.png" class="mx-auto" max-width="100%" max-height="100%" />
            </v-col>
          </v-row>
        </v-container>
      </v-row>
    </v-sheet>

    <Partners />

    <Stats />

    <v-container>
      <v-divider></v-divider>
    </v-container>



    <Feature1 />
    <Feature2 />
    <Pricing />
    <Doctors />

    <CallToAction />
  </div>
</template>

<script>
import Partners from '@/components/landing/Partners.vue'
import Stats from '@/components/landing/Stats.vue'
import Pricing from '@/components/landing/Pricing.vue'
import Feature1 from '@/components/landing/Feature1.vue'
import CallToAction from '@/components/landing/CallToAction.vue'
import Feature2 from '@/components/landing/Feature2.vue'
import Doctors from '@/components/landing/Doctors.vue'

export default {
  components: {
    Partners,
    Stats,
    Pricing,
    Feature1,
    Feature2,
    CallToAction,
    Doctors,

  },
  data() {
    return {}
  },
  created() {
    this.clearDoctor();
  },
  methods: {
    clearDoctor() {
      localStorage.removeItem('doctor');
    },

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