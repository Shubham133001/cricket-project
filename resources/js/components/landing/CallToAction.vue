<template>
  <v-sheet>
    <v-container class="py-8">
      <div class="d-flex flex-column flex-lg-row justify-space-between align-center">
        <div class="text-center text-lg-left">
          <div v-html="aboutexcerpts"></div>
          <!-- <div class="text-h3">Ready to talk?</div>
          <div class="text-h3 primary--text">Our team is here to help.</div> -->
        </div>
        <div class="mt-4 mt-lg-0">
          <v-btn x-large class="my-1 mx-sm-2 w-full w-sm-auto" color="primary" @click="opencontactus">Contact
            Sales</v-btn>
          <!-- <v-btn x-large class="my-1 w-full w-sm-auto">Learn more</v-btn> -->
        </div>
      </div>
    </v-container>
  </v-sheet>
</template>
<script>
export default {
  data() {
    return {
      aboutexcerpts: ""
    }
  },
  mounted() {
    this.getthemeoptions();
  },
  methods: {
    opencontactus() {
      // open in new window
      window.open('https://bestcricketacademy.com/contact-us/', '_blank');
    },
    getthemeoptions() {
      var self = this;
      axios.get('/api/getpageoption')
        .then(function (response) {
           self.aboutexcerpts = response.data.options.aboutexcerpts;
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  }
}
</script>