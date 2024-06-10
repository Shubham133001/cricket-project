<template>
  <v-sheet>
    <v-container class="py-8">
      <div class="d-flex flex-column flex-lg-row justify-space-between align-center">
        <div class="text-center text-lg-left">
          <div v-html="description(callaction.calltotitle)"></div>
        </div>
        <div class="mt-4 mt-lg-0">
          <v-btn x-large class="my-1 mx-sm-2 w-full w-sm-auto" color="primary"
            @click="opencontactus">{{ callaction.calltobutton }}</v-btn>
        </div>
      </div>
    </v-container>
  </v-sheet>
</template>
<script>
import { mapState} from 'vuex'
export default {
  props: {
    callaction: {
      type: Object,
      required: true
    }
  },
  methods: {
    description(data) {
      return this.$striphtml(data);
    },
    opencontactus() {
      // open in new window
      window.open('https://bestcricketacademy.com/contact-us/', '_blank');
    },
  }
}
</script>