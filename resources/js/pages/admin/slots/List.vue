<template>
    <div style="width: 100%">
        <div>
        <div class="display-1">List Slots</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card>
            <!-- <v-card-title>
                <span class="headline">List Slots</span>
            </v-card-title> -->
            <v-card-text>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">ID</th>
                                <th class="text-left">Title</th>
                                <th class="text-left">Slot Time</th>
                                <th class="text-left"> Slot Date</th>
                                <th class="text-left"> Available Days</th>
                                <th class="text-left">Category</th>
                                 <th class="text-left">Advance Price</th>
                                <th class="text-left">Price</th>
                                <th class="text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(slot, index) in slots" :key="index">
                                <td>{{ index + 1 }}</td>
                                
                                <td>
                                    <strong>{{ slot.title }}</strong>
                                </td>
                                <td>
                                    {{ slot.start_time }} - {{ slot.end_time }}
                                </td>
                                <td>
                                    {{ slot.start_date }} - {{ slot.end_date }}
                                </td>
                                <td>

                                    <v-chip color="green" outlined dark v-for="(day, index) in slot.days"
                                        style="font-size: 11px; padding: 0px 5px;  width: auto; height: 20px;"
                                        class="mr-1 mb-1" :key="index">{{
                                days[parseInt(day)]
                            }}</v-chip>
                                </td>
                                <td>
                                    <strong>{{ slot.category.name }}</strong>
                                </td>
                                <td>
                                    <v-img src="/images/inr_icon.png" width="20"
                                        style="float: left; margin-top: 4px; margin-right: 5px;"></v-img> <strong
                                        style="color:#297729">{{ slot.advanceprice }}</strong>
                                </td>
                                <td>
                                    <v-img src="/images/inr_icon.png" width="20"
                                        style="float: left; margin-top: 4px; margin-right: 5px;"></v-img> <strong
                                        style="color:#297729">{{
                                slot.price }}</strong>
                                </td>
                                
                                <td>
                                    <!-- <v-btn color="primary" @click="editSlot(slot)">Edit</v-btn> -->
                                    <v-btn color="red" icon fab small @click="deleteSlot(slot)"><v-icon small
                                            color="red">mdi-delete</v-icon></v-btn>
                                </td>

                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-card-text>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>

</template>

<script>
import axios from 'axios';
import Confirm from '@/components/common/Confirm.vue';
export default {
    components: {
        Confirm
    },
    data() {
        return {
            breadcrumbs: [{
                text: 'Slots',
                disabled: false,
                to: '/admin/slots'
            }, {
                text: 'List'
            }],
            slots: [],

            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat']
        }
    },
    mounted() {
        this.getSlots();
    },
    methods: {
        async getSlots() {
            try {
                const response = await axios.get('/api/admin/slots/all').then(response => {
                    console.log(response.data.slots);
                    this.slots = response.data.slots;
                });

            } catch (error) {
                console.error(error);
            }
        },
        async deleteSlot(slot) {
            const ok = await this.$refs.confirm.open({
                title: 'Are you sure?',
                message: 'Are you sure you want to delete this slot?',
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
                try {
                    const response = await axios.post(`/api/admin/slots/delete`, { id: slot.id }).then(response => {
                        this.$toasted.success('Slot deleted successfully', {
                            duration: 2000
                        });
                    });
                    this.getSlots();
                } catch (error) {
                    console.error(error);
                }
            }
        },

    },


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