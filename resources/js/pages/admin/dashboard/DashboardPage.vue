<template>
  <div class="d-flex flex-grow-1 flex-column">
    <h1>Welcome to {{ companyname }}</h1>
    {{ bookings }}
  </div>
</template>

<script>
// DEMO Cards for dashboard
import SalesCard from '../../../components/dashboard/SalesCard'
import {
  mapState,
  mapActions
} from 'vuex';
export default {
  components: {
    SalesCard
  },
  data() {
    return {
      loadingInterval: null,
      companyname: JSON.parse(localStorage.getItem('store')).name,
      role: 0,
      bookings: []

    }
  },
  beforeMount() {
    this.getAdminRoles();
  },
  mounted() {
    this.getBookings();

  },
  methods: {
    ...mapActions('app', ['getStoreData']),

    getAdminRoles() {

      axios.get('/api/admin/getcurretadmingroup')
        .then(response => {
          this.role = response.data.data.admin_group_id;
          console.log(response.data.data.admin_group_id);
        })
        .catch(error => {
          console.log(error)
        })
    },

    async getBookings() {
      await axios.post('/api/admin/bookings/list')
        .then(response => {
          this.bookings = response.data.data;
          console.log(response.data.data);
        })
        .catch(error => {
          console.log(error)
        })
    }

  }
}
</script>