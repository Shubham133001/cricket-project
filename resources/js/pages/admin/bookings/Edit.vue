<template>
    <div style="width: 100%">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">Edit Booking</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field v-model="booking.user.name" label="User" readonly></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                            transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="booking.date" label="Picker without buttons"
                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="booking.date" @input="menu2 = false"
                                @change="getslots"></v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col cols="12" md="6">

                        <v-select v-model="booking.category_id" :items="categories" @change="getslots" item-text="name"
                            item-value="id" label="Category"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-select v-model="booking.slot_id" :items="slots" item-text="title" item-value="id"
                            label="Slot"></v-select>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-select v-model="booking.status" :items="status" label="Status"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-select v-model="booking.payment_status" :items="payment_status"
                            label="Payment Status"></v-select>
                    </v-col>
                    <v-col cols="12" md="12">
                        <v-btn color="primary" @click="updateBooking">Update</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>
</template>


<script>
import axios from 'axios'
import moment from 'moment'
import {
    mapState
} from 'vuex'
import confirm from '@/components/common/Confirm.vue'
export default {
    components: {
        confirm
    },
    data() {
        return {
            menu2: false,
            loading: true,
            booking: {
                user: {
                    name: '',
                    date: '',
                },
            },
            categories: [],
            slots: [],
            status: [
                'Pending',
                'Approved',
                'Cancelled',
                'Completed'
            ],
            payment_status: [
                'Pending',
                'Paid',
                'Partial P  aid'
            ],
        }
    },
    mounted() {
        this.editBooking();
    },
    watch: {

    },
    methods: {
        async editBooking() {
            await axios.get('/api/admin/bookings/edit/' + this.$route.params.id).then((response) => {
                this.booking = response.data.booking;
                this.getcategories();
            });
        },
        async getcategories() {
            await axios.get('/api/admin/category/all').then((response) => {
                this.categories = response.data.categories;
                this.getslots();
            });
        },
        async getslots() {
            await axios.post('/api/slots', {
                id: this.booking.category_id,
                date: this.booking.date
            }).then((response) => {
                this.slots = response.data.slots;
                this.loading = false;
            });
        },
        async updateBooking() {
            await axios.post('/api/admin/bookings/update', this.booking).then((response) => {
                if (response.data.success) {
                    this.$toasted.show(response.data.message, {
                        type: 'success',
                        dureation: 3000
                    })
                    this.$router.push({
                        name: 'admin-bookings-list'
                    });
                } else {
                    this.$toasted.show(response.data.message, {
                        type: 'error',
                        dureation: 3000
                    })
                }
            });
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