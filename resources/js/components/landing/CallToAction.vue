<template>
  <v-sheet>
    <v-container class="py-8">
      <div class="d-flex flex-column flex-lg-row justify-space-between align-center">
        <div class="text-center text-lg-left">
          <div v-html="description(callaction.calltotitle)"></div>
        </div>
        <div class="mt-4 mt-lg-0">
          <v-btn x-large class="my-1 mx-sm-2 w-full w-sm-auto" color="primary"
            @click="opencontactus(callaction.calltobtnlink)">{{ callaction.calltobutton }}</v-btn>
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
    opencontactus(calltobtnlink) {
      console.log(calltobtnlink,"sfsfsfs");
      window.open(calltobtnlink, '_blank');
    },
  }
}
</script>