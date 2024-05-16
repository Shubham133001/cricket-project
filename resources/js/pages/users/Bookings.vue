<template>
  <div class="flex-grow-1">
    <v-container>
      <div class="my-2">
        <div>
          <v-card :loading="loadingdata">
            <v-card-title>My Bookings</v-card-title>
            <v-card-text>
              <v-data-table :headers="headers" :items="bookings" item-key="id" class="elevation-1">
                <template v-slot:item.invoice_id="{ item }">
                  <strong style="border-bottom: dashed 1px #000; cursor:pointer;"
                    @click="openinvoice(item.invoice_id)">{{
            item.invoice_id }}</strong>
                </template>
                <template v-slot:item.action="{ item }">
                  <v-btn color="red" dark rounded
                    v-if="item.status != 'Cancelled' && item.status != 'Cancellation Requested'"
                    @click="cancelbooking(item.id)">Cancel</v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </div>
      </div>
      <v-dialog v-model="askreason" max-width="500">
        <v-card>
          <v-card-title>
            <span class="headline">Cancel Booking</span>
          </v-card-title>
          <v-card-text>
            <v-textarea v-model="reason" label="Reason" required></v-textarea>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="askreason = false">Close</v-btn>
            <v-btn color="blue darken-1" text @click="cancelbookingfinal">Cancel Booking</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
    <confirm ref="confirm"></confirm>
  </div>
</template>

<script>
import { duration } from 'moment'
import CopyLabel from '@/components/common/CopyLabel'
import confirm from '@/components/common/Confirm.vue'
export default {
  components: {
    CopyLabel,
    confirm
  },
  data() {
    return {
      bookings: [],
      cancel: false,
      askreason: false,
      loadingdata: true,
      headers: [{
        text: 'ID',
        align: 'start',
        sortable: false,
        value: 'id'
      },
      {
        text: 'Date',
        value: 'date'
      },
      {
        text: 'Slot',
        value: 'slot.title'
      },
      {
        text: 'Time',
        value: 'slot.start_time'
      },

      {
        text: 'Invoice ID',
        value: 'invoice_id'
      },
      {
        text: 'Status',
        value: 'status'
      },
      {
        text: 'Payment Status',
        value: 'payment_status'
      },
      {
        text: 'Action',
        value: 'action'
      }
      ],
      reason: '',
      selectedid: ''
    }
  },
  mounted() {
    this.getbookings()
  },
  methods: {
    openinvoice(id) {
      this.$router.push({
        name: 'adminviewinvoice',
        params: {
          id: id
        }
      })
    },
    async cancelbooking(id) {
      this.selectedid = id;
      const ok = await this.$refs.confirm.open({
        title: 'Cancel Booking',
        message: 'Are you sure you want to cancel this booking?',
        options: {
          color: 'red',
          icon: 'mdi-delete'
        }
      })
      if (ok) {
        this.askreason = true;
      }
    },
    async cancelbookingfinal() {
      await axios.post('/api/user/cancelrequest', {
        id: this.selectedid,
        reason: this.reason
      }).then(response => {
        if (response.data.success) {
          this.askreason = false;
          this.getbookings();
          this.$toasted.show(response.data.message, {
            type: 'success',
            duration: 3000
          })
        } else {
          this.$refs.confirm.open({
            title: 'Error',
            message: response.data.message,
            options: {
              color: 'red',
              icon: 'mdi-alert-circle'
            }
          })
        }
      }).catch(error => {
        console.log(error)
      })
    },
    async getbookings() {
      await axios.post('/api/user/getbookings', {}).then(response => {
        // console.log(response.data.userdata);
        if (response.data.success) {
          this.bookings = response.data.bookings;
          this.loadingdata = false;
        }
      }).catch(error => {
        console.log(error)
      })
    },
  },
}
</script>