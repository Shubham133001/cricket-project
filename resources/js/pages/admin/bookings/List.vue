<template>
    <div style="width: 100%">
        <v-card :loading="loading">
            <v-card-title>
                <span class="headline">Bookings List</span>
            </v-card-title>
            <v-card-text>
                <v-data-table :headers="headers" :items="bookings" class="elevation-0" :options.sync="options"
                    :server-items-length="totalbookings" :search="search" :loading="loading">
                    <template v-slot:top>
                        <v-toolbar flat>
                            <!-- <v-toolbar-title>Bookings</v-toolbar-title> -->
                            <!-- <v-divider class="mx-4" inset vertical></v-divider> -->
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                                hide-details></v-text-field>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.id="{ item }">
                        <span style="font-weight: bold; cursor: pointer; border-bottom: dashed 1px #000;"
                            @click="editBooking(item)">#{{ item.id
                            }}</span>
                    </template>
                    <template v-slot:item.slot.start_time="{ item }">
                        {{ item.slot.start_time }} - {{ item.slot.end_time }}
                    </template>
                    <template v-slot:item.user.name="{ item }">
                        <span style="font-weight: bold; cursor: pointer; border-bottom: dashed 1px #000;"
                            @click="openuser(item.user.id)">#{{ item.user.name
                            }}</span>
                    </template>
                    <template v-slot:item.slot.price="{ item }">
                        <v-img src="/images/inr_icon.png" width="20"
                            style="float: left; margin-top: 4px; margin-right: 5px;"></v-img> <strong
                            style="color:#297729">{{
            item.slot.price }}</strong>
                    </template>
                    <template v-slot:item.slot.advanceprice="{ item }">
                        <v-img src="/images/inr_icon.png" width="20"
                            style="float: left; margin-top: 4px; margin-right: 5px;"></v-img> <strong
                            style="color:#297729">{{
            item.slot.advanceprice }}</strong>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-btn small fab icon color="primary" @click="editBooking(item)"><v-icon
                                small>mdi-pencil</v-icon></v-btn>
                        <v-btn small fab icon color="red" @click="deleteBooking(item)"><v-icon
                                small>mdi-delete</v-icon></v-btn>
                    </template>
                </v-data-table>
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
            loading: true,
            slots: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
            time: [],
            bookings: [],
            totalbookings: 0,
            options: {},
            search: '',
            headers: [{
                text: '#ID',
                align: 'start',

                value: 'id'
            },
            {
                text: 'User',
                value: 'user.name'
            },
            {
                text: 'Slot',
                value: 'slot.title'
            },
            {
                text: 'Date',
                value: 'date'
            },
            {
                text: 'Time',
                value: 'slot.start_time'
            },
            {
                text: 'Price',
                value: 'slot.price'
            },
            {
                text: 'Advance Price',
                value: 'slot.advanceprice'
            },
            {
                text: 'Status',
                value: 'status'
            }, {
                text: 'Payment Status',
                value: 'payment_status'
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            }
            ],
        }
    },
    mounted() {
        this.getbookings();
    },
    watch: {
        options: {
            handler() {
                this.getbookings()
            },
            deep: true,
        },
        search: {
            handler() {
                this.getbookings()
            },
            deep: true,
        },
    },
    methods: {
        openuser(id) {
            this.$router.push({
                name: 'admin-users-edit',
                params: {
                    id: id
                }
            });
        },
        async deleteBooking(item) {
            const ok = await this.$refs.confirm.open({
                title: 'Delete Booking',
                message: 'Are you sure you want to delete this booking?',
                options: {
                    color: "error",
                    width: 290,
                    zIndex: 200,
                },
                icon: "mdi-delete",
                buttons: [{
                    title: 'Yes',
                    class: 'btn-danger',
                }]
            });
            if (ok) {
                axios.post('/api/admin/bookings/delete', {
                    id: item.id
                }).then(response => {
                    if (response.data.success) {
                        this.$toasted.show(response.data.message, {
                            type: 'success',
                            dureation: 3000
                        })
                        this.getbookings();
                    } else {
                        this.$toasted.show(response.data.message, {
                            type: 'error',
                            dureation: 3000
                        })
                    }
                });
            }
        },
        getbookings() {
            this.loading = true;
            axios.post('/api/admin/bookings/list', { options: this.options, search: this.search, page: this.options.page })
                .then(response => {
                    let bookings = response.data.bookings.data;

                    let newbookings = [];
                    // bookings.forEach(booking => {
                    //     booking.date = moment(booking.date).format('YYYY-MM-DD');
                    //     booking.time = moment(booking.time, 'HH:mm:ss').format('hh:mm A');
                    //     newbookings.push(booking);
                    // });
                    this.bookings = bookings;
                    this.totalbookings = response.data.bookings.total;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    console.log(error);
                });
        },
        editBooking(item) {
            this.$router.push({
                name: 'admin-bookings-edit',
                params: {
                    id: item.id
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