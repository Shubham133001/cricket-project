<template>
    <div class="d-flex flex-column flex-grow-1">
      <div>
        <div class="display-1">Edit Group</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-card>
        <v-card-title>
         <!--  Edit Group -->
          <v-spacer></v-spacer>
        </v-card-title>
        <v-form ref="form" v-model="isFormValid" lazy-validation>
          <div class="pa-4">
              <v-row>
                  <v-col cols="12" md="6">
                  <v-text-field
                      v-model="fullname"
                      :rules="nameRules"
                      :counter="50"
                      :label="'Group Name'"
                      required
                      outlined
                  ></v-text-field>
                  </v-col>
            </v-row>
            <v-row>
            <v-col
              v-for="(allroute, id) in allroutes"
              :key="id"
              cols="12"
              sm="12"
            >
              <div
                class="text-h5"
                style="
                  display: flex;
                  align-items: center;
                  text-transform: capitalize;
                "
              >
                {{ id.replace("_", " ") }}
              </div>
              <div
                class="text-h5"
                style="margin-left:0%; width:50%; float: left;"
                v-for="(route, i) in allroute"
                :key="i"
              >
                <v-checkbox :disabled="disabledcheckbox"
                  v-model="routes"
                  :value="route.slug"
                  :label="route.name"
                  outlined
                >
                  </v-checkbox
                >
              </div>
            </v-col>
          </v-row>
          <v-row style="position: fixed; top: 100px; right: 55px" v-if="forbuttonspermission != 1">
            <v-btn color="primary" elevation="2" @click="checkAll()">
              Check All </v-btn
            >
            &nbsp;
            <v-btn color="primary" elevation="2" @click="unCheckAll()">
             Uncheck All</v-btn
            >
            <br /><br />
          </v-row>

            <v-row class="float-right pa-3">
              <v-spacer></v-spacer>

              <v-btn
                :loading="isLoading"
                :disabled="isSignInDisabled"
                elevation="2"
                color="primary"
                x-large
                @click="submit"
              >
                Update
              </v-btn>
              &nbsp;
              <!-- <v-btn elevation="2" x-large @click="clear"> Clear </v-btn> -->

              &nbsp;
              <v-btn elevation="2" x-large @click="backtolist"> Cancel </v-btn>
              <v-spacer></v-spacer>
            </v-row>

              <!-- <v-checkbox
              v-model="status"
              :label="`Tick this box to enable this account`"
              persistent-hint
              hint=""
              outlined
              ></v-checkbox> -->
          </div>
        </v-form>
      </v-card>
    </div>
  </template>
  <script>
  export default {
    components: {
    },
    mounted: function () {
        this.getAllRoutes();
        this.getGroupAdmin();
    },
    data: () => ({
    forbuttonspermission: "",
    disabledcheckbox: true,
    allroutes: [],
    routes: ["admin.me"],
    routesall: [],
      enablenotify: false,
      statusList: [
        { text: "Active", value: 1 },
        { text: "Inactive", value: 0 },
      ],
      showPassword: false,
      breadcrumbs: [{
        text: 'Admin Group',
        disabled: false,
        to: '/admin/adminuser/groups'
      }, {
        text: 'Edit Group'
      }],
      adminGroupList: [],
      isLoading: false,
      isSignInDisabled: false,
      isFormValid: true,
      valid: false,
      fullname: "",
      email: "",
      password: "",
      group: "",
      status: 1,
      nameRules: [
        (v) => !!v || "Group Name is required",
        (v) => (v && v.length <= 50) || "Group Name must be less than 50 characters",
      ],
    }),
    methods: {
       backtolist() {
        this.$router.go(-1);
      },
      async getGroupAdmin() {
        await axios
          .get("/api/admin/getgroupadmin/" + this.$route.params.id)
          .then((response) => {
            const alldata = response.data.records;
            this.fullname = alldata.name;
            this.forbuttonspermission = alldata.id;
            if (alldata.id == 1) {
                this.checkAll();
                this.disabledcheckbox = true;
            }
            else
            {
                if (alldata.permissions.length > 0)
                {
                    this.routes = [];
                    alldata.permissions.forEach((element) => {
                        this.routes.push(element.slug);
                    });
                }
                this.disabledcheckbox = false;
            }
          })
          .catch((error) => {
            if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            } else {
            this.$toasted.show(error,{
                type: "error",
                icon : 'error_outline',
                action : {
                    text : 'Cancel',
                    onClick : (e, toastObject) => {
                        toastObject.goAway(0);
                    }
                }
            }).goAway(10000);
            this.$router.push({
                name: "admin-users",
            });
          }
          });
        },
        getAllRoutes() {
            axios
                .get("/api/admin/getroutes")
                .then((response) => {
                    //console.log(response);
                    this.allroutes = response.data.records;
                })
                .catch((error) => {
                    //console.log(error);
                });
        },
      submit(e) {
        if (this.$refs.form.validate()) {
          this.isLoading = true;
          this.isSignInDisabled = true;
          axios
            .post("/api/admin/updateadmingroup/"+this.$route.params.id, {
                name: this.fullname,
                routes: this.routes
            })
            .then((response) => {
              this.isLoading = false;
              this.isSignInDisabled = false;
              if (response.data.success == true) {
                  this.$toasted.show(response.data.message,{
                      type: "success",
                      icon : 'success_outline',
                      action : {
                          text : 'Okay',
                          onClick : (e, toastObject) => {
                          }
                      }
                  }).goAway(2000);
                  // setTimeout(()=> {

                  // }, 2000);
              } else {

                  this.$toasted.show(response.data.message,{
                      type: "error",
                      icon : 'error_outline',
                      action : {
                          text : 'Cancel',
                          onClick : (e, toastObject) => {
                              toastObject.goAway(0);
                          }
                      }
                  }).goAway(10000);
              }
            })
            .catch((error) => {
              if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            } else {
              this.isLoading = false;
              this.isSignInDisabled = false;
              this.$toasted.show(error,{
                  type: "error",
                  icon : 'error_outline',
                  action : {
                      text : 'Cancel',
                      onClick : (e, toastObject) => {
                          toastObject.goAway(0);
                      }
                  }
              }).goAway(10000);
            }
            });
        } else {
          for (
            let index = 0;
            index < Object.keys(this.$refs.form.inputs).length;
            index++
          ) {
            if (
              this.$refs.form.inputs[index].hasError == true &&
              this.$refs.form.inputs[index].type != undefined
            ) {
              this.$refs.form.inputs[index].focus();
              break;
            }
          }
        }
      },
      checkAll() {
            this.routes = [];
            this.routes.push("admin.me");
            this.allroutes.admin_permissions.forEach((route) => {
                this.routes.push(route.slug);
            });
        },
        unCheckAll() {
            this.routes = [];
            this.routes.push("admin.me");
        },
    //   clear(e) {
    //     this.$refs.form.reset();
    //   },
    },
  };
  </script>
