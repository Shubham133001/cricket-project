<template>
  <div>
    <v-app-bar flat height="80">
      <v-container class="py-0 px-0 px-sm-2 fill-height">

        <img :src="storeDetails.logo" height="36" @click="gotomain" />
        <h1 v-if="storeDetails.logo == null" class="text-sm-h6 text-xs-h6 text-lg-h3" @click="gotomain">{{
          storeDetails.name }}</h1>


        <v-spacer></v-spacer>

        <div class="d-none d-md-block">
          <router-link to="/" class="text-decoration-none">
            <v-btn text>Home</v-btn>
          </router-link>
          <router-link to="/about" class="text-decoration-none">
            <v-btn text>About</v-btn>
          </router-link>
          <router-link to="/categories" class="text-decoration-none">
            <v-btn text>Book Now</v-btn>
          </router-link>
          <router-link to="/contactus" class="text-decoration-none">
            <v-btn text>Contact</v-btn>
          </router-link>
          <v-menu offset-y left transition="slide-y-transition" v-if="isUserlogin == true">
            <template v-slot:activator="{ on }">
              <v-btn icon class="elevation-2" v-on="on">
                <v-badge color="success" dot bordered offset-x="10" offset-y="10">
                  <v-avatar size="40">
                    <v-img src="/images/avatars/avatar1.svg"></v-img>
                  </v-avatar>
                </v-badge>
              </v-btn>
            </template>
            <!-- user menu list -->
            <v-list dense nav>
              <v-list-item @click="myteam">
                <v-list-item-icon>
                  <v-icon small>mdi-account-group-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>My Team</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item @click="profile">
                <v-list-item-icon>
                  <v-icon small>mdi-account-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Profile</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item @click="bookings">
                <v-list-item-icon>
                  <v-icon small>mdi-calendar-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>My Bookings</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item @click="invoice">
                <v-list-item-icon>
                  <v-icon small>mdi-calendar</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>My Invoice</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item @click="logout">
                <v-list-item-icon>
                  <v-icon small>mdi-logout-variant</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>{{ $t("menu.logout") }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-btn text @click="openlogindialog = true" v-else><v-avatar color="" x-small
              style="min-width: 28px; max-width: 28px" class="mr-1">
              <v-icon dark> mdi-account-circle </v-icon>
            </v-avatar>
            Login</v-btn>
        </div>
        <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer" class="d-md-none
          d-block"></v-app-bar-nav-icon>
      </v-container>
    </v-app-bar>
    <v-navigation-drawer v-model="openlogindialog" app temporary right style="max-width: 450px" width="80%">
      <v-img src="/images/sportsbg.png" lazy-src="https://picsum.photos/id/11/10/6" height="80px"
        class="white--text align-start" style="opacity: 0.5"
        gradient="to bottom, rgba(255,255,255,.8), rgba(255,255,255,1)">
        <v-btn icon @click="openlogindialog = false" class="black--text" style="float: right">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-img>
      <v-col cols="12" class="text-center">
        <v-img :src="storeDetails.logo" max-width="150"></v-img>
        <!-- <img :src="storeDetails.logo" height="36" /> -->
        <h1 v-if="storeDetails.logo == null" class="text-sm-h6 text-xs-h6 text-lg-h3">{{ storeDetails.name }}</h1>
      </v-col>

      <v-row v-if="haveaccount">
        <v-col cols="12" md="12" class="pl-6 pr-6">
          <v-text-field label="Email" v-model="booking.email" outlined></v-text-field>

          <v-text-field label="Password" type="password" outlined v-model="booking.password"></v-text-field>
          <p class="text-center">
            Don't have an account?
            <v-btn color="primary" text style="text-decoration: underline" @click="haveaccount = false">Register Your
              Team</v-btn>
          </p>
          <v-btn color="primary" @click="login" block>Login</v-btn>
          <v-btn :disabled="isSignUpDisabled" class="mb-2 red mt-2 lighten-1 white--text" block
            @click="redirectToGoogleAuth()">
            <v-icon small left>mdi-google</v-icon>
            Signin with Google
          </v-btn>
          <div class="text-center">
            <v-btn text class="mt-2" @click="haveaccount = false; forgotpassword = true">Forgot Password?</v-btn>
          </div>
        </v-col>
      </v-row>
      <v-row v-else-if="forgotpassword">
        <v-col cols="12" md="12" class="pl-6 pr-6">
          <v-text-field label="Email" v-model="booking.email" outlined></v-text-field>
          <v-btn color="primary" block @click="sendemailforgotpassword" :loading="sendinglink">Send Reset Link</v-btn>
          <div class="text-center block">
            <v-btn text class="mt-2" @click="haveaccount = true; forgotpassword = false">Back to Login</v-btn>
          </div>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col cols="12" md="12" class="pl-6 pr-6">
          <v-text-field label="Name" v-model="booking.name" outlined required></v-text-field>
          <v-text-field label="Phone" v-model="booking.phone" type="number" outlined></v-text-field>
          <v-text-field label="Team name" v-model="booking.team.name" outlined required></v-text-field>
          <v-select label="Skill Level" v-model="booking.team.designation" outlined :items="designations"
            required></v-select>
          <v-text-field label="Email" v-model="booking.email" outlined required></v-text-field>
          <v-text-field label="Password" type="password" outlined v-model="booking.password" required></v-text-field>
          <p class="text-center">
            Already have an account?
            <v-btn color="primary" text style="text-decoration: underline" @click="haveaccount = true">Login Your
              Team</v-btn>
          </p>
          <v-btn color="primary" @click="register" block>Register</v-btn>
          <v-btn :disabled="isSignUpDisabled" class="mb-2 red mt-2 lighten-1 white--text" block
            @click="redirectToGoogleAuth()">
            <v-icon small left>mdi-google</v-icon>
            Signup with Google
          </v-btn>
        </v-col>
      </v-row>

      <v-img src="/images/loginfooterimg.png" class="mt-4"></v-img>
    </v-navigation-drawer>
    <v-navigation-drawer v-model="drawer" app temporary left style="max-width: 450px" width="80%">
      <v-col cols="12">
        <router-link to="/" class="d-flex align-center text-decoration-none mr-2">
          <img :src="storeDetails.logo" height="36" />
          <h1 v-if="storeDetails.logo == null">{{ storeDetails.name }}</h1>
        </router-link>
      </v-col>
      <v-col cols="12" class="text-center" v-if="isUserlogin == true">
        <v-badge color="success" dot bordered offset-x="10" offset-y="10">
          <v-avatar size="100">
            <v-img src="/images/avatars/avatar1.svg"></v-img>
          </v-avatar>
        </v-badge>
        <h3 class="text-h5 text-lg-h4 font-weight-bold">{{ (userdetails != null) ? userdetails.name : '' }} <v-btn icon
            small to="/me"><v-icon small>mdi-pencil</v-icon></v-btn></h3>
      </v-col>
      <v-col cols="12" class="mt-0 pt-0">
        <v-list>
          <v-list-group prepend-icon="mdi-account" v-if="isUserlogin == true">
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>Account</v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item to="/me">
              <v-list-item-content>
                <v-list-item-title>Profile</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item to="/mybookings">
              <v-list-item-content>
                <v-list-item-title>My Bookings</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item to="/myinvoice">
              <v-list-item-content>
                <v-list-item-title>My Invoice</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click="logout">
              <v-list-item-content>
                <v-list-item-title>Logout</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
        </v-list>
        <v-list-item to="/">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/about">
          <v-list-item-icon>
            <v-icon>mdi-information-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>About</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/categories">
          <v-list-item-icon>
            <v-icon>mdi-view-list</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Categories</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/contactus">
          <v-list-item-icon>
            <v-icon>mdi-phone</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Contact us</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item @click="drawer = false; openlogindialog = true" v-if="isUserlogin == false">
          <v-list-item-icon>
            <v-icon>mdi-account-circle</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Login</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-col>
    </v-navigation-drawer>
    <v-main>
      <router-view :key="$route.fullPath"></router-view>

      <v-footer color="transparent">
        <v-container class="py-5">
          <v-row style="display: flex; justify-content: center; align-items: top">
            <v-col cols="12" md="4">
              <div class="">
                <img :src="storeDetails.logo" height="36" />
                <h1 v-if="storeDetails.logo == null">{{ storeDetails.name }}</h1>
                <div v-html="description(aboutexcerpts)"></div>
              </div>
            </v-col>
            <v-col cols="12" md="4">
              <div class="text-h6 text-lg-h5 font-weight-bold">
                Contact Information
              </div>
              <div style="width: 80px; height: 2px" class="mb-5 mt-1 primary" />
              <div class="d-flex mb-2 font-weight-bold" v-if="storeDetails.address != ''">
                <v-icon color="primary lighten-1" class="mr-2">mdi-map-marker-outline</v-icon>
                {{ storeDetails.address }}
              </div>
              <div class="d-flex mb-2 font-weight-bold" v-if="storeDetails.contact != ''">
                <v-icon color="primary lighten-1" class="mr-2">mdi-phone-outline</v-icon>
                <a href="#" class="text-decoration-none text--primary">Mobile: {{ storeDetails.contact }}</a>
              </div>
            </v-col>
            <v-col cols="12" md="4">
              <div class="text-h6 text-lg-h5 font-weight-bold">
                Useful Links
              </div>
              <div style="width: 80px; height: 2px" class="mb-4 mt-1 primary" />
              <router-link :to="link.to" class="text-decoration-none" v-for="link in links" :key="link.label"
                style="width: 100%; float: left">
                <v-btn text>{{ link.label }}</v-btn>
              </router-link>

            </v-col>
          </v-row>
          <v-divider class="my-3"></v-divider>
          <div class="text-center caption">
            Made with <v-icon color="red" small>mdi-heart</v-icon> by <a href="https://whmcssmarters.com"
              target="_blank" class="text-decoration-none">WHMCSSmarters</a>
          </div>
        </v-container>
      </v-footer>
    </v-main>
  </div>
</template>
<script>
import config from "@/configs";
import ToolbarUser from "../components/toolbar/ToolbarUser";
import { EventBus } from "../event-bus.js";
import { mapState, mapActions } from "vuex";
export default {
  components: {
    ToolbarUser,
  },
  data() {
    return {
      isSignUpDisabled: false,
      drawer: false,
      sendinglink: false,
      currentYear: new Date().getFullYear(),
      isUserlogin: localStorage.getItem("userdetails") ? true : false,
      haveaccount: true,
      forgotpassword: false,
      aboutexcerpts: '',
      openlogindialog: false,
      userdetails: localStorage.getItem("userdetails")
        ? JSON.parse(localStorage.getItem("userdetails"))
        : null,
      isAdmin: false,
      booking: {
        email: "",
        password: "",
        name: "",
        phone: "",
        team: {
          name: "",
          designation: "",
          experience: "",
          description: "",
        },
      },
      designations: [
        {
          text: "Beginner",
          value: "Beginner",
        },
        {
          text: "Intermediate",
          value: "Intermediate",
        },
        {
          text: "Expert",
          value: "Expert",
        },
      ],

      loading: false,
      storeDetails: {
        name: "",
        address: "",
        contact: "",
        email: "",
        logo: "",
        phone: "",
      },
      config,
      links: [
        {
          label: "Terms & Conditions",
          to: "/terms",
        },
        {
          label: "Refund & Privacy Policy",
          to: "/privacy",
        },
        {
          label: "Categories",
          to: "/categories",
        },
        // {
        //   label: "Documentation",
        //   to: "#",
        // },
        // {
        //   label: "News",
        //   to: "#",
        // },
        // {
        //   label: "FAQ",
        //   to: "#",
        // },
        // {
        //   label: "About us",
        //   to: "#",
        // },
        // {
        //   label: "Carrers",
        //   to: "#",
        // },
        // {
        //   label: "Press",
        //   to: "#",
        // },
      ],
    };
  },
  created() {
    EventBus.$on("isUserLogin", (status) => {
      this.isUserlogin = status;
      console.log("isUserLogin", status);
    });
  },
  methods: {
    ...mapActions("app", ["getStoreData", "setUserdetails", "getUserLogin"]),
    gotomain() {
      window.location.href = "https://thesportswala.com";
    },
    description(data) {
      return this.$striphtml(data);
    },
    myteam() {
      this.$router.push({
        name: "userteam",
      });
    },
    redirectToGoogleAuth() {
      let baseurl = window.location.origin;
      console.log(JSON.parse(localStorage.getItem("store")).clientid)
      let $clientid = JSON.parse(localStorage.getItem("store")).clientid;
      let $redirecturi = baseurl + "/user/auth/google";
      window.location.href = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${$clientid}&redirect_uri=${$redirecturi}&response_type=code&scope=email+profile`;

    },
    async login() {
      this.loading = true;
      await axios.post("/api/user/signin", this.booking).then((response) => {
        if (response.data.success) {
          localStorage.setItem(
            "userdetails",
            JSON.stringify(response.data.user)
          );
          this.userdetails = response.data.user;
          localStorage.setItem("token", response.data.token);
          this.isUserlogin = true;
          EventBus.$emit("isUserLogin", true);
          this.openlogindialog = false;
          this.$toasted.show(response.data.message, {
            type: "success",
            duration: 5000,
          });
          // this.$router.push({
          //   path: "/",
          // });
          // reload the vue
          // location.reload();
        } else {
          this.$toasted.show(response.data.message, {
            type: "error",
            duration: 5000,
          });
        }
        this.loading = false;
      });
    },
    bookings() {
      this.$router.push({
        name: "mybookings",
      });
    },
    invoice() {
      this.$router.push({
        name: "myinvoice",
      });
    },
    register() {
      this.loading = true;
      axios
        .post("/api/user/signup", this.booking)
        .then((response) => {
          if (response.data.success) {
            localStorage.setItem(
              "userdetails",
              JSON.stringify(response.data.user)
            );
            localStorage.setItem("token", response.data.token);
            this.isUserlogin = true;
            this.$store.commit("app/setIsUserlogin", true);
            this.isUserlogin = true;
            this.openlogindialog = false;
            this.$toasted.show(response.data.message, {
              type: "success",
              duration: 5000,
            });
            EventBus.$emit("isUserLogin", true);
            // this.$router.push({
            //   path: "/",
            // });
            // reload the vue
            // location.reload();
          } else {
            this.$toasted.show(response.data.message, {
              type: "error",
              duration: 5000,
            });
          }
          this.loading = false;
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            duration: 5000,
          });
          this.loading = false;
        });
    },
    getStoreData() {
      this.isAdmin = localStorage.getItem("adminData") ? true : false;
      axios.get("/api/store").then((response) => {
        this.storeDetails = response.data.storeDetails;
        // set store details to vuex
        this.$store.commit("app/setStoreDetails", response.data.storeDetails);
      });
    },
    logout() {
      axios
        .post("/api/user/signout", {})
        .then((response) => {
          localStorage.removeItem("userdetails");
          localStorage.removeItem("token");
          this.$toasted.show(response.data.message, {
            type: "success",
            duration: 5000,
          });
          // this.$router.push({
          //   path: "/",
          // });
          // reload the vue
          location.reload();
        })
        .catch((error) => {
          localStorage.removeItem("userdetails");
          localStorage.removeItem("token");
          this.$toasted.show(response.data.message, {
            type: "success",
            duration: 5000,
          });
          // reload the vue
          //location.reload();
        });
    },
    openPage(page) {
      if (page == "bookappointment") {
        this.getalldoctors();
      }
      this.$router.push({
        name: page,
      });
    },
    openAdmin() {
      this.$router.push({
        name: "admin-dashboard",
      });
    },

    profile() {
      this.$router.push({
        name: "myprofile",
      });
    },
    sendemailforgotpassword() {
      this.sendinglink = true;
      axios
        .post("/api/user/forgotpassword", this.booking)
        .then((response) => {
          if (response.data.success) {
            this.$toasted.show(response.data.message, {
              type: "success",
              duration: 5000,
            });
            this.haveaccount = true;
            this.forgotpassword = false;
          } else {
            this.$toasted.show(response.data.message, {
              type: "error",
              duration: 5000,
            });
          }
          this.sendinglink = false;
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            duration: 5000,
          });
          this.sendinglink = false;
        });
    },
  },
  created() {
    EventBus.$on("openlogindialog", (status) => {
      this.openlogindialog = status;
    });
  },
  mounted() {
    this.getStoreData();
    this.getUserLogin();
    //this.getPageData();
  }
};
</script>
