<template>
  <v-container class="pt-0 pb-4">
    <v-row>
      <v-col v-for="(item, i) in stats" :key="i" cols="12" sm="6" lg="3" class="showslidebg1">
        <div class="text-center">
          <div class="text-h2 text-number font-weight-light stattitle">{{ item.value }}</div>
          <v-responsive max-width="300" class="mx-auto">
            <div class="text-h6 text-lg-h5">{{ item.title }}</div>
          </v-responsive>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      stats: []
    }
  },
  mounted() {
    this.getStats()
  },
  methods: {
    async getStats() {
      // Fetch stats from API
      axios.get('/api/stats')
        .then(response => {
          let stats = response.data.stats;
          this.stats = [{
            title: 'Bookings',
            value: stats.total_bookings + "+"
          }, {
            title: 'Categories',
            value: stats.total_categories + "+"
          }, {
            title: 'Slots',
            value: stats.total_slots + "+"
          }, {
            title: 'Successful Bookings',
            value: stats.total_completebookings + "+"
          }]
        })
        .catch(error => {
          console.error(error)
        })
    }
  }
}
</script>
<style scoped>
.showslidebg1 .stattitle {
  transition: all 0.5s;
}

.showslidebg1:hover .stattitle {
  color: var(--v-primary-base);
  transition: all 0.5s;
  transform: scale(1.15);
}
</style>
