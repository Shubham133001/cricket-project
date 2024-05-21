<template>
  <div class="flex-grow-1">
    <v-container>
      <div
        v-if="user.admin_group_id === 1"
        class="d-flex align-center font-weight-bold primary--text my-2"
      >
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
            <div class="d-flex flex-sm-row">
              <v-card-title>Basic Information</v-card-title
              ><v-spacer></v-spacer>
              <v-card-title
                >Credits Balance : {{ user.credits }} INR</v-card-title
              >
            </div>

            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-text-field
                    v-model="user.name"
                    label="Display name"
                    placeholder="name"
                    outlined
                  ></v-text-field>
                  <v-text-field
                    v-model="user.email"
                    :disabled="user.status"
                    label="Email"
                    outlined
                  ></v-text-field>
                  <v-text-field
                    v-model="user.phone"
                    label="Phone"
                    outlined
                  ></v-text-field>
                  <v-text-field
                    v-model="user.password"
                    label="Password"
                    class=""
                    outlined
                  ></v-text-field>
                  <div class="d-flex flex-column mt-2">
                    <!-- <v-checkbox v-model="user.status" dense label="Email Verified"></v-checkbox> -->
                    <!-- <div>
                    <v-btn v-if="!user.status">
                      <v-icon left small>mdi-email</v-icon>Send Verification Email
                    </v-btn>
                  </div> -->
                  </div>

                  <div class="mt-2">
                    <v-btn color="primary" @click="updateuser">Update</v-btn>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </div>
      </div>
    </v-container>
  </div>
</template>

<script>
import CopyLabel from "../../components/common/CopyLabel";
import AccountTab from "./EditUser/AccountTab";
import InformationTab from "./EditUser/InformationTab";

export default {
  components: {
    CopyLabel,
    AccountTab,
    InformationTab,
  },
  data() {
    return {
      user: {
        phone: "",
        id: 32,
        email: "bfitchew0@ezinearticles.com",
        name: "Bartel Fitchew",
        password: null,
      },
      loadingdata: true,
      tab: null,
      askpassword: false,
      breadcrumbs: [
        {
          text: "Home",
          to: "/",
          exact: true,
        },
        {
          text: "Edit User",
        },
      ],
    };
  },
  mounted() {
    this.me();
  },
  methods: {
    async me() {
      await axios
        .get("/api/user/me")
        .then((response) => {
          // console.log(response.data.userdata);
          if (response.data.success) {
            // response.data.userdata.status = (response.data.userdata.status == 1) ? true : false;
            this.user = response.data.user;
            this.loadingdata = false;
          } else {
            this.$router.push({
              name: "home",
            });
          }
        })
        .catch((error) => {
          this.$router.push({
            name: "home",
          });
        });
    },
    async updateuser() {
      axios.post("/api/user/update", this.user).then((response) => {
        // console.log(response.data);

        if (response.data.success) {
          this.$toasted
            .show("User updated successfully", {
              type: "success",
            })
            .goAway(2000);
          this.askpassword = false;
          this.user.password = null;
        } else {
          this.$toasted
            .show(response.data.message, {
              type: "error",
            })
            .goAway(2000);
          return;
        }
      });
    },
  },
};
</script>