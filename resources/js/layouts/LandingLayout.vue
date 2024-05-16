<template>
  <div>
    <v-app-bar flat height="80">
      <v-container class="py-0 px-0 px-sm-2 fill-height">
        <router-link
          to="/"
          class="d-flex align-center text-decoration-none mr-2"
        >
          <img :src="storeDetails.logo" height="36" />
          <h1 v-if="storeDetails.logo == null">{{ storeDetails.name }}</h1>
        </router-link>

        <v-spacer></v-spacer>

        <div class="d-none d-md-block">
          <v-menu
            offset-y
            left
            transition="slide-y-transition"
            v-if="isUserlogin == true"
          >
            <template v-slot:activator="{ on }">
              <v-btn icon class="elevation-2" v-on="on">
                <v-badge
                  color="success"
                  dot
                  bordered
                  offset-x="10"
                  offset-y="10"
                >
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
          <v-btn text @click="openlogindialog = true" v-else
            ><v-avatar
              color=""
              x-small
              style="min-width: 28px; max-width: 28px"
              class="mr-1"
            >
              <v-icon dark> mdi-account-circle </v-icon>
            </v-avatar>
            Login</v-btn
          >
        </div>
      </v-container>
    </v-app-bar>
    <v-navigation-drawer
      v-model="openlogindialog"
      app
      temporary
      right
      style="max-width: 450px"
      width="80%"
    >
      <v-img
        src="/images/sportsbg.png"
        lazy-src="https://picsum.photos/id/11/10/6"
        height="80px"
        class="white--text align-start"
        style="opacity: 0.5"
        gradient="to bottom, rgba(255,255,255,.8), rgba(255,255,255,1)"
      >
        <v-btn
          icon
          @click="openlogindialog = false"
          class="black--text"
          style="float: right"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-img>
      <v-col cols="12" class="text-center">
        <v-img src="/images/logo.png" max-width="150"></v-img>
      </v-col>

      <v-row v-if="haveaccount">
        <v-col cols="12" md="12" class="pl-6 pr-6">
          <v-text-field
            label="Email"
            v-model="booking.email"
            outlined
          ></v-text-field>

          <v-text-field
            label="Password"
            type="password"
            outlined
            v-model="booking.password"
          ></v-text-field>
          <p class="text-center">
            Don't have an account?
            <v-btn text @click="haveaccount = false" small>Register</v-btn>
          </p>
          <v-btn color="primary" @click="login" block>Login</v-btn>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col cols="12" md="12" class="pl-6 pr-6">
          <v-text-field
            label="Name"
            v-model="booking.name"
            outlined
            required
          ></v-text-field>
          <v-text-field
            label="Phone"
            v-model="booking.phone"
            outlined
          ></v-text-field>
          <v-text-field
            label="Team name"
            v-model="booking.team.name"
            outlined
            required
          ></v-text-field>
          <v-select
            label="Team Skill"
            v-model="booking.team.designation"
            outlined
            :items="designations"
            required
          ></v-select>
          <v-text-field
            label="Email"
            v-model="booking.email"
            outlined
            required
          ></v-text-field>
          <v-text-field
            label="Password"
            type="password"
            outlined
            v-model="booking.password"
            required
          ></v-text-field>
          <p class="text-center">
            Already have an account?
            <v-btn text @click="haveaccount = true" small>Login</v-btn>
          </p>
          <v-btn color="primary" @click="register" block>Register</v-btn>
        </v-col>
      </v-row>
      <v-img src="/images/loginfooterimg.png" class="mt-4"></v-img>
    </v-navigation-drawer>
    <v-main>
      <router-view :key="$route.fullPath"></router-view>

      <v-footer color="transparent">
        <v-container class="py-5">
          <v-row
            style="display: flex; justify-content: center; align-items: center"
          >
            <v-col cols="12" md="4">
              <div class="text-h6 text-lg-h5 font-weight-bold">
                Contact Information
              </div>
              <div style="width: 80px; height: 2px" class="mb-5 mt-1 primary" />
              <div
                class="d-flex mb-2 font-weight-bold"
                v-if="storeDetails.address != ''"
              >
                <v-icon color="primary lighten-1" class="mr-2"
                  >mdi-map-marker-outline</v-icon
                >
                {{ storeDetails.address }}
              </div>
              <div
                class="d-flex mb-2 font-weight-bold"
                v-if="storeDetails.contact != ''"
              >
                <v-icon color="primary lighten-1" class="mr-2"
                  >mdi-phone-outline</v-icon
                >
                <a href="#" class="text-decoration-none text--primary"
                  >Mobile: {{ storeDetails.contact }}</a
                >
              </div>
            </v-col>
          </v-row>
          <v-divider class="my-3"></v-divider>
          <div class="text-center caption">
            © Indielayer 2024. All Rights Reserved
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
      isUserlogin: localStorage.getItem("userdetails") ? true : false,
      haveaccount: true,
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
          text: "Beginner (1 - 50 Machtes)",
          value: "Beginner",
        },
        {
          text: "Intermediate (50 - 200 Matches)",
          value: "Intermediate",
        },
        {
          text: "Expert (200+ Matches)",
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
          label: "Overview",
          to: "#",
        },
        {
          label: "Features",
          to: "#",
        },
        {
          label: "Pricing",
          to: "#",
        },
        {
          label: "Documentation",
          to: "#",
        },
        {
          label: "News",
          to: "#",
        },
        {
          label: "FAQ",
          to: "#",
        },
        {
          label: "About us",
          to: "#",
        },
        {
          label: "Carrers",
          to: "#",
        },
        {
          label: "Press",
          to: "#",
        },
      ],
    };
  },
  created() {
    EventBus.$on("isUserLogin", (status) => {
      this.isUserlogin = status;
    });
  },
  methods: {
    ...mapActions("app", ["getStoreData", "setUserdetails", "getUserLogin"]),
    myteam() {
      this.$router.push({
        name: "userteam",
      });
    },
    login() {
      this.loading = true;
      axios.post("/api/user/signin", this.booking).then((response) => {
        if (response.data.success) {
          localStorage.setItem(
            "userdetails",
            JSON.stringify(response.data.user)
          );
          localStorage.setItem("token", response.data.token);
          this.isUserlogin = true;
          EventBus.$emit("isUserLogin", true);
          this.openlogindialog = false;
          this.$toasted.show(response.data.message, {
            type: "success",
            duration: 5000,
          });
          this.$router.push({
            path: "/",
          });
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
            this.$router.push({
              path: "/",
            });
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
  },
  created() {
    EventBus.$on("openlogindialog", (status) => {
      this.openlogindialog = status;
    });
  },
  mounted() {
    this.getStoreData();
    this.getUserLogin();
  },
};
</script>