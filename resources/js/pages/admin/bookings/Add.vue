<template>
    <div style="width: 100%">
        <v-card>
            <v-card-title>
                <span class="headline">Book Slot</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="4">

                        <v-card class="mx-auto">

                            <v-img height="200px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                :src="(selecteditem.image != 'null' || selecteditem.image != '') ? '/storage/images/' + selecteditem.image : 'https://dummyimage.com/400X400'"
                                cover>

                                <v-card-title class="text-h4" style="color: #fff;">
                                    {{ selecteditem.name }}
                                </v-card-title>

                                <v-card-subtitle style="color: #fff;">
                                    {{ selecteditem.description }}
                                </v-card-subtitle>

                            </v-img>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <!-- :allowed-dates="getalloweddates" -->
                        <v-date-picker v-model="timeperiod" label="Start Date" @click="selectdate"></v-date-picker>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-btn-toggle v-model="selection" multiple>
                            <v-btn v-for="(slot, index) in slots" :key="index" :value="slot">{{ slot.time
                                }}</v-btn>
                        </v-btn-toggle>
                    </v-col>

                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>


<script>
import axios from 'axios'
import moment from 'moment'
import {
    mapState
} from 'vuex'
export default {
    data() {
        return {
            slots: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
            time: [],
            bookings: [],
            selection: [],
            selecteditem: [],
            timeperiod: '',
        }
    },
    mounted() {
        this.getcategory();
    },
    methods: {
        getcategory() {
            axios.get('/api/admin/category/edit/' + this.$route.params.id)
                .then(response => {
                    if (response.data.success) {

                        let category = response.data.category;
                        this.selecteditem = category;
                        // this.availabledays = category.slots[0].days;

                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getalloweddates(val) {
            // get day from val
            let day = new Date(val).getDay();
            // check if day is in available days
            if (this.availabledays.includes(day)) {
                return true;
            }
        },
        bookslot(item) {
            this.selecteditem = item;
        },
        selectdate() {
            this.slots = [];
            this.slots = this.selecteditem.slots;
        }
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
</style>