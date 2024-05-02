<template>
    <div style="width: 100%">
        <div>
        <div class="display-1">Bookings Cancellation Requests</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card :loading="loading">
            <!-- <v-card-title>
                <span class="headline">Bookings Cancellation Requests</span>
            </v-card-title> -->
            <v-card-text>
                <v-data-table :headers="headers" :items="cancellations" class="elevation-0" :loading="loading">

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
                        <v-btn small color="primary" @click="approve(item)" dark><v-icon small>mdi-check
                            </v-icon>Approve</v-btn>
                        <v-btn small color="red" @click="reject(item)" dark><v-icon small>mdi-close
                            </v-icon>Reject</v-btn>
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
            breadcrumbs: [{
                text: 'Booking',
                disabled: false,
                to: '/admin/cancellation'
            }, {
                text: 'Cancellation Requests'
            }],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
            time: [],
            cancellations: [],
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
            // {
            //     text: 'Slot',
            //     value: 'slot.title'
            // },
            {
                text: 'Date',
                value: 'booking.date'
            },
            // {
            //     text: 'Time',
            //     value: 'slot.start_time'
            // },
             // {
            //     text: 'Advance Price',
            //     value: 'slot.advanceprice'
            // },
            // {
            //     text: 'Price',
            //     value: 'slot.price'
            // },
           
            {
                text: 'Status',
                value: 'status'
            }, {
                text: 'Payment Status',
                value: 'booking.payment_status'
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
        this.getbookingscancellations();
    },
    watch: {

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
        async approve(item) {
            const ok = await this.$refs.confirm.open({
                title: 'Approve Cancel Request',
                message: 'Are you sure you want to approve this cancel request?',
                buttons: [{
                    title: 'Yes',
                    class: 'btn-success',
                }]
            });
            if (ok) {
                axios.post('/api/admin/bookings/cancelbooking', {
                    id: item.id
                }).then(response => {
                    if (response.data.success) {
                        this.$toasted.show(response.data.message, {
                            type: 'success',
                            duration: 3000
                        });
                        this.getbookingscancellations();
                    } else {
                        this.$toasted.show(response.data.message, {
                            type: 'error',
                            duration: 3000
                        });
                    }
                });
            }
        },
        reject(item) {

            axios.post('/api/admin/bookings/rejectcancellation', {
                id: item.id
            }).then(response => {
                if (response.data.success) {
                    this.$toasted.show(response.data.message, {
                        type: 'success',
                        duration: 3000
                    });
                    this.getbookingscancellations();
                } else {
                    this.$toasted.show(response.data.message, {
                        type: 'error',
                        duration: 3000
                    });
                }
            });
        },
        getbookingscancellations() {
            this.loading = true;
            axios.get('/api/admin/bookings/cancellationrequests')
                .then(response => {
                    this.cancellations = response.data.cancellations;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    console.log(error);
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
</style>