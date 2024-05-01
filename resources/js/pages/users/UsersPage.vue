<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">Users</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn color="primary" to="/admin/user/add">
        Create User
      </v-btn>
    </div>

    <v-card>
      <!-- users list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedUsers.length > 0" v-on="on">
                  Actions
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item @click>
                <v-list-item-title>Verify</v-list-item-title>
              </v-list-item>
              <v-list-item @click>
                <v-list-item-title>Disable</v-list-item-title>
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item @click>
                <v-list-item-title>Delete</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field v-model="searchQuery" append-icon="mdi-magnify" class="flex-grow-1 mr-md-2" solo hide-details
            dense clearable placeholder="e.g. filter for id, email, name, etc"></v-text-field>
          <v-btn :loading="isLoading" icon small class="ml-2">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>

      <v-data-table v-model="selectedUsers" show-select :headers="headers" :items="users" :options.sync="options"
        :server-items-length="totalUsers" :search="searchQuery" :loading="loading" class="flex-grow-1">
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold"># <copy-label :text="item.id + ''" /></div>
        </template>

        <template v-slot:item.email="{ item }">
          <div class="d-flex align-center py-1">
            <v-avatar size="32" class="elevation-1 grey lighten-3">
              <h4 class="font-weight-bold text-uppercase">{{ item.name.charAt(0) }}</h4>
            </v-avatar>
            <div class="ml-1 caption font-weight-bold">
              <copy-label :text="item.email" />
            </div>
          </div>
        </template>

        <template v-slot:item.verified="{ item }">
          <v-icon v-if="item.verified" small color="success">
            mdi-check-circle
          </v-icon>
          <v-icon v-else small>
            mdi-circle-outline
          </v-icon>
        </template>

        <!-- <template v-slot:item.disabled="{ item }">
          <div>{{ item.disabled.toString() | capitalize }}</div>
        </template> -->

        <template v-slot:item.role="{ item }">
          <v-chip label small class="font-weight-bold" :color="item.role === 'ADMIN' ? 'primary' : undefined">{{
          item.role | capitalize }}</v-chip>
        </template>

        <template v-slot:item.created="{ item }">
          <div>{{ item.created_at | formatDate('ll') }}</div>
        </template>

        <!-- <template v-slot:item.lastSignIn="{ item }">
          <div>{{ item.lastSignIn | formatDate('lll') }}</div>
        </template> -->

        <template v-slot:item.action="{ item }">

          <div class="actions">
            <v-btn icon fab color="primary" small @click=" edituser(item.id)">
              <v-icon small color="primary">mdi-pencil</v-icon>
            </v-btn>
            <v-btn icon fab color="red" small @click="deleteuser(item.id)">
              <v-icon small color="red">mdi-delete</v-icon>
            </v-btn>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios'
import CopyLabel from '../../components/common/CopyLabel'

export default {
  components: {
    CopyLabel
  },
  data() {
    return {
      isLoading: false,
      breadcrumbs: [{
        text: 'Users',
        disabled: false,
        href: '#'
      }, {
        text: 'List'
      }],
      loading: false,
      users: [],
      options: {},
      totalUsers: 0,
      searchQuery: '',
      selectedUsers: [],
      headers: [
        { text: 'Id', align: 'left', value: 'id' },
        { text: 'Email', value: 'email' },
        // { text: 'Verified', value: 'verified' },
        { text: 'Name', align: 'left', value: 'name' },
        // { text: 'Role', value: 'role' },
        { text: 'Created', value: 'created' },
        // { text: 'Last SignIn', value: 'lastSignIn' },
        // { text: 'Status', value: 'status' },
        { text: '', sortable: false, align: 'right', value: 'action' }
      ],


    }
  },
  mounted() {
    this.getusers()
  },
  watch: {
    searchQuery(val) {
      this.searchUser(val)
    },
    options: {
      handler() {
        this.getusers()
      },
      deep: true
    }
  },
  methods: {
    searchUser() { },
    open() { },
    edituser(userid) {
      this.$router.push({
        name: 'admin-users-edit',
        params: {
          id: userid
        }
      })
    },
    deleteuser(userid) {
      axios.post('/api/admin/user/deleteuser', {
        id: userid
      })
        .then(response => {
          if (response.data.success) {
            this.$toasted.show('User deleted successfully', {
              type: 'success',
              duration: 3000
            })
            this.getusers()
          }
        })
        .catch(error => {
          console.error(error)
        }
        );
    },
    getusers() {
      this.isLoading = true
      let limit = this.options.itemsPerPage;
      let page = this.options.page;
      console.log(this.options)
      axios.get('/api/admin/users?limit=' + this.options.itemsPerPage + '&page=' + page + '&search=' + this.searchQuery + '&sortBy=' + this.options.sortBy + '&sortDesc=' + this.options.sortDesc)
        .then(response => {
          console.log(response.data.users);
          this.totalUsers = response.data.users.total
          this.users = response.data.users.data
        })
        .catch(error => {
          console.error(error)
        })
        .finally(() => {
          this.isLoading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
