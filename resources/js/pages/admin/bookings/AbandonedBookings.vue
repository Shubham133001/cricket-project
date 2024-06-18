<template>
    <div style="width: 100%">
        <div>
            <div class="display-1">Abandoned Bookings List</div>
            <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
        </div>
        <v-card :loading="loading">
            <v-card-text>
                <v-data-table :headers="headers" :items="bookings" class="elevation-0" :options.sync="options"
                    :server-items-length="totalbookings" :search="search" :loading="loading">
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                                hide-details></v-text-field>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.id="{ item }">
                        <span style="font-weight: bold; cursor: pointer; border-bottom: dashed 1px #000;">#{{ item.id
                            }}</span>
                    </template>
                    <template v-slot:item.slot.start_time="{ item }">
                        {{ item.slot.start_time }} - {{ item.slot.end_time }}
                    </template>
                    <template v-slot:item.user.name="{ item }">
                        <span style="font-weight: bold; cursor: pointer; border-bottom: dashed 1px #000;"
                            @click="openuser(item.user.id)">#{{ item.user.name }}</span>
                    </template>
                    <template v-slot:item.slot.advanceprice="{ item }">
                        <strong style="color:#297729">{{ item.slot.advanceprice }}%</strong>
                    </template>
                    <template v-slot:item.slot.price="{ item }">
                        <v-img src="/images/inr_icon.png" width="20"
                            style="float: left; margin-top: 4px; margin-right: 5px;"></v-img> <strong
                            style="color:#297729">{{ item.slot.price }}</strong>
                    </template>

                    <template v-slot:item.actions="{ item }">
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
            breadcrumbs: [{
                text: 'Booking',
                disabled: false,
                to: '/admin/bookings'
            }, {
                text: 'Abandoned Bookings',
            }],
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
                value: 'user.name',
                sortable: false
            },
            {
                text: 'Slot',
                value: 'slot.title',
                sortable: false
            },
            {
                text: 'Date',
                value: 'booking_date'
            },
            {
                text: 'Time',
                value: 'slot.start_time',
                sortable: false
            },
            {
                text: 'Advance Price(%)',
                value: 'slot.advanceprice',
                sortable: false
            },
            {
                text: 'Price',
                value: 'slot.price',
                sortable: false
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
                axios.post('/api/admin/bookings/deleteabandonedbooking', {
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
            axios.post('/api/admin/bookings/abandonedbookings', { options: this.options, search: this.search, page: this.options.page })
                .then(response => {
                    let bookings = response.data.abandonedbookings.data;
                    let newbookings = [];
                    // bookings.forEach(booking => {
                    //     booking.date = moment(booking.date).format('YYYY-MM-DD');
                    //     booking.time = moment(booking.time, 'HH:mm:ss').format('hh:mm A');
                    //     newbookings.push(booking);
                    // });
                    this.bookings = bookings;
                    this.totalbookings = response.data.abandonedbookings.total;
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