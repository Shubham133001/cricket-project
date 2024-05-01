<template>
    <div style="width: 100%;">
        <v-sheet rounded>
            <v-container>
                <h2>Payment Gateways</h2>
                <v-row>

                    <v-col cols="12" md="6" v-for="(gateway, index) in gateways" :key="index">
                        <v-list class="elevation-4">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title style="font-size: 25px; ">{{ index }} <v-switch
                                            v-model="gateway.status" value="Active" color="primary" style="float:right"
                                            @change="addgateway(gateway, index)"></v-switch></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-btn color="primary" @click="openconfig(index)">Configure</v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                    </v-col>

                </v-row>
            </v-container>
        </v-sheet>
        <v-dialog v-model="showconfig" max-width="500">
            <v-card>
                <v-card-title>
                    <span class="headline">Configure Gateway</span>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <div v-for="(config, index) in gatewayconfig" :key="index"
                            style="width:100%; padding: 0px 30px;" class="mt-2">
                            <v-text-field v-model="config.value" outlined :label="config.FriendlyName"
                                v-if="config.Type == 'text'"></v-text-field>
                            <v-select v-model="config.value" outlined :label="config.FriendlyName"
                                :items="config.Options" item-text="text" item-value="value"
                                v-if="config.Type == 'dropdown'"></v-select>
                        </div>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="showconfig = false">Close</v-btn>
                    <v-btn color="primary" dark @click="saveconfig">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
export default {
    data() {
        return {
            // check if url contains admin

            showloading: true,
            isAdmin: false,
            gateways: [],
            gatewaystatus: [],
            showconfig: false,
            gatewayconfig: [],
            selectedgateway: ''
        }
    },
    created() {
        this.getgateways();
    },
    methods: {
        async getgateways() {
            this.isAdmin = this.$route.path.includes('admin');
            await axios.get('/api/admin/getpaymentgateways').then(response => {
                if (response.data.success) {
                    this.gateways = response.data.gateways;
                }
            });
        },
        addgateway(gateway, index) {
            var url = '/api/admin/addpaymentgateways';
            if (gateway.status != 'Active') {
                url = '/api/admin/removepaymentgateways';
            }
            axios.post(url, {
                gateway: index
            }).then(response => {
                if (response.data.success) {
                    this.$toasted.show(response.data.message, {
                        type: 'success',
                        dureation: 3000
                    })
                } else {
                    this.$toasted.show(response.data.message, {
                        type: 'error',
                        dureation: 3000
                    })
                }
            });
        },
        async openconfig(index) {
            this.selectedgateway = index;
            axios.get('/api/admin/getconfig/' + index).then(response => {
                if (response.data.success) {
                    this.gatewayconfig = response.data.config;
                }
            });
            this.showconfig = true;
        },
        saveconfig() {
            axios.post('/api/admin/saveconfig', {
                config: this.gatewayconfig,
                gateway: this.selectedgateway
            }).then(response => {
                if (response.data.success) {
                    this.$toasted.show('Configurations Updated Successfully', {
                        type: 'success',
                        dureation: 3000
                    });
                    this.showconfig = false;
                } else {
                    this.$toasted.show('Something Went Wring!', {
                        type: 'error',
                        dureation: 3000
                    })
                }
            });
        }
    }
}
</script>