<template>
  <div class="flex-grow-1">
    <v-container>
      <div class="d-flex align-center py-3">
        <div>
          <div class="display-1">
            Edit User {{ user.name && `- ${user.name}` }}
          </div>
          <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
        </div>
        <v-spacer></v-spacer>
        <!-- <v-btn icon @click>
        <v-icon>mdi-refresh</v-icon>
      </v-btn> -->
      </div>

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
            <v-card-title>Basic Information</v-card-title>
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

                  <!-- <div class="mt-2">
                    <v-btn color="primary" @click="updateuser">Update</v-btn>
                  </div> -->
                </div>
              </div>
            </v-card-text>
          </v-card>

          <v-card>
            <v-card-title>Team Information</v-card-title>
            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <!-- <v-avatar :size="100" :color="hover ? 'primary' : ''" color="#cccccc">
                      <v-img :lazy-src="temimagecurrent" :src="'/storage/' + user.team.image"
                          v-if="user.team.image != '' && user.team.image != null" class="align-center" />
                      <span class="headline text-h1" v-else>{{ user.team.name.charAt(0) }}</span>
                  </v-avatar> -->
                <v-hover v-slot:default="{ hover }">
                  <v-avatar
                    :size="150"
                    :color="hover ? 'primary' : ''"
                    color="#cccccc"
                  >
                    <v-img
                      :lazy-src="temimagecurrent"
                      :src="'/storage/' + user.team.image"
                      v-if="user.team.image != '' && user.team.image != null"
                      class="align-center"
                    />
                    <span class="headline text-h1" v-else>{{
                      user.team.name.charAt(0)
                    }}</span>
                    <v-fade-transition>
                      <v-overlay v-if="hover" absolute color="#036358">
                        <v-btn icon fab small @click="handleFileImport"
                          ><v-icon>mdi-pencil</v-icon></v-btn
                        >
                      </v-overlay>
                    </v-fade-transition>
                  </v-avatar>
                </v-hover>
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <input
                    type="file"
                    ref="uploader"
                    style="display: none"
                    @change="onfilechange"
                  />
                  <v-text-field
                    v-model="user.team.name"
                    label="Team Name"
                    placeholder="name"
                    outlined
                  ></v-text-field>
                  <!-- <v-text-field
                    v-model="user.team.designation"
                    label="Designation"
                    outlined
                  ></v-text-field> -->
                  <v-select
                    v-model="user.team.designation"
                    :items="designations"
                    outlined
                    label="Designation"
                  ></v-select>
                  <v-text-field
                    v-model="user.team.experience"
                    label="Experience (How old is your team?)"
                    outlined
                  ></v-text-field>
                  <!-- <v-text-field
                    v-model="user.team.description"
                    label="Description"
                    class=""
                    outlined
                  ></v-text-field> -->
                  <v-textarea v-model="user.team.description" outlined label="Description"></v-textarea>
                  <div class="d-flex flex-column mt-2">
                    <!-- <v-checkbox v-model="user.status" dense label="Email Verified"></v-checkbox> -->
                    <!-- <div>
                    <v-btn v-if="!user.status">
                      <v-icon left small>mdi-email</v-icon>Send Verification Email
                    </v-btn>
                  </div> -->
                  </div>
                </div>
              </div>
              <div class="mt-2">
                <v-btn color="primary" @click="updateuser">Update</v-btn>
              </div>
            </v-card-text>
          </v-card>
        </div>
      </div>
      <!-- <v-dialog v-model="askpassword" max-width="350px">
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
      </v-dialog> -->
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
      designations: ["Beginner", "Intermediate", "Expert"],
      loadingdata: true,
      temimagecurrent: "",
      tab: null,
      askpassword: false,
      breadcrumbs: [
        {
          text: "Users",
          to: "/admin/users",
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
    onfilechange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      const files = e.target.files[0];
      this.user.team.image = files;
      reader.onload = (e) => {
        this.temimagecurrent = e.target.result;
      };
    },
    handleFileImport() {
      this.$refs.uploader.click();
    },
    async me() {
      await axios
        .get("/api/admin/user/edit/" + this.$route.params.id)
        .then((response) => {
          // console.log(response.data.userdata);
          if (response.data.success) {
            // response.data.userdata.status = (response.data.userdata.status == 1) ? true : false;
            this.user = response.data.data;
            this.temimagecurrent = response.data.data.team.image;
            this.loadingdata = false;
          } else {
            this.$router.push({
              name: "login",
            });
          }
        });
    },
    async updateuser() {
      let formdata = new FormData();
      formdata.append("id", this.user.id);
      formdata.append("name", this.user.name);
      formdata.append("email", this.user.email);
      formdata.append("phone", this.user.phone);
      formdata.append("password", this.user.password);
      formdata.append("team_name", this.user.team.name);
      formdata.append("designation", this.user.team.designation);
      formdata.append("experience", this.user.team.experience);
      formdata.append("description", this.user.team.description);
      formdata.append("image", this.user.team.image);
      
      axios.post("/api/admin/user/update", formdata).then((response) => {
        if (response.data.success) {
          this.me();
          this.$toasted
            .show("User updated successfully", {
              type: "success",
            })
            .goAway(2000);
          this.askpassword = false;
          this.user.password = null;
          // this.user.currentpass = null;
        } else {
          this.$toasted
            .show("Password Does Not Match", {
              type: "error",
            })
            .goAway(2000);
          this.user.currentpass = null;
          return;
        }
      });
    },
  },
};
</script>