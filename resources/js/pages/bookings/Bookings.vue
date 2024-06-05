<template>
    <div style="width: 100%">
        <v-container id="category">
            <v-col cols="12" v-if="showloading" class="text-center">
                <v-progress-circular indeterminate :size="50" color="primary"></v-progress-circular>
            </v-col>
            <v-col cols="12" md="12" style="background-color: var(--v-primary-base); border-radius: 8px;" :class="(showheading) ? 'showheading' : 'hideheading'" v-if="!showloading"
                class="pa-0">
                <!-- <v-img height="70px" class="align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.8)"
                    :src="'/storage/images/' + selecteditem.image" lazy-src="https://picsum.photos/id/886/350/200" cover
                    style="border-radius: 10px;">
                </v-img>-->
                <v-card-title class="text-lg-h5 text-sm-h4"  style="color: #fff; position: relative; z-index: 1">
                        <v-btn icon small fab color="white"   @click="selecteditem = []; showheading = false"><v-icon
                                large>mdi-chevron-left</v-icon></v-btn>{{
                selecteditem.name }}
                    </v-card-title>
            </v-col>

            <v-row :class="(showheading) ? 'hidecategories' : 'showcategories'" v-if="!showloading">
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

                                <v-card-subtitle style="color: #fff;">
                                    {{ truncatedDescription(item.description) }}<br />
                                    <v-chip small color="primary">{{ item.slotslength }} Slots
                                    </v-chip>
                                </v-card-subtitle>




                                <v-card-actions class="mb-2 pl-2 mt-0">
                                    <v-btn color="white" @click="bookslot(item)" class="px-3"
                                        :disabled="(item == selecteditem)">Book
                                        Now</v-btn>

                                </v-card-actions>
                            </v-img>
                        </v-hover>
                    </v-card>
                </v-col>
            </v-row>
            <v-col cols="12" md="12" :class="(showheading) ? 'pa-0 showcategories' : 'hidecategories'"
                v-if="!showloading">
                <v-row>
                    <v-col cols="12" md="4" v-for="subcategory in this.selecteditem.children" :key="subcategory.id"
                        style="transform: scale(0);
    opacity: 0;" :class="(showheading) ? 'showcategories1' : 'hidecategories1'">
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
            <v-col cols="12" class="text-center pt-0 mt-0" v-if="!showheading">
                <v-btn x-large class="my-1 mx-sm-1 w-full w-sm-auto" color="primary" to="/categories">View all
                    categories</v-btn>
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
            showloading: true,
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
            this.showloading = true;
            await axios.get('/api/categories?limit=3')
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
                    this.showloading = false;
                })
                .catch(error => {
                    this.showloading = false;
                    console.log(error);
                });
        },
        bookslot(item) {
            this.selecteditem = item;
            this.showheading = true;

            let childrens = [];
            if (this.selecteditem.children != undefined) {
                childrens = this.selecteditem.children;
                // get y position of the element
                let categorypos = document.getElementById('category');
                let yPosition = categorypos.offsetTop;
                console.log(categorypos, 'yPosition');
                window.scrollTo({
                    top: yPosition,
                    behavior: 'smooth'
                });
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
    transition: all 0.5s 2s;
    transform: scale(0);
    opacity: 0;
    height: 0;
    overflow: hidden;

}

.showcategories1 {
    transition: all 0.5s 2s;
    transform: scale(1) !important;
    opacity: 1 !important;
    height: auto;
    overflow: hidden;

}
</style>