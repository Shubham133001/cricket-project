<template>
  <div>

    <v-container class="py-6">
      <v-row> 
        <v-col cols="12" md="6">
          <h2 class="display-2 font-weight-bold">{{ pageDetails.abouteTitle }}</h2>
             <br/>
            <div v-html="description(pageDetails.aboutexcerpts)"></div>
        </v-col>
        <v-col cols="12" md="6">
          <v-img v-if="pageDetails.whyusimage" :src="pageDetails.whyusimage" class="mx-auto" height="300" max-width="500"
                                    style="margin: auto;"></v-img>
          <v-img v-else src="https://cdn.vuetifyjs.com/images/cards/desert.jpg" aspect-ratio="2.75"></v-img>
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
import Stats from '@/components/landing/Stats.vue'
import { mapState, mapActions } from 'vuex'
export default {
  components: {
    Stats
  },
  mounted() {
    this.getPageData();
  },
  computed: {
    ...mapState('app', ['pageDetails'])
  },
  methods: {
    ...mapActions("app", ["getPageData"]),
    description(data) {
        return this.$striphtml(data);
      },
  }
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