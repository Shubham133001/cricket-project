<template>
  <v-container class="py-4">
    <v-row>
      <v-col v-for="(item, i) in JSON.parse(pageDetails.features)" :key="i" cols="12" md="6">
        <div class="d-flex">
          <div class="mr-2">
            <v-sheet outlined rounded class="pa-2 elevation-1">
              <v-icon color="primary">{{ item.icon }}</v-icon>
            </v-sheet>
          </div>
          <div>
            <div class="text-h5">{{ item.title }}</div>
            <div class="text-h6 font-weight-light mt-1">{{ item.description }}</div>
          </div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState} from 'vuex'
export default { 
  computed: {
    ...mapState('app', ['pageDetails'])
  }
}
</script>
