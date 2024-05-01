<template>
    <div style="width: 100%">
        <v-card>
            <v-card-title>
                <span class="headline">New Booking</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" sm="6" md="4" v-for="item in categories" :key="item.id">
                        <v-card class="mx-auto">

                            <v-img height="200px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                :src="(item.image != 'null') ? '/storage/images/' + item.image : 'https://dummyimage.com/400X400'"
                                cover>

                                <v-card-title class="text-h4" style="color: #fff;">
                                    {{ item.name }}
                                </v-card-title>

                                <v-card-subtitle style="color: #fff;">
                                    {{ item.description }}<br />
                                    <v-chip small color="primary">{{ item.slotslength }} Slots
                                        Available</v-chip>
                                </v-card-subtitle>

                            </v-img>

                            <v-card-actions>
                                <v-btn color="primary" @click="bookslot(item)" outlined
                                    :disabled="(item == selecteditem)">Reserve Now</v-btn>
                                <!-- <v-chip-group v-model="item.selection" v-if="item.slots.length > 0">
                                    <v-chip v-for="(slot, index) in item.slots" :key="slot.id"
                                        :color="(item.selection == index) ? 'green' : 'primary'" dark>{{ slot.time
                                        }}</v-chip>
                                </v-chip-group> -->

                            </v-card-actions>



                            <v-divider v-if="item.children.length > 0"></v-divider>
                            <!-- <v-expand-transition>
                                <div v-show="item.show">
                                     -->

                            <!-- <v-card-text v-if="item.children.length > 0">
                                <v-row v-for="subcategory in item.children" :key="subcategory.id">

                                    <v-col cols="12" md="12">
                                        <p><strong>{{ subcategory.name }}</strong><br />{{ subcategory.name }}
                                        </p>

                                        <v-chip-group v-model="subcategory.selection"
                                            v-if="subcategory.slots.length > 0">
                                            <v-chip v-for="(slot, index) in subcategory.slots" :key="slot.id"
                                                :color="(subcategory.selection == index) ? 'green' : 'primary'" dark>{{
                        slot.time
                    }}</v-chip>
                                        </v-chip-group>
                                        <v-col cols="12" md="12" class="text-center"
                                            v-if="subcategory.slots.length > 0">
                                            <v-btn color="primary" @click="bookslot(item)">Reserve Now</v-btn>
                                        </v-col>
                                    </v-col>
                                </v-row>
                            </v-card-text> -->
                            <!-- </div>
                            </v-expand-transition> -->
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>

                    <!-- <v-col cols="12" md="12" v-if="this.selecteditem != [] && this.selecteditem.type == 'slot'">
                        Book Slote Here
                    </v-col> -->
                    <v-col cols="12" md="12" v-if="this.selecteditem != [] && this.selecteditem.type == 'category'">
                        <v-row>
                            <v-col cols="12" md="4" v-for="subcategory in this.selecteditem.children"
                                :key="subcategory.id">
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ subcategory.name }}</span>
                                    </v-card-title>
                                    <v-card-actions>
                                        <v-btn color="primary" @click="bookslot(subcategory)" outlined
                                            :disabled="(subcategory == selecteditem)">Book Slot Now</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
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
            categories: [],
            search: '',
            headers: [{
                text: 'ID',
                align: 'start',
                sortable: false,
                value: 'id'
            },
            {
                text: 'Slot',
                value: 'slot'
            },
            {
                text: 'Date',
                value: 'date'
            },
            {
                text: 'Time',
                value: 'time'
            },
            {
                text: 'Price',
                value: 'price'
            },
            {
                text: 'Advance Price',
                value: 'advanceprice'
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            }
            ],
            selection: [],
            selecteditem: []

        }
    },
    mounted() {
        this.getcategories();
    },
    methods: {
        async getcategories() {
            await axios.get('/api/category/listwithslots')
                .then(response => {
                    let categories = response.data.categories;
                    let newcatgories = [];
                    categories.forEach(category => {
                        category.is_child = false;
                        category.show = false;
                        category.slotslength = category.slots.length;
                        newcatgories.push(category);
                        if (category.children.length > 0) {
                            category.children.forEach(child => {
                                // child.is_child = true;
                                // newcatgories.push(child);
                                category.slotslength = category.slotslength + child.slots.length;
                            });
                        }
                    });
                    this.categories = newcatgories;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        bookslot(item) {
            this.selecteditem = item;

            if (this.selecteditem.type == 'slot') {
                this.$router.push({
                    name: 'admin-bookings-slots',
                    params: {
                        id: this.selecteditem.id,
                    }
                });
            }
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