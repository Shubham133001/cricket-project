<template>
  <div style="width: 100%">
    <v-row class="ma-0 ml-0 mr-0">
      <v-img height="300px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
        :src="'/storage/images/' + selecteditem.image" lazy-src="https://picsum.photos/id/114/1920/450" cover>
        <!-- <v-row> -->
        <v-col cols="12" md="8" style="">
          <v-card-title class="text-h4 " style="color: #fff; width: 100%">
            {{ selecteditem.name }}
          </v-card-title>

          <v-card-subtitle class="" style="color: #fff; width: 100%">
            {{ selecteditem.description }}<br />
          </v-card-subtitle>
          <v-col cols="12" md="12" class="py-0" style="flex:none">
            <v-btn color="primary" :class="(isMobile) ? ' pull-left' : 'pull-left'" @click="opendirection"><v-icon
                small>mdi-directions</v-icon>Get
              Directions</v-btn>
          </v-col>
        </v-col>
        <!-- <v-col cols="12" md="4"> -->
        <!-- <Mapview ref="mapview" v-if="!isMobile"></Mapview> -->
        <!-- <v-btn color="primary" :class="(isMobile) ? 'mt-2 ml-4' : 'mt-2'" @click="opendirection"><v-icon
                small>mdi-directions</v-icon>Get
              Directions</v-btn> -->
        <!-- <v-btn color="primary" class="mt-2" @click="opendirectionlocal"><v-icon small>mdi-map-marker</v-icon>See
              map</v-btn> -->
        <!-- </v-col> -->
        <!-- </v-row> -->
      </v-img>
    </v-row>
    <v-container>
      <v-row>
        <v-col cols="12" md="7">
          <v-card class="mx-auto">
            <v-date-picker v-model="timeperiod" label="Start Date" style="width: 100%" @change="getSlots" full-width
              :allowed-dates="getalloweddates"></v-date-picker>
          </v-card>
          <v-card class="mx-auto mt-2" :loading="loadingslots" v-if="timeperiod != ''">
            <v-card-title class="text-h5">Available Slots</v-card-title>
            <v-divider class="mt-0 pb-0"></v-divider>

            <v-card-text v-if="slots.length > 0">
              <v-list dense two-line>
                <v-list-item-group>

                  <v-list-item v-for="(slot, index) in  slots " :key="index">

                    <template v-slot:default="{ active }">
                      <v-list-item-content style="border-bottom: solid 1px #ececec">
                        <v-list-item-title>
                          <p class="text-h6 ma-0">
                            {{ slot.title }}
                            <span class="mr-3 ml-2" v-if="!isMobile" style="font-size: 14px; font-weight: 400px">({{
          slot.start_time + " - " + slot.end_time
        }})</span><v-chip v-if="!isMobile" :color="slot.bookings.length == 0
          ? 'green'
          : slot.bookings.length >=
            slot.bookings_allowed
            ? 'red'
            : 'orange'
          " small dark>Slot(s) {{ slot.bookings.length }}
                              /
                              {{ slot.bookings_allowed }}</v-chip>
                            <v-btn small color="primary"
                              v-if="(slot.bookings.length > 0 && slot.bookings.length <= slot.bookings_allowed) && !isMobile"
                              style="text-decoration: underline;" text @click="showbookings(slot)">Booked By</v-btn>
                          </p>
                          <v-spacer></v-spacer>
                        </v-list-item-title>
                        <v-list-item-subtitle v-if="isMobile">
                          {{ slot.start_time + ' - ' + slot.end_time }}<br />
                          <v-chip
                            :color="(slot.bookings.length == 0) ? 'green' : (slot.bookings.length >= slot.bookings_allowed) ? 'red' : 'orange'"
                            small dark class="mt-1">Slot(s) {{ slot.bookings.length }}
                            /
                            {{ slot.bookings_allowed }}</v-chip><br v-if="isMobile" />
                          <v-btn small color="primary"
                            v-if="(slot.bookings.length > 0 && slot.bookings.length <= slot.bookings_allowed) && isMobile"
                            style="text-decoration: underline;" text @click="showbookings(slot)">Booked By</v-btn>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                      <v-list-item-action>

                        <v-radio-group v-model="selection[index]" inline>
                          <v-radio label="Half" :disabled="slot.bookings.length >= slot.bookings_allowed"
                            :value="[slot]"></v-radio>
                          <v-radio label="Full"
                            :disabled="slot.bookings.length >= slot.bookings_allowed || slot.bookings.length > 0"
                            :value="[slot, slot]"></v-radio>
                        </v-radio-group>
                      </v-list-item-action>
                    </template>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-card-text>
            <v-card-text v-else>
              <p>No slots available for this date</p>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="5">
          <v-card v-if="selection.length > 0">
            <v-card-title class="text-h5" style="background: var(--v-primary-base); color: #fff">Booking
              Summary</v-card-title>
            <v-card-text>
              <p class="text-h5 mt-2">
                {{ selecteditem.name }}
                {{ this.timeperiod != "" ? " - " + fomartdate(this.timeperiod) + "" : ""
                }}
              </p>
              <v-divider class="mb-2"> </v-divider>

              <v-simple-table v-if="selection.length > 0">

                <tr>
                  <td>
                    <!-- <v-icon color="primary">mdi-clock</v-icon> -->
                    Selected Slots
                  </td>
                </tr>
                <tr>
                  <td>
                    <v-simple-table>
                      <thead>
                        <tr>
                          <th>Description</th>
                          <th>Advance Price</th>
                          <th>Price</th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr v-for="slot in fullselection" :key="slot.id">
                          <td v-if="slot != undefined">

                            {{ slot.title }}<br />
                            {{ slot.start_time + " - " + slot.end_time }}<br />
                            <v-btn text small color="red" @click="deletebooking(slot)" v-if="isMobile" style="">
                              Remove
                            </v-btn>
                          </td>
                          <td v-if="slot != undefined">
                            ₹{{ slot.advanceprice }} INR
                          </td>
                          <td v-if="slot != undefined">
                            ₹{{ slot.price }} INR

                          </td>
                          <td v-if="!isMobile">
                            <v-btn icon x-small fab color="red" @click="deletebooking(slot)">
                              <v-icon x-small>mdi-delete</v-icon>
                            </v-btn>
                          </td>


                        </tr>
                      </tbody>
                      <tfoot>

                        <tr>
                          <td>
                            <h3>Sub Total</h3>
                          </td>
                          <td>
                            <h3>₹{{ advanceprice }} INR</h3>
                          </td>
                          <td>
                            <h3>₹{{ totalprice }} INR</h3>
                          </td>
                        </tr>
                      </tfoot>
                    </v-simple-table>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <v-divider class="mt-2 pb-2"></v-divider>
                  </td>
                </tr>
                <!-- <tr>
                                    <td colspan="2">
                                        <h3 style="float: left">Slot's Payment</h3>
                                        <span style="float: right">{{ totalprice }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <v-divider class="mt-2 pb-2"></v-divider>
                                    </td>
                                </tr> -->

                <tr>
                  <td colspan="2">
                    <h3 style="float: left">Available Credits</h3>
                    <span style="float: right">₹{{ credits }} INR</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <v-divider class="mt-2 pb-2"></v-divider>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <h3 style="float: left">Total (Amount Payable)</h3>
                    <span style="float: right">₹{{ advanceprice }} INR</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <v-divider class="mt-2 pb-2"></v-divider>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <h3 style="float: left">Balance</h3>
                    <span style="float: right">₹{{
          totalprice - (advanceprice + credits)
        }} INR</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <v-divider class="mt-2 pb-2"></v-divider>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <h4>Payment Method</h4>
                    <v-radio-group v-model="gateway" row>
                      <v-radio :label="gateway" :value="gateway"
                        v-for="          gateway           in           gateways          " :key="gateway"></v-radio>
                    </v-radio-group>
                  </td>
                </tr>
              </v-simple-table>
            </v-card-text>
            <v-card-actions>
              <p class="pa-0 ma-0" style="width: 100%; text-align: center; color: #fff">

                <v-btn color="primary" v-if="!isUserlogin" @click.stop="openlogindialogfun" block large>
                  Proceed <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
                <v-btn color="primary" v-if="isUserlogin && selection.length > 0" block large @click.stop="bookslot">
                  Book Now <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </p>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
      <v-dialog v-model="showbooking" max-width="350">
        <v-card>
          <v-card-title class="text-h5">Bookings <v-spacer></v-spacer><v-icon dense small fab
              @click="showbooking = false">mdi-close</v-icon></v-card-title>
          <v-card-text>
            <v-list>
              <v-list-item v-for="booking in  bookings" :key="booking.id">
                <v-list-item-content>
                  <v-list-item-title>
                    <v-avatar size="70" color="#efefef" style="float: left">
                      <v-img :lazy-src="temimagecurrent" :src="'/storage/uploads/team/' + booking.team.image"
                        v-if="booking.team.image != '' && booking.team.image != null" class="align-center" />
                      <span class="headline" style="text-transform: capitalize" v-else>{{ booking.team.name.charAt(0)
                        }}</span>
                    </v-avatar>
                    <h3 style="float: left; clear: right; text-transform: capitalize;" class="mt-0 ml-1 text-h4">
                      {{ booking.team.name }}<br /><v-chip color="orange" dark style="font-family: 'Pacifico'">{{
          booking.team.designation }}</v-chip>
                    </h3>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-dialog>
      <!-- <v-navigation-drawer v-model="openlogindialog" app temporary right style="max-width: 450px;" width="80%">

                <v-img src="/images/sportsbg.png" lazy-src="https://picsum.photos/id/11/10/6" height="80px"
                    class="white--text align-start" style="opacity: 0.5;"
                    gradient="to bottom, rgba(255,255,255,.8), rgba(255,255,255,1)">
                    <v-btn icon @click="openlogindialog = false" class="black--text" style="float: right">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-img>
                <v-col cols="12" class="text-center">
                    <v-img src="/images/logo.png" max-width="150"></v-img>
                </v-col>


                <v-row v-if="haveaccount">
                    <v-col cols="12" md="12" class="pl-6 pr-6">
                        <v-text-field label="Email" v-model="booking.email" outlined></v-text-field>

                        <v-text-field label="Password" type="password" outlined
                            v-model="booking.password"></v-text-field>
                        <p class="text-center">Don't have an account? <v-btn text @click="haveaccount = false"
                                small>Register</v-btn>
                        </p>
                        <v-btn color="primary" @click="login" block>Login</v-btn>
                    </v-col>
                </v-row>
                <v-row v-else>
                    <v-col cols="12" md="12" class="pl-6 pr-6">
                        <v-text-field label="Name" v-model="booking.name" outlined></v-text-field>
                        <v-text-field label="Phone" v-model="booking.phone" outlined></v-text-field>
                        <v-text-field label="Email" v-model="booking.email" outlined></v-text-field>
                        <v-text-field label="Password" type="password" outlined
                            v-model="booking.password"></v-text-field>
                        <p class="text-center">Already have an account? <v-btn text @click="haveaccount = true"
                                small>Login</v-btn>
                        </p>
                        <v-btn color="primary" @click="register" block>Register</v-btn>
                    </v-col>
                </v-row>
                <v-img src="/images/loginfooterimg.png" class="mt-4"></v-img>
            </v-navigation-drawer> -->
    </v-container>
    <confirm ref="confirm"></confirm>
    <v-dialog v-model="mapdirection" max-width="80%">
      <v-card>
        <v-card-title class="text-h5">Direction <v-spacer></v-spacer><v-icon dense small fab
            @click="mapdirection = false">mdi-close</v-icon></v-card-title>
        <v-card-text>
          <Mapdirection ref="Mapdirection"></Mapdirection>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import axios from "axios";
import moment, { duration } from "moment";
import confirm from "@/components/common/Confirm.vue";
import { EventBus } from "../../event-bus.js";
import Mapview from "@/components/common/Mapview.vue";
import { mapState, mapActions } from "vuex";
import Mapdirection from "@/components/common/Mapdirection.vue";
export default {
  components: {
    confirm,
    Mapview,
    Mapdirection
  },
  data() {
    return {
      isMobile: false,
      isUserlogin: (localStorage.getItem('userdetails')) ? true : false,
      openlogindialog: false,
      haveaccount: true,
      bookingform: false,
      credits: 0,
      slots: [],
      days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
      time: [],
      bookings: [],
      selection: [],
      fullselection: [],
      selecteditem: [],
      timeperiodold: moment().format('YYYY-MM-DD'),
      timeperiod: moment().format('YYYY-MM-DD'),
      showbooking: false,
      newbookings: [],
      bookfull: [],
      mapdirection: false,
      booking: {
        name: '',
        phone: '',
        email: '',
        password: ''
      },
      advanceprice: 0,
      totalprice: 0,
      userdetails: localStorage.getItem('userdetails'),
      loadingslots: false,
      team: {
        id: 0,
        name: '',
        designation: '',
        experience: '',
        description: '',
        image: ''
      },
      gateways: [],
      gateway: 'Phonepe',
    }

  },
  watch: {
    selection: function (val) {
      // console.log(val, 'selection')
      // if (val != null) {
      //   this.addbookings(val);
      // }
      let newselection = [];
      this.selection.forEach((slot) => {
        slot.forEach((slot) => {
          newselection.push(slot);
        });
      });
      this.fullselection = newselection;
    },
    fullselection: function (val) {
      if (val != null) {
        this.addbookings();
      }
    }
  },
  mounted() {
    this.getcategory();
    this.getuserteam();
    this.getusercredits();
    this.timeperiod = '';//  moment().format('YYYY-MM-DD');
    // this.selection
    // this.getSlots();
    this.getgateways();
  },
  created() {
    this.isMobile = this.$vuetify.breakpoint.mobile;
    EventBus.$on("isUserLogin", (status) => {
      this.isUserlogin = status;
      this.userdetails = localStorage.getItem("userdetails");
      this.getuserteam();
      this.getusercredits();
    });
  },
  methods: {
    fomartdate(date) {
      return moment(date).format("DD MMM YYYY (dddd)");
    },
    opendirection() {
      // parse string to url
      // let addr = encodeURIComponent(this.selecteditem.name + ' ' + this.selecteditem.location);
      // let latlng = JSON.parse(this.selecteditem.location_data).lat + ',' + JSON.parse(this.selecteditem.location_data).lng;
      let newaddr = 'https://www.google.com/maps/dir//' + this.selecteditem.location + ',14z/'
      window.open(newaddr, '_blank');
    },
    opendirectionlocal() {
      // parse string to url
      // let addr = encodeURIComponent(this.selecteditem.location);
      let latlng = JSON.parse(this.selecteditem.location_data).lat + ',' + JSON.parse(this.selecteditem.location_data).lng;
      // let newaddr = 'https://www.google.com/maps/dir//' + addr + '/@' + latlng + ',14z/'
      this.$router.push({
        name: 'direction',
        query: {
          lat: JSON.parse(this.selecteditem.location_data).lat,
          lng: JSON.parse(this.selecteditem.location_data).lng
        }
      });
    },
    ...mapActions("app", ["getStoreData", "setUserdetails", "getUserLogin"]),
    async deletebooking(item) {

      const ok = await this.$refs.confirm.open({
        title: "Are you sure?",
        message: "Are you sure you want to delete this booking?",
        okButtonText: "Yes",
        cancelButtonText: "No",
        options: {
          color: "error",
          width: 290,
          zIndex: 200,
        },
        icon: "mdi-delete",
      });
      if (ok) {
        let index = this.fullselection.indexOf(item);
        this.fullselection.splice(index, 1);
        // check in selection array and splice
        this.selection.forEach((slot, index) => {
          slot.forEach((slot, index1) => {
            if (slot.id == item.id) {
              let mainindex = this.selection[index].indexOf(slot);
              this.selection[index].splice(mainindex, 1);
            }
          });

        });
      }
    },
    openlogindialogfun() {
      EventBus.$emit("openlogindialog", true);
    },

    async getcategory() {
      await axios
        .get("/api/category/" + this.$route.params.id)
        .then((response) => {
          if (response.data.success) {
            let category = response.data.category;
            this.selecteditem = category;
            let location = category.location_data;

            // this.$refs.mapview.getlocation(location);
            // this.$refs.Mapdirection.loadmap();
            // this.availabledays = category.slots[0].days;
            this.getSlots();
          } else {
            console.log(response.data.message);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSlots() {
      this.loadingslots = true;
      if (this.selection.length > 0) {
        const ok = await this.$refs.confirm.open({
          title: "Are you sure?",
          message:
            "Are you sure you want to change the date? It will clear all your selected slots.",
          okButtonText: "Yes",
          cancelButtonText: "No",
          options: {
            color: "error",
            width: 290,
            zIndex: 200,
          },
          icon: "mdi-delete",
        });
        if (ok) {
          let selectedslotsid = this.selection.map((slot) => slot.id);
          if (selectedslotsid.length > 0) {
            selectedslotsid.forEach((slotid) => {
              this.slots.forEach((slot) => {
                this.bookfull[slot.id] = false;
                if (slot.id == slotid) {
                  slot.bookings.pop();
                }
              });
            });
          }
          this.timeperiodold = this.timeperiod;
          this.selection = [];
          this.fullselection = [];
          this.newbookings = [];
          this.advanceprice = 0;
          this.totalprice = 0;
        } else {
          this.timeperiod = this.timeperiodold;
          this.loadingslots = false;
          return;
        }
        await axios
          .post("/api/slots1", {
            id: this.selecteditem.id,
            date: this.timeperiod,
            day: new Date(this.timeperiod).getDay(),
          })
          .then((response) => {
            if (response.data.success) {
              this.slots = response.data.slots;
              // add dummy bookings
              // this.slots.forEach(slot => {

              // });
            } else {
              console.log(response.data.message);
            }
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        this.timeperiodold = this.timeperiod;
        // console.log(this.timeperiod)
        // timeperiod is less than today
        if (moment(this.timeperiod).isBefore(moment().format("YYYY-MM-DD"))) {
          this.$toasted.show("Please select a valid date", {
            type: "error",
            duration: 3000,
          });
          return;
        }
        this.newbookings = [];
        this.advanceprice = 0;
        this.totalprice = 0;
        this.selection = [];
        console.log(this.selection, 'selection befoe api')
        await axios
          .post("/api/slots1", {
            id: this.selecteditem.id,
            date: this.timeperiod,
            day: new Date(this.timeperiod).getDay(),
          })
          .then((response) => {
            if (response.data.success) {
              this.slots = response.data.slots;
              // add dummy bookings
              // this.slots.forEach(slot => {

              // });
            } else {
              console.log(response.data.message);
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
      this.loadingslots = false;
      console.log(this.selection, 'selection after api')
    },
    getalloweddates(val) {
      // check if val is less than today
      if (moment(val).isBefore(moment().format("YYYY-MM-DD"))) {
        return false;
      }
      return true;
      // check if val is in available days
      // if (this.selecteditem.length > 0) {
      //     if (this.selecteditem.slots[0].days.includes(new Date(val).getDay())) {
      //         return true;
      //     }
      // }
    },
    showbookingform() {
      // check if user is logged in
      if (this.userdetails) {
        this.bookingform = true;
        this.showbooking = false;
      } else {
        this.haveaccount = true;
      }
      this.bookingform = true;
      this.showbooking = false;
    },
    addbookings() {

      this.advanceprice = 0;
      this.totalprice = 0;
      this.newbookings = [];
      this.fullselection.forEach((slot) => {
        this.advanceprice += parseInt(slot.advanceprice);
        this.totalprice += parseInt(slot.price);
        // this.newbookings.push({
        //   slot_id: slot.id,
        //   advanceprice: parseInt(slot.advanceprice),
        //   price: parseInt(slot.price),
        //   date: this.timeperiod,
        //   category_id: this.selecteditem.id,
        // });
      });

    },
    getuserteam() {
      if (localStorage.getItem("userdetails") == null) {
        return;
      }
      axios.get("/api/user/getuserteam").then((response) => {
        this.team = response.data.team;
        this.temimagecurrent = this.team.image;
      });
    },

    getusercredits() {
      if (localStorage.getItem('userdetails') == null) {
        return;
      }
      axios.get('/api/user/me')
        .then(response => {
          this.credits = response.data.user.credits;
        })
    },
    bookslot() {
      // console.log(this.selection);
      // console.log(this.selecteditem);
      // console.log(this.timeperiod);
      // console.log(this.booking);
      if (this.team.id == 0) {
        this.$toasted.show('Please create a team', {
          type: 'error',
          duration: 3000
        });
        return;
      }
      if (this.gateway == '') {
        this.$toasted.show('Please select a payment gateway', {
          type: 'error',
          duration: 3000
        });
        return;
      }
      // availableCredit =  this.totalprice - (this.advanceprice + this.credits)
      // array merge selection and fullselection
      this.newbookings = this.selection.concat(this.fullselection);
      console.log(this.newbookings);
      axios.post('/api/addbooking', {
        slot: this.fullselection,
        category: this.selecteditem,
        date: this.timeperiod,
        credit: this.credits,
        user: JSON.parse(this.userdetails),
        team_id: this.team.id,
        gateway: this.gateway
      })
        .then(response => {
          if (response.data.success) {
            if (response.data.data['invoice_id'] != null && response.data.data['invoice_id'] != 0) {
              this.$router.push({
                name: 'payinvoice',
                params: {
                  id: response.data.data['invoice_id']
                }
              });
            }
            this.$toasted.show(response.data.message, {
              type: 'success'
            });
            this.bookingform = false;
          }
          else {
            this.$toasted.show(response.data.message, {
              type: 'error'
            });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    showbookings(slot) {
      let bookings = slot.bookings;
      this.bookings = bookings;
      this.showbooking = true;
    },
    selectdate() {
      this.slots = [];
      this.slots = this.selecteditem.slots;


      // this.getUserLogin();
    },
    getgateways() {
      axios.get('/api/getgateways').then(response => {
        if (response.data.success) {
          this.gateways = response.data.gateways;
        }
      });
    },

  }
}



</script>
<style>
.theme--light.v-calendar-weekly {
  border: none !important;
}

.v-calendar-weekly__head {
  background: #efefef;
  line-height: 30px;
  border: none;
}

.theme--light.v-calendar-weekly .v-calendar-weekly__day {
  border: none;
}

.theme--light.v-calendar-weekly .v-calendar-weekly__head-weekday {
  border: none;
}

.v-present .v-btn {
  background-color: transparent !important;
  border: solid 1px #000 !important;
  color: #000 !important;
}

.v-present .theme--light.v-btn:focus {
  color: #fff !important;
  background: #0096c7 !important;
}

.v-present .theme--light.v-btn:focus::before {}

.v-future .v-btn::before {
  background-color: #0096c7 !important;
}

.v-future .theme--light.v-btn:focus {
  color: #fff !important;
}

.v-future .theme--light.v-btn:focus::before {
  opacity: 0.8 !important;
}

table tr td {
  padding: 0px 5px;
}

table tr {
  border-bottom: 1px solid #202020;
}

.v-navigation-drawer__content {
  padding: 0px !important;
}

.v-label {
  font-size: 16px;
  margin-left: 10px;
}

@media screen and (max-width: 600px) {

  table td,
  table th {
    padding: 0px 5px !important;
  }
}
</style>