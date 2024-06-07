<template>
  <v-sheet>
    <v-container class="py-8">
      <div class="d-flex flex-column flex-lg-row justify-space-between align-center">
        <div class="text-center text-lg-left">
          <div v-html="description(calltotitle)"></div>
        </div>
        <div class="mt-4 mt-lg-0">
          <v-btn x-large class="my-1 mx-sm-2 w-full w-sm-auto" color="primary"
            @click="opencontactus">{{ calltobutton }}</v-btn>
        </div>
      </div>
    </v-container>
  </v-sheet>
</template>
<script>
export default {
  data() {
    return {
      calltotitle: "",
      calltobutton: "Contact Sales",
    }
  },
  mounted() {
    this.getthemeoptions();
  },
  methods: {
    description(data) {
      return this.$striphtml(data);
    },
    opencontactus() {
      // open in new window
      window.open('https://bestcricketacademy.com/contact-us/', '_blank');
    },
    getthemeoptions() {
      var self = this;
      axios.get('/api/getpageoption')
        .then(function (response) {
          self.calltotitle = response.data.options.calltotitle;
          self.calltobutton = response.data.options.calltobutton;
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  }
}
</script>