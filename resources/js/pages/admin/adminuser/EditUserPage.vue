<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">Edit User {{ user.name && `- ${user.name}` }}</div>
        <!-- <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs> -->
      </div>
      <v-spacer></v-spacer>
      <v-btn icon @click>
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </div>

    <div v-if="user.admin_group_id === 1" class="d-flex align-center font-weight-bold primary--text my-2">
      <v-icon small color="primary">mdi-security</v-icon>
      <span class="ma-1">Administrator</span>
    </div>

    <div class="my-2">
      <div>
        <!-- <v-card v-if="!user.status" class="warning mb-4" light>
          <v-card-title>User Disabled</v-card-title>
          <v-card-subtitle>This user has been disabled! Login accesss has been revoked.</v-card-subtitle>
          <v-card-text>
            <v-btn dark @click="user.status = true">
              <v-icon left small>mdi-account-check</v-icon>Enable User
            </v-btn>
          </v-card-text>
        </v-card> -->

        <v-card :loading="loadingdata">
          <v-card-title>Basic Information</v-card-title>
          <v-card-text>
            <div class="d-flex flex-column flex-sm-row">
              <div class="flex-grow-1 pt-2 pa-sm-2">
                <v-text-field v-model="user.name" label="Display name" placeholder="name"></v-text-field>
                <v-text-field v-model="user.email" :disabled="user.status" label="Email" hide-details></v-text-field>
                <v-text-field v-model="user.password" label="Password" class="mt-2" hide-details></v-text-field>
                <div class="d-flex flex-column mt-2">
                  <!-- <v-checkbox v-model="user.status" dense label="Email Verified"></v-checkbox> -->
                  <!-- <div>
                    <v-btn v-if="!user.status">
                      <v-icon left small>mdi-email</v-icon>Send Verification Email
                    </v-btn>
                  </div> -->
                </div>

                <div class="mt-2">
                  <v-btn color="primary" @click="askpassword = true">Update</v-btn>
                </div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </div>
    </div>
    <v-dialog v-model="askpassword" max-width="350px">
      <v-card>
        <v-card-title>
          <span class="headline">Enter Current Password</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-text-field v-model="user.currentpass" label="Current Password" required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="justify-center">
          <v-btn color="primary" @click="updateuser">Save</v-btn>
          <v-btn color="error" @click="askpassword = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import CopyLabel from '@/components/common/CopyLabel'
// import AccountTab from './EditUser/AccountTab'
// import InformationTab from './EditUser/InformationTab'

export default {
  components: {
    CopyLabel,
    // AccountTab,
    // InformationTab,
  },
  data() {
    return {
      user: {
        'id': 32,
        'email': 'bfitchew0@ezinearticles.com',
        'name': 'Bartel Fitchew',
        'password': null,
      },
      loadingdata: true,
      tab: null,
      askpassword: false,
      breadcrumbs: [{
        text: 'Users',
        to: '/users/list',
        exact: true
      },
      {
        text: 'Edit User'
      }
      ]
    }
  },
  mounted() {
    this.me()
  },
  methods: {
    async me() {
      await axios.get('/api/admin/me').then(response => {
        console.log(response.data.userdata);
        if (response.data.success) {
          // response.data.userdata.status = (response.data.userdata.status == 1) ? true : false;
          this.user = response.data.userdata;
          this.loadingdata = false;
        } else {
          this.$router.push({
            name: 'login'
          })
        }
      })
    },
    async updateuser() {
      if (!this.user.currentpass) {
        this.$toasted.show('Please enter your current password', {
          type: 'error'
        }).goAway(2000);
        return;
      }
      axios.post('/api/admin/user/update', this.user).then(response => {
        // console.log(response.data);

        if (response.data.success) {
          this.$toasted.show('User updated successfully', {
            type: 'success'
          }).goAway(2000);
          this.askpassword = false;
          this.user.password = null;
          this.user.currentpass = null;
        } else {
          this.$toasted.show('Password Does Not Match', {
            type: 'error'
          }).goAway(2000);
          this.user.currentpass = null;
          return;
        }

      })

    }
  },
}
</script>