<template>
  <div style="width: 100%">
    <div>
      <div class="display-1">Add Slots</div>
      <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
    </div>
    <v-card>
      <!-- <v-card-title>
                <span class="headline">Add Slots</span>
            </v-card-title> -->
      <v-card-text>
        <v-row>
          <v-col cols="12" md="4">
            <v-select
              v-model="slotstype"
              :items="['Single Day', 'Multiple Days', 'Date Range']"
              label="Slots For"
              @change="timeperiod = ''"
            ></v-select>

            <v-text-field
              v-model="totalslots"
              label="Total Slots"
            ></v-text-field>
            <v-select
              v-model="availabledays"
              v-if="slotstype == 'Date Range'"
              :items="days"
              multiple
              label="Available Days"
              @change="updatecalendar"
            ></v-select>
            <v-btn color="primary" @click="addslot">Add</v-btn>
          </v-col>
          <v-col cols="12" md="4">
            <v-date-picker
              v-model="timeperiod"
              :allowed-dates="getalloweddates"
              label="Start Date"
              :events="events"
              v-if="slotstype == 'Single Day'"
            ></v-date-picker>
            <v-date-picker
              v-model="timeperiod"
              multiple
              :allowed-dates="getalloweddates"
              label="Start Date"
              :events="events"
              v-if="slotstype == 'Multiple Days'"
            ></v-date-picker>
            <!-- <v-date-picker
        v-model="selectedDates"
        multiple
        range
        @input="onDateSelected"
        :min="minDate"
        :max="maxDate"
      ></v-date-picker> -->
            <v-date-picker
              v-model="timeperiod"
              range
              :allowed-dates="getalloweddates"
              label="Start Date"
              :events="events"
              v-if="slotstype == 'Date Range'"
            ></v-date-picker>
          </v-col>
        </v-row>
        <v-form @submit.prevent="addslot">
          <v-simple-table v-if="slots.length > 0">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Slot</th>
                  <th class="text-left">Details</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(slot, index) in slots" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="2">
                        <v-text-field
                          v-model="slot.title"
                          label="Title"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="2">
                        <v-text-field
                          type="number"
                          v-model="slot.bookings_allowed"
                          label="Bookings Per Slot"
                          :max="2"
                          :min="1"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="2">
                        <v-select
                          v-model="slot.time_start"
                          :items="time"
                          label="Start Time"
                        ></v-select>
                      </v-col>
                      <v-col cols="12" md="2">
                        <v-select
                          v-model="slot.time_end"
                          :items="time"
                          label="End Time"
                          @change="checkslots"
                        ></v-select>
                      </v-col>
                      <v-col cols="12" md="2">
                        <v-text-field
                          type="number"
                          v-model="slot.advanceprice"
                          label="Advance Price"
                          :min="1"
                          value="1"
                          @focusout="checkslots"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="2">
                        <v-text-field
                          type="number"
                          :min="1"
                          v-model="slot.price"
                          label="Price"
                          value="0"
                          @focusout="checkslots"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <v-btn
                      color="primary"
                      @click="saveslots"
                      v-if="allgood == true"
                      >Save</v-btn
                    >
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
  data() {
    return {
      allgood: false,
      totalslots: 0,
      breadcrumbs: [
        {
          text: "Categories",
          disabled: false,
          to: "/admin/categories",
        },
        {
          text: "Add Slots",
        },
      ],
      slots: [],
      time: [
        "00:00 AM",
        "01:00 AM",
        "02:00 AM",
        "03:00 AM",
        "04:00 AM",
        "05:00 AM",
        "06:00 AM",
        "07:00 AM",
        "08:00 AM",
        "09:00 AM",
        "10:00 AM",
        "11:00 AM",
        "12:00 PM",
        "01:00 PM",
        "02:00 PM",
        "03:00 PM",
        "04:00 PM",
        "05:00 PM",
        "06:00 PM",
        "07:00 PM",
        "08:00 PM",
        "09:00 PM",
        "10:00 PM",
        "11:00 PM",
      ],
      date: new Date().toISOString().substr(0, 10),
      events: [],
      slotstype: "",
      bookedslots: [],
      startdate: "",
      enddate: "",
      timeperiod: [],
      allowedDates: "",
      availabledays: [0, 1, 2, 3, 4, 5, 6],
      selectedDates: [],
      minDate: "2024-01-01",
      maxDate: "2024-12-31",
      bookeddates: [],
      days: [
        {
          text: "Monday",
          value: 1,
        },
        {
          text: "Tuesday",
          value: 2,
        },
        {
          text: "Wednesday",
          value: 3,
        },
        {
          text: "Thursday",
          value: 4,
        },
        {
          text: "Friday",
          value: 5,
        },
        {
          text: "Saturday",
          value: 6,
        },
        {
          text: "Sunday",
          value: 0,
        },
      ],
    };
  },
  created() {
    this.getslots();
  },
  watch: {
    slotstype: function (val) {
      if (val == "Single Day") {
        this.timeperiod = "";
      } else if (val == "Multiple Days") {
        this.timeperiod = [];
      } else {
        this.timeperiod = [];
      }
    },
    timeperiod: function (val) {
      this.checkslots();
    },
  },
  methods: {
    async checkslots() {
      this.allgood = false;
      let slots = this.slots;
      // console.log(this.slots);
      let stoploop = false;
      // if (this.slotstype == 'Multiple Days' && this.timeperiod == '') {
      //     this.timeperiod.forEach(date => {
      //         if (this.bookeddates.includes(date)) {
      //             this.$toasted.show('Slot already exists for "' + date + '"', {
      //                 type: 'error',
      //                 duration: 2000
      //             });
      //             this.allgood = false;
      //             return false;
      //         }
      //     });
      // }
      const availableTimeSlots = this.bookedslots;
      var alltimes = [];
      slots.forEach((slot) => {
        let startdate = "";
        let enddate = "";
        if (this.slotstype == "Single Day") {
          startdate = this.timeperiod;
          enddate = this.timeperiod;
          if (startdate == "") {
            this.timeperiod = "";
            this.$toasted.show("Date not get selected, Please Select Again.", {
              type: "error",
              duration: 2000,
            });
            stoploop = true;
            this.allgood = false;
            return false;
          }
        } else if (this.slotstype == "Multiple Days") {
          this.timeperiod.forEach((date) => {
            startdate = date;
            enddate = date;
            this.bookedslots.forEach((bookedslot) => {
              let bookeddates = this.getdaysbetween(
                bookedslot.start_date,
                bookedslot.end_date
              );
              let bookedtimes = this.gethoursbetween(
                bookedslot.start_time,
                bookedslot.end_time
              );
              if (bookeddates.includes(date)) {
                bookedtimes.forEach((time) => {
                  if (stoploop) {
                    this.allgood = false;
                    return false;
                  }

                  if (
                    bookedtimes.includes(slot.start_time) ||
                    bookedtimes.includes(slot.end_time)
                  ) {
                    this.allgood = false;
                    this.$toasted.show(
                      'Slot already exists for "' +
                        date +
                        '" (' +
                        bookedslot.start_time +
                        " - " +
                        bookedslot.end_time +
                        ") ",
                      {
                        type: "error",
                        duration: 5000,
                      }
                    );
                    stoploop = true;
                    this.allgood = false;
                    return false;
                  }
                });
              }
            });
          });

          // if (new Date(startdate) > new Date(enddate)) {
          //     startdate = this.timeperiod[1];
          //     enddate = this.timeperiod[0];
          // }
          if (startdate == "" || enddate == "") {
            this.timeperiod = [];
            this.$toasted.show("Date not get selected, Please Select Again.", {
              type: "error",
              duration: 5000,
            });
            stoploop = true;
            this.allgood = false;
            return false;
          }
        } else {
          startdate = this.timeperiod[0];
          enddate = this.timeperiod[1];
          if (new Date(startdate) > new Date(enddate)) {
            startdate = this.timeperiod[1];
            enddate = this.timeperiod[0];
          }
          if (startdate == "" || enddate == "") {
            this.timeperiod = [];
            this.$toasted.show("Date not get selected, Please Select Again.", {
              type: "error",
              duration: 2000,
            });
            stoploop = true;
            this.allgood = false;
            return false;
          }
        }
        // cehck in existing slots if any slot exists

        let currentselectedates = this.getdaysbetween(startdate, enddate);

        if (slot.time_start != "" && slot.time_end != "") {
          if (
            moment(slot.time_start, "hh:mm A").format("HH:mm") >=
            moment(slot.time_end, "hh:mm A").format("H:mm")
          ) {
            this.$toasted.show("Start time should be less than end time", {
              type: "error",
              duration: 2000,
            });
            this.allgood = false;
            return false;
          } else {
            this.allgood = true;
          }
        }

        if(slot.advanceprice == ""){
          this.$toasted.show("Advance price required", {
            type: "error",
            duration: 2000,
          });
          this.allgood = false;
          return false;
        }else {
          this.allgood = true;
        }

        if(slot.price == ""){
          this.$toasted.show("Price  required", {
            type: "error",
            duration: 2000,
          });
          this.allgood = false;
          return false;
        }else {
          this.allgood = true;
        }

        if (slot.advanceprice > slot.price) {
          this.$toasted.show("Advance price should be less than price", {
            type: "error",
            duration: 2000,
          });
          this.allgood = false;
          return false;
        } else {
          this.allgood = true;
        }
        let currentselectedtimes = this.gethoursbetween(
          moment(slot.time_start, "hh:mm A").format("HH:mm"),
          moment(slot.time_end, "hh:mm A").format("H:mm")
        );
        let stoploop1 = false;
        currentselectedtimes.forEach((times) => {
          if (stoploop1) {
            return false;
          }
          if (alltimes.includes(times)) {
            this.$toasted.show(
              "Can not add Slot for (" +
                moment(slot.time_start, "hh:mm A").format("H:mm") +
                " - " +
                moment(slot.time_end, "hh:mm A").format("H:mm") +
                ") ",
              {
                type: "error",
                duration: 2000,
              }
            );
            stoploop1 = true;
            this.allgood = false;
          } else {
            alltimes.push(times);
          }
        });
        // console.log(alltimes);

        this.bookedslots.forEach((bookedslot) => {
          let bookeddates = this.getdaysbetween(
            bookedslot.start_date,
            bookedslot.end_date
          );
          let bookedtimes = this.gethoursbetween(
            bookedslot.start_time,
            bookedslot.end_time
          );
          currentselectedates.forEach((date) => {
            if (bookeddates.includes(date)) {
              currentselectedtimes.forEach((time) => {
                if (stoploop) {
                  this.allgood = false;
                  return false;
                }
                if (bookedtimes.includes(time)) {
                  this.allgood = false;
                  this.$toasted.show(
                    'Slot already exists for "' +
                      date +
                      '" (' +
                      bookedslot.start_time +
                      " - " +
                      bookedslot.end_time +
                      ") ",
                    {
                      type: "error",
                      duration: 2000,
                    }
                  );
                  stoploop = true;
                  this.allgood = false;
                  return false;
                } else {
                  this.allgood = true;
                }
              });
            }
          });
        });
        // check advance price less then price
        
         
      });
    },
    async getslots() {
    
      await axios
        .post("/api/admin/slots/list", { id: this.$route.params.id })
        .then((response) => {
          this.bookedslots = response.data.slots;
          this.bookedslots.forEach((slot) => {
            let startdate = new Date(slot.start_date)
              .toISOString()
              .substr(0, 10);
            let enddate = new Date(slot.end_date).toISOString().substr(0, 10);
            if (startdate > enddate) {
              startdate = new Date(slot.end_date).toISOString().substr(0, 10);
              enddate = new Date(slot.start_date).toISOString().substr(0, 10);
            }
            // get all dates in between start and end date
            let currentdate = new Date(startdate);
            let stopdate = new Date(enddate);
            while (currentdate <= stopdate) {
              // set format to yyyy-mm-dd
              let datetopush =
                currentdate.getFullYear() +
                "-" +
                ("0" + (currentdate.getMonth() + 1)).slice(-2) +
                "-" +
                ("0" + currentdate.getDate()).slice(-2);
              this.bookeddates.push(datetopush);
              this.events.push(new Date(datetopush));
              currentdate.setDate(currentdate.getDate() + 1);
            }
          });
        })
        .catch((error) => {
          this.$toasted.show("Error loading slots" + error, {
            type: "error",
          });
        });
    },
    getdaysbetween(start_date, end_date) {
      let startdate = new Date(start_date).toISOString().substr(0, 10);
      let enddate = new Date(end_date).toISOString().substr(0, 10);

      // get all dates in between start and end date
      let currentdate = new Date(startdate);
      let stopdate = new Date(enddate);
      let datesarray = [];
      while (currentdate <= stopdate) {
        // set format to yyyy-mm-dd
        let datetopush =
          currentdate.getFullYear() +
          "-" +
          ("0" + (currentdate.getMonth() + 1)).slice(-2) +
          "-" +
          ("0" + currentdate.getDate()).slice(-2);
        datesarray.push(datetopush);
        // this.events.push(new Date(datetopush));
        currentdate.setDate(currentdate.getDate() + 1);
      }
      return datesarray;
    },
    gethoursbetween(starttime, endtime) {
      let start = parseInt(starttime.split(":")[0]);
      let end = parseInt(endtime.split(":")[0]);
      let hours = [];
      for (let i = start; i <= end - 1; i++) {
        hours.push(i);
      }
      if (hours.length == 0) {
        for (let i = start; i <= end; i++) {
          hours.push(i);
        }
      }
      return hours;
    },
    addslot() {
      if (this.timeperiod == "") {
        this.$toasted.show("Please select time period", {
          type: "error",
          duration: 2000,
        });
        return false;
      }
      if (this.slotstype == "Date Range" && this.availabledays.length == 0) {
        this.$toasted.show("Please select available days", {
          type: "error",
          duration: 2000,
        });
        return false;
      }
      if (this.slotstype == "Date Range") {
        this.startdate = this.timeperiod[0];
        this.enddate = this.timeperiod[1];
        if (this.startdate > this.enddate) {
          this.startdate = this.timeperiod[1];
          this.enddate = this.timeperiod[0];
        }
      } else if (
        this.slotstype == "Single Day" ||
        this.slotstype == "Multiple Days"
      ) {
        this.startdate = this.timeperiod;
        this.enddate = this.timeperiod;
      }

      this.slots = [];
      for (let i = 0; i < this.totalslots; i++) {
        this.slots.push({
          time_start: "",
          time_end: "",
          price: 0,
          advanceprice: 0,
          bookings_allowed: 1,
        });
      }
    },
    // disable all monday in datepicker
    getalloweddates(val) {
      // get day from val
      if (moment(val).isBefore(moment().format("YYYY-MM-DD"))) {
        return false;
      }

      // let day = new Date(val).getDay();
      // // check if day is in available days

      // // else {
      // if (this.availabledays.includes(day)) {
      return true;
      // }

      // }
    },
    updatecalendar() {
      this.getalloweddates();
    },

    onDateSelected() {
      console.log(this.selectedDates);
    },

    async saveslots() {
      // this.slots.forEach((slot) => {
      // if (slot.advanceprice > slot.price) {
      //     this.$toasted.show("Advance price should be less than price", {
      //       type: "error",
      //       duration: 2000,
      //     });
      //     this.allgood = false;
      //     return false;
      //   } else {
      //     this.allgood = true;
      //   }

      //    });


      await axios
        .post("/api/admin/slots/add", {
          slots: this.slots,
          date: this.timeperiod,
          days: this.availabledays,
          id: this.$route.params.id,
          bookings_allowed: this.bookings_allowed,
          ismultiple: this.slotstype == "Multiple Days" ? true : false,
        })
        .then((response) => {
          this.$toasted.show("Slots added successfully", {
            type: "success",
            duration: 2000,
          });
          this.$router.push("/admin/category/slots/" + this.$route.params.id);
        })
        .catch((error) => {
          this.$toasted.show("Error adding slots", {
            type: "error",
            duration: 2000,
          });
        });
    },
  },
};
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

.v-present .theme--light.v-btn:focus::before {
}

.v-future .v-btn::before {
  background-color: #0096c7 !important;
}

.v-future .theme--light.v-btn:focus {
  color: #fff !important;
}

.v-future .theme--light.v-btn:focus::before {
  opacity: 0.8 !important;
}
</style>