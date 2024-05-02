<template>
    <div style="width: 100%">
        <v-row>
            <v-img height="300px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                :src="(selecteditem.image != 'null' || selecteditem.image != '') ? '/storage/images/' + selecteditem.image : 'https://dummyimage.com/400X400'"
                cover>
                <v-row>
                    <v-col cols="12" md="8" class=" d-flex align-end"
                        style="justify-content: flex-end; flex-direction: column;">
                        <v-card-title class="text-h4  pl-8" style="color: #fff; width: 100%;">
                            {{ selecteditem.name }}
                        </v-card-title>

                        <v-card-subtitle class="pl-8" style="color: #fff; width: 100%;">
                            {{ selecteditem.description }}<br />

                        </v-card-subtitle>
                    </v-col>
                    <v-col cols="12" md="4">
                        <Mapview ref="mapview"></Mapview>
                        <v-btn color="primary" class="mt-2" @click="opendirection"><v-icon
                                small>mdi-directions</v-icon>Get
                            Directions</v-btn>
                    </v-col>
                </v-row>


            </v-img>
        </v-row>
        <v-container>
            <v-row>
                <v-col cols="12" md="7">
                    <v-card class="mx-auto">
                        <v-date-picker v-model="timeperiod" label="Start Date" style="width: 100%" @change="getSlots"
                            full-width :allowed-dates="getalloweddates"></v-date-picker>
                    </v-card>
                    <v-card class="mx-auto mt-2" :loading="loadingslots" v-if="timeperiod != ''">
                        <v-card-title class="text-h5">Available Slots</v-card-title>
                        <v-card-text v-if="slots.length > 0">
                            <v-list dense two-line>
                                <v-list-item-group>
                                    <v-list-item v-for="(slot, index) in slots" :key="index">
                                        <template v-slot:default="{ active }">
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    <p class="text-h6 ma-0">{{ slot.title }}  <span class="mr-3  ml-2" style="font-size: 14px; font-weight: 400px;">({{ slot.start_time + ' - ' + slot.end_time }})</span><v-chip
                                                        :color="(slot.bookings.length == 0) ? 'green' : (slot.bookings.length >= slot.bookings_allowed) ? 'red' : 'orange'"
                                                        small dark>Slot(s) {{ slot.bookings.length }}
                                                        /
                                                        {{ slot.bookings_allowed }}</v-chip></p>
                                                </v-list-item-title>
                                                <!-- <v-list-item-subtitle>
                                                    {{ slot.start_time + ' - ' + slot.end_time }}<br />
                                                    <v-chip
                                                        :color="(slot.bookings.length == 0) ? 'green' : (slot.bookings.length >= slot.bookings_allowed) ? 'red' : 'orange'"
                                                        small dark>Slot(s) {{ slot.bookings.length }}
                                                        /
                                                        {{ slot.bookings_allowed }}</v-chip>
                                                    <v-btn x-small color="primary"
                                                        v-if="slot.bookings.length > 0 && slot.bookings.length <= slot.bookings_allowed"
                                                        @click="showbookings(slot)">Show
                                                        Booking</v-btn>
                                                </v-list-item-subtitle> -->
                                            </v-list-item-content>
                                            <v-list-item-action>
                                                <v-checkbox v-model="selection"
                                                    :disabled="slot.bookings.length >= slot.bookings_allowed" multiple
                                                    :value="slot" @change="addbookings(slot, $event)"></v-checkbox>
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
                    <v-card>
                        <v-card-title class="text-h5" style="background: var(--v-primary-base); color:#fff;">Booking
                            Summary</v-card-title>
                        <v-card-text>
                            <p class="text-h5 mt-2">{{ selecteditem.name }} {{ (this.timeperiod != '')?'('+this.timeperiod+')':'' }}</p>
                            <v-divider class="mb-2"> </v-divider>
                            <v-simple-table v-if="selection.length > 0">
                                <tr>
                                    <td>
                                        <!-- <v-icon color="primary">mdi-clock</v-icon> -->
                                        Selected Slots
                                    </td>
                                    <td>
                                        <v-chip color="" small v-for="slot in selection" :key="slot.id"
                                            class="mr-1 mb-1" style="font-size: 11px">{{ slot.start_time }} -
                                            {{ slot.end_time }}</v-chip>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <v-divider class="mt-2 pb-2"></v-divider>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h3 style="float: left">Slot's Payment</h3>
                                        <span style="float: right">{{ totalprice }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <v-divider class="mt-2 pb-2"></v-divider>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h3 style="float: left">Advance Payment</h3>
                                        <span style="float: right">{{ advanceprice }}</span>
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
                                        <span style="float: right">{{ totalprice - advanceprice }}</span>
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
                                            <v-radio :label="gateway" :value="gateway" v-for="gateway in gateways"
                                                :key="gateway"></v-radio>
                                        </v-radio-group>

                                    </td>
                                </tr>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions style="background:var(--v-primary-base);">
                            <p class="pa-0 ma-0" style="width: 100%; text-align: center; color: #fff;">{{
                    selection.length }}
                                Slot(s) Selected
                                <v-btn color="default" v-if="!isUserlogin" small @click.stop="openlogindialogfun">
                                    Login/Signup
                                </v-btn>
                                <v-btn color="default" v-if="isUserlogin && selection.length > 0" small
                                    @click.stop="bookslot">
                                    Book Now
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
                            <v-list-item v-for="booking in bookings" :key="booking.id">
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <v-avatar size="50" color="#efefef" style="float: left;">
                                            <v-img :lazy-src="temimagecurrent"
                                                :src="'/storage/uploads/team/' + booking.team.image"
                                                v-if="booking.team.image != ''" class="align-center" />
                                            <span class="headline text-h1" v-else>{{ booking.team.name.charAt(0)
                                                }}</span>
                                        </v-avatar>
                                        <h3 style="float: left; clear: right;" class="mt-0 ml-1">{{
                    booking.team.name }}<br /><v-chip color="orange" dark small
                                                style="font-family:'Pacifico';">{{ booking.team.designation }}</v-chip>
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

    </div>
</template>


<script>
import axios from 'axios'
import moment, { duration } from 'moment'
import confirm from '@/components/common/Confirm.vue'
import { EventBus } from "../../event-bus.js";
import Mapview from '@/components/common/Mapview.vue'
import {
    mapState,
    mapActions
} from "vuex";
export default {
    components: {
        confirm,
        Mapview
    },
    data() {
        return {
            isUserlogin: (localStorage.getItem('userdetails')) ? true : false,
            openlogindialog: false,
            haveaccount: true,
            bookingform: false,
            slots: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
            time: [],
            bookings: [],
            selection: '',
            selecteditem: [],
            timeperiodold: moment().format('YYYY-MM-DD'),
            timeperiod: moment().format('YYYY-MM-DD'),
            showbooking: false,
            newbookings: [],
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
            gateway: '',
        }
    },
    watch: {
        // selection: function (val) {

        //     if (val.length > 0) {

        //         this.addbookings(val);
        //     }

        // }
    },
    mounted() {
        this.getcategory();
        this.getuserteam();
        this.timeperiod = '';//  moment().format('YYYY-MM-DD');
        // this.selection
        // this.getSlots();
        this.getgateways();
    },
    created() {
        EventBus.$on("isUserLogin", (status) => {
            this.isUserlogin = status;
        });
    },
    methods: {
        opendirection() {
            window.open('https://www.google.com/maps/dir/?api=1&destination=' + this.selecteditem.location, '_blank');
        },
        ...mapActions("app", ["getStoreData", "setUserdetails", "getUserLogin"]),
        deletebooking(item) {
            this.newbookings.splice(item, 1);
            // get index of booking 
        },
        openlogindialogfun() {
            EventBus.$emit("openlogindialog", true);
        },

        async getcategory() {
            await axios.get('/api/category/' + this.$route.params.id)
                .then(response => {
                    if (response.data.success) {

                        let category = response.data.category;
                        this.selecteditem = category;
                        let location = category.location;

                        this.$refs.mapview.getlocation(location);
                        // this.availabledays = category.slots[0].days;
                        this.getSlots();
                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async getSlots() {
            this.loadingslots = true;
            if (this.selection.length > 0) {
                const ok = await this.$refs.confirm.open({
                    title: 'Are you sure?',
                    message: 'Are you sure you want to change the date? It will clear all your selected slots.',
                    okButtonText: 'Yes',
                    cancelButtonText: 'No',
                    options: {
                        color: "error",
                        width: 290,
                        zIndex: 200,
                    },
                    icon: "mdi-delete",
                });
                if (ok) {
                    let selectedslotsid = this.selection.map(slot => slot.id);
                    if (selectedslotsid.length > 0) {
                        selectedslotsid.forEach(slotid => {
                            this.slots.forEach(slot => {
                                if (slot.id == slotid) {
                                    slot.bookings.pop();
                                }
                            });
                        });
                    }
                    this.timeperiodold = this.timeperiod;
                    this.selection = [];
                    this.newbookings = [];
                    this.advanceprice = 0;
                    this.totalprice = 0;
                }
                else {
                    this.timeperiod = this.timeperiodold;
                    return;
                }
            }
            else {
                this.timeperiodold = this.timeperiod;
                // console.log(this.timeperiod)
                // timeperiod is less than today
                if (moment(this.timeperiod).isBefore(moment().format('YYYY-MM-DD'))) {
                    this.$toasted.show('Please select a valid date', {
                        type: 'error',
                        duration: 3000
                    });
                    return;
                }
                this.newbookings = [];
                this.advanceprice = 0;
                this.totalprice = 0;
                this.selection = [];
                await axios.post('/api/slots', {
                    id: this.selecteditem.id,
                    date: this.timeperiod,
                    day: new Date(this.timeperiod).getDay()
                })
                    .then(response => {
                        if (response.data.success) {
                            this.slots = response.data.slots;
                            // add dummy bookings
                            // this.slots.forEach(slot => {


                            // });
                        }
                        else {
                            console.log(response.data.message);
                        }
                    }).catch(error => {
                        console.log(error);
                    });
            }
            this.loadingslots = false;
        },
        getalloweddates(val) {
            // check if val is less than today
            if (moment(val).isBefore(moment().format('YYYY-MM-DD'))) {
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
            }
            else {
                this.haveaccount = true;
            }
            this.bookingform = true;
            this.showbooking = false;
        },
        addbookings(slot, event) {

            // console.log(slot.bookings.length, slot.bookings_allowed);

            let newbookingdata = {
                slot_id: slot.id,
                category_id: this.selecteditem.id,
                date: this.timeperiod,
                // slot: slot,
                // category: this.selecteditem,
                advance: slot.advanceprice,
                total: slot.price,
                time: slot.start_time + ' - ' + slot.end_time,
                team_id: this.team.id,
                team: this.team,
                user_id: localStorage.getItem('userdetails') ? JSON.parse(localStorage.getItem('userdetails')).id : 0,

            }
 
            // else {

            // }
            // }
            this.advanceprice = 0;
            this.totalprice = 0;
            this.newbookings = [];
            this.selection.forEach(booking => {
                this.advanceprice += parseInt(booking.advanceprice);
                this.totalprice += parseInt(booking.price);
                this.newbookings.push(newbookingdata);
            });


            console.log(this.selection, 'newbookings');
        },
        getuserteam() {
            if (localStorage.getItem('userdetails') == null) {
                return;
            }
            axios.get('/api/user/getuserteam')
                .then(response => {
                    this.team = response.data.team;
                    this.temimagecurrent = this.team.image;
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
            axios.post('/api/addbooking', {
                slot: this.selection,
                category: this.selecteditem,
                date: this.timeperiod,
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
</style>