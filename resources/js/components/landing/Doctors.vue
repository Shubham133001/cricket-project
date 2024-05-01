<template>
  <!-- add doctors cards here -->

  <v-sheet color="transparent">
    <v-container class="py-4 py-lg-10">
      <div class="text-center">
        <h2 class="text-h3 text-lg-h2">Our Doctors</h2>
        <v-responsive max-width="1200" class="mx-auto">
          <div class="text-h6 text-lg-h5 mt-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus impedit error labore doloremque fugit! Dolor fugit molestiae vero quos quisquam nobis, eos debitis magni omnis ea incidunt amet voluptate dignissimos!</div>
        </v-responsive>
      </div>
      <v-row class="mt-6">
        <v-col v-for="(item, i) in doctors" :key="i" cols="12" lg="4">
          <v-card class="pa-3 text-center">
            <v-avatar size="100" class="mx-auto">
              <img :src="item.profilephoto" alt="avatar">
            </v-avatar>
            <div class="text-h5">
              {{ item.name }}
            </div>
            <div class="mt-2">
              {{ item.specialization.specialization_name }}
            </div>
            <div class="mt-1">
              {{ item.about|strip }}
              
            </div>
            <div class="mt-1">
              <strong>Age:</strong> {{ item.age }} Yrs.
            </div>
            <div class="mt-1">
              <strong>Experience:</strong> {{ item.experience }}
            </div>
            <div class="mt-1 mb-2">
              <strong>Location:</strong> {{ item.address }}, {{ item.city }}
            </div>
            <div class="text-center">
              <v-btn color="primary" @click="bookappointment(JSON.stringify(item))">Book Appointment</v-btn>
              <v-btn color="secondary" @click="knowmore(JSON.stringify(item))">Know More</v-btn>
            </div>
          </v-card>

        </v-col>
      </v-row>
    </v-container>
    
  </v-sheet>
</template>
<script>
  export default {
    data() {
      return {
        doctors: [],
        showDoctorDetails: false,
        doctorDetails: {}
      }
    },
    mounted() {
      this.getDoctors();
    },
    methods: {
      getDoctors() {
        axios.get('/api/getalldoctors   ')
          .then(response => {
            this.doctors = response.data.doctors;
          })
          .catch(error => {
            console.log(error);
          })
      },
      bookappointment($doctor) {
        localStorage.setItem('doctor', $doctor);
        this.$router.push('/bookappointment');
      },
      knowmore($doctor) {
        localStorage.setItem('doctor', $doctor);
        this.$router.push('/doctorprofile');
      }
    }
  }
</script>