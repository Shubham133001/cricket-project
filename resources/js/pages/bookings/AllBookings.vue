<template>
    <div style="width: 100%">
        <v-container>
            <h1 class="text-lg-h2 text-sm-h3">Categories</h1>
            <v-col cols="12" md="12" :class="(showheading) ? 'showheading' : 'hideheading'">
                <v-img height="80px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                    :src="'/storage/images/' + selecteditem.image" lazy-src="https://picsum.photos/id/886/350/200" cover
                    style="border-radius: 10px;">

                    <v-card-title class="text-h4" style="color: #fff; position: relative; z-index: 1">
                        <v-btn icon fab color="white" @click="selecteditem = []; showheading = false"><v-icon
                                large>mdi-chevron-left</v-icon></v-btn>{{
                selecteditem.name }}
                    </v-card-title>
                </v-img>
            </v-col>

            <v-row :class="(showheading) ? 'hidecategories' : 'showcategories'">
                <v-col cols=" 12" sm="6" md="4" v-for="item in categories" :key="item.id"
                    :class="(showheading) ? 'hidecategories' : 'showcategories'">
                    <v-card class="mx-auto">
                        <v-hover v-slot:default="{ hover }">
                            <v-img height="300px" class="align-end showslidebg"
                                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                :src="'/storage/images/' + item.image" lazy-src="https://picsum.photos/id/886/350/200"
                                cover style="border-radius: 10px;">

                                <v-card-title class="text-h4" style="color: #fff; position: relative; z-index: 1">
                                    {{ item.name }}
                                </v-card-title>

                                <v-card-subtitle style="color: #fff; position: relative; z-index: 1">
                                    {{ truncatedDescription(item.description) }}<br />
                                    <v-chip small color="primary" class="mt-1">{{ item.slotslength }} Slots
                                    </v-chip>
                                </v-card-subtitle>



                                <v-card-actions class="mb-2 pl-2 mt-0">
                                    <v-btn color="white" @click="bookslot(item)" class="px-3"
                                        :disabled="(item == selecteditem)">Reserve
                                        Now</v-btn>

                                    <!-- <v-chip-group v-model="item.selection" v-if="item.slots.length > 0">
                                    <v-chip v-for="(slot, index) in item.slots" :key="slot.id"
                                        :color="(item.selection == index) ? 'green' : 'primary'" dark>{{ slot.time
                                        }}</v-chip>
                                </v-chip-group> -->

                                </v-card-actions>
                            </v-img>
                        </v-hover>

                    </v-card>
                </v-col>
            </v-row>
            <v-col cols="12" md="12" :class="(showheading) ? 'pt-0 showcategories' : 'hidecategories'">
                <v-row>
                    <v-col cols="12" md="4" v-for="subcategory in this.selecteditem.children" :key="subcategory.id"
                        :class="(showheading) ? 'showcategories1' : 'hidecategories1'">
                        <v-card>
                            <v-hover v-slot:default="{ hover }">
                                <v-img height="300px" class="align-end showslidebg"
                                    gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                    :src="'/storage/images/' + subcategory.image"
                                    lazy-src="https://picsum.photos/id/886/350/200" cover style="border-radius: 10px;">

                                    <v-card-title class="text-h4" style="color: #fff; position: relative; z-index: 1">
                                        {{ subcategory.name }}
                                    </v-card-title>

                                    <v-card-subtitle style="color: #fff; position: relative; z-index: 1">
                                        {{ truncatedDescription(subcategory.description) }}<br />
                                        <v-chip small color="primary" class="mt-1">{{ subcategory.slotslength }}
                                            Slots
                                        </v-chip>
                                    </v-card-subtitle>
                                    <v-card-actions class="mb-2 pl-2 mt-0">
                                        <v-btn color="white" @click="bookslot(subcategory)" class="px-3"
                                            :disabled="(subcategory == selecteditem)">Book
                                            Now</v-btn>

                                    </v-card-actions>
                                </v-img>
                            </v-hover>
                        </v-card>
                    </v-col>

                </v-row>
            </v-col>

        </v-container>
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
            showheading: false,
            headers: [{
                text: 'ID',
                align: 'start',
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
                text: 'Advance Price',
                value: 'advanceprice'
            },
            {
                text: 'Price',
                value: 'price'
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
        truncatedDescription(description) {
            const maxLength = 100; // Adjust the character limit as needed
            return description.length > maxLength
                ? description.substring(0, maxLength) + '...'
                : description;
        },
        async getcategories() {

            await axios.get('/api/categories')
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
                                category.slotslength = (category.slotslength + child.slots.length);
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
            this.showheading = true;

            let childrens = [];
            if (this.selecteditem.children != undefined) {
                childrens = this.selecteditem.children;
            }

            if (childrens.length == 0) {
                this.$router.push({
                    name: 'bookings-slots',
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

.showslidebg:hover::before {
    content: '';
    position: absolute;
    top: 190px;
    left: -89px;
    width: 130%;
    height: 130%;
    background-color: var(--v-primary-base);
    border-radius: 500px;
    transition: all 0.5s;
    transform: scale(3);
    /* transform: rotate(-40deg); */
    /* transform-origin: 0% 0%; */
}

.showslidebg::before {
    content: '';
    position: absolute;
    top: 150px;
    left: 50%;
    width: 130%;
    height: 130%;
    background-color: var(--v-primary-base);
    opacity: 0.5;
    border-radius: 10px;
    transition: all 0.5s;
    /* transform: rotate(-00deg); */
    transform: scale(0);
    /* transform-origin: 0% 0%; */
    z-index: 0;
    border-radius: 500px;
}

.hideheading {
    transition: all 0.5s;
    transform: scale(0);
    opacity: 0;
    height: 0;
    overflow: hidden;

}

.showheading {
    transition: all 0.5s;
    transform: scale(1);
    opacity: 1;
    height: auto;
    overflow: hidden;
}

.hidecategories {
    transition: all 0.5s;
    transform: scale(0);
    opacity: 0;
    height: 0;
    overflow: hidden;
}

.showcategories {
    transition: all 0.5s;
    transform: scale(1);
    opacity: 1;
    height: auto;
    overflow: hidden;
}

.hidecategories1 {
    transition: all 0.5s;
    transform: scale(0);
    opacity: 0;
    height: 0;
    overflow: hidden;
    transition-delay: 2s;
}

.showcategories1 {
    transition: all 0.5s;
    transform: scale(1);
    opacity: 1;
    height: auto;
    overflow: hidden;
    transition-delay: 2s;
}
</style>