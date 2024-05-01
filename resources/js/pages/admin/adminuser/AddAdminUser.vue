<template>
    <div class="d-flex flex-column flex-grow-1">
      <v-card>
        <v-card-title>
          Add Admin
          <v-spacer></v-spacer>
        </v-card-title>
        <v-form ref="form" v-model="isFormValid" lazy-validation>
          <div class="pa-4">
              <v-row>
                  <v-col cols="12" md="6">
                  <v-text-field
                      v-model="fullname"
                      :rules="nameRules"
                      :counter="100"
                      :label="$t('menu.fullname')"
                      required
                      outlined
                  ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                  <v-select
                      item-text="name"
                      item-value="id"
                      v-model.lazy="group"
                      :rules="requiredRules"
                      :items="adminGroupList"
                      :label="$t('menu.admingroup')"
                      required
                      outlined
                  ></v-select>
                  </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="email"
                  :rules="emailRules"
                  :label="$t('register.email')"
                  autocomplete="new-email"
                  required
                  outlined
                >
                </v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="password"
                  :rules="requiredRules"
                  :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="showPassword ? 'text' : 'password'"
                  :label="$t('register.password')"
                  name="password"
                  required
                  outlined
                  @click:append="showPassword = !showPassword"
                >
                </v-text-field>
              </v-col>
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
                Add
              </v-btn>
              &nbsp;
              <v-btn elevation="2" x-large @click="clear"> Clear </v-btn>

              &nbsp;
              <v-btn elevation="2" x-large @click="backtolist"> Cancel </v-btn>
              <v-spacer></v-spacer>
            </v-row>

              <v-checkbox
              v-model="status"
              :label="`Tick this box to enable this account`"
              persistent-hint
              hint=""
              outlined
              ></v-checkbox>
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
     // checkpermission.checkPermission(this.$router, "admin.add");
      this.getAdminGroup();
    },
    data: () => ({
      enablenotify: false,
      statusList: [
        { text: "Active", value: 1 },
        { text: "Inactive", value: 0 },
      ],
      showPassword: false,
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
        (v) => !!v || "Name is required",
        (v) => (v && v.length <= 100) || "Name must be less than 100 characters",
      ],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            v
          ) || "E-mail must be valid",
      ],
      requiredRules: [(v) => !!v || "Field is Required"],
    }),
    methods: {
       backtolist() {
        this.$router.go(-1);
      },
      getAdminGroup() {
        axios
          .get("/api/admin/getadmingroups")
          .then((response) => {
            this.adminGroupList = response.data.groups;
            this.group = this.adminGroupList[0].id;
            this.status = 1;
          })
          .catch((error) => {
            if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            } else {
            console.log(error);
            }
          });
      },
      submit(e) {
        if (this.$refs.form.validate()) {
          this.isLoading = true;
          this.isSignInDisabled = true;
          axios
            .post("/api/admin/addadminuser", {
              fullname: this.fullname,
              email: this.email,
              password: this.password,
              group: this.group,
              status: this.status,
            })
            .then((response) => {
              console.log(response);
              this.isLoading = false;
              this.isSignInDisabled = false;
              if (response.data.success == true) {
                  this.$toasted.show(response.data.message,{
                      type: "success",
                      icon : 'success_outline',
                      action : {
                          text : 'Okay',
                          onClick : (e, toastObject) => {
                              this.$router.push({
                                  name: "admin-users",
                              });
                          }
                      }
                  }).goAway(2000);
                  // setTimeout(()=> {
                      this.$router.push({
                          name: "admin-users",
                      });
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
      clear(e) {
        this.$refs.form.reset();
      },
    },
  };
  </script>
