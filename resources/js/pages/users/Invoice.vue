<template>
  <div class="flex-grow-1">
    <v-container>
      <div class="my-2">
        <div>
          <v-card :loading="loadingdata">
            <v-card-title>My Invoice</v-card-title>
            <v-card-text>
              <v-data-table :headers="headers" :items="invoice" item-key="id" class="elevation-1">
                <template v-slot:item.status="{ item }">
                  <v-btn v-if="item.status =='Paid'" color="blue" dark rounded>{{item.status}}</v-btn>
                  <v-btn v-if="item.status =='Unpaid'" color="red" dark rounded>{{item.status}}</v-btn>
                  <v-btn v-if="item.status =='Partial Paid'" color="warning" dark rounded>{{item.status}}</v-btn>
                </template>
                <template v-slot:item.action="{ item }">
                  <v-btn color="green" dark rounded
                    @click="viewInvoice(item.id)">View Invoice</v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </div>
      </div>
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
      invoice: [],
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
        text: 'Item',
        sortable: false,
        value: 'items[0].description'
        
      },
      {
        text: 'Amount',
        sortable: false,
        value: 'amount'
      },
      {
        text: 'Gateway',
        sortable: false,
        value: 'gateway'
      },
      {
        text: 'Status',
        sortable: false,
        value: 'status'
      },
      {
        text: 'Date',
      // sortable: false,
        value: 'created_at'
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
    this.getInvoice()
  },
  methods: {
    async viewInvoice(id) {
      this.$router.push({
            path: '/invoice/'+id,
        });
    },
    
    async getInvoice() {
      await axios.get('/api/user/invoice', {}).then(response => {
        // console.log(response.data);
        if (response.data.success) {
          this.invoice = response.data.invoice;
          this.loadingdata = false;
        }
      }).catch(error => {
        console.log(error)
      })
    },
  },
}
</script>