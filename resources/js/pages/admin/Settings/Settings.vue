<template>
    <div style="width: 100%;">
        <v-sheet>
            <v-card>
                <v-card-title>
                    <span class="headline">General Settings</span>
                </v-card-title>
                <v-card-text>

                    <v-tabs v-model="tab">
                        <v-tab>Store Details</v-tab>

                    </v-tabs>
                    <v-tabs-items v-model="tab">
                        <v-tab-item>
                            <v-simple-table>
                                <tr>
                                    <th>Store Name</th>
                                    <td><v-text-field label="Store Name*" v-model="storeDetails.name"
                                            :rules="required"></v-text-field></td>
                                    <th>Store Address</th>
                                    <td><v-text-field label="Store Address"
                                            v-model="storeDetails.address"></v-text-field></td>
                                </tr>
                                <tr>
                                    <th>Store Contact</th>
                                    <td><v-text-field label="Store Contact*" v-model="storeDetails.contact"
                                            :rules="required"></v-text-field></td>
                                    <th>Store Email</th>
                                    <td><v-text-field label="Store Email*" v-model="storeDetails.email"
                                            :rules="required"></v-text-field></td>
                                </tr>
                                <tr>
                                    <th>Store Logo</th>
                                    <td><v-text-field label="Store Logo URL" v-model="storeDetails.logo"></v-text-field>
                                    </td>
                                    <!-- <th>Enable SMS</th>
                                    <td><v-checkbox v-model="storeDetails.enablesms"
                                            label="Tick to Enable sending SMS"></v-checkbox><span></span>
                                    </td> -->

                                </tr>
                            </v-simple-table>
                        </v-tab-item>
                        <v-tab-item>
                            <v-simple-table>
                                <tr>
                                    <th>Driver</th>
                                    <td>
                                        <v-select label="Driver" v-model="smtpSettings.driver"
                                            :items="drivers"></v-select>
                                    </td>
                                    <th>
                                        Host
                                    </th>
                                    <td>
                                        <v-text-field label="Host*" v-model="smtpSettings.host"
                                            :rules="required"></v-text-field>
                                    </td>

                                </tr>
                                <tr>
                                    <th>
                                        Port
                                    </th>
                                    <td>
                                        <v-text-field label="Port*" v-model="smtpSettings.port"
                                            :rules="required"></v-text-field>
                                    </td>
                                    <th>
                                        Username
                                    </th>
                                    <td>
                                        <v-text-field label="Username*" v-model="smtpSettings.username"
                                            :rules="required"></v-text-field>
                                    </td>

                                </tr>
                                <tr>
                                    <th>
                                        Password
                                    </th>
                                    <td>
                                        <v-text-field label="Password*" v-model="smtpSettings.password"
                                            :rules="required"></v-text-field>
                                    </td>
                                    <th>
                                        Encryption
                                    </th>
                                    <td>
                                        <v-select label="Encryption" v-model="smtpSettings.encryption"
                                            :items="encryptionOptions"></v-select>
                                    </td>
                                </tr>
                            </v-simple-table>
                        </v-tab-item>
                        <v-tab-item>
                            <v-simple-table>
                                <tr>
                                    <th>
                                        SMS API Key
                                    </th>
                                    <td>
                                        <v-text-field label="SMS API Key*" v-model="smsSettings.apikey"
                                            :rules="required"></v-text-field>
                                    </td>
                                    <th>
                                        Sender ID
                                    </th>
                                    <td>
                                        <v-text-field label="Sender ID*" v-model="smsSettings.senderid"
                                            :rules="required"></v-text-field>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Route
                                    </th>
                                    <td>
                                        <v-select label="Route" v-model="smsSettings.route"
                                            :items="routeOptions"></v-select>
                                    </td>
                                    <th>
                                        Country
                                    </th>
                                    <td>
                                        <v-select label="Country" v-model="smsSettings.country"
                                            :items="countryOptions"></v-select>
                                    </td>
                                </tr>

                            </v-simple-table>
                        </v-tab-item>
                    </v-tabs-items>


                </v-card-text>
                <v-card-actions class="justify-center">
                    <v-btn color="primary" @click="updateStoreDetails">Save</v-btn>
                    <v-btn color="error" @click="askpassword = false">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-sheet>
    </div>
</template>
<script>
import {
    mapState,
    mapActions
} from "vuex";
export default {
    data() {
        return {
            drivers: [{
                text: "SMTP",
                value: "smtp",
            },
            {
                text: "Sendmail",
                value: "sendmail",
            }],
            tab: null,
            askpassword: false,
            storeDetails: {
                name: "",
                address: "",
                contact: "",
                email: "",
                logo: "",
                enablesms: true,
            },

            smtpSettings: {
                host: "",
                driver: "sendmail",
                port: "587",
                username: "",
                password: "",
                encryption: "tls",
                enablesmtp: false,
            },
            encryptionOptions: [
                "tls",
                "ssl"
            ],
            smsSettings: {
                url: "",
                apikey: "",
                senderid: "",
                route: "1",
                country: "91",
            },
            routeOptions: [
                {
                    text: "Promotional",
                    value: "1",
                },
                {
                    text: "Transactional",
                    value: "4",
                }
            ],
            countryOptions: [
                {
                    text: "India",
                    value: "91",
                }
            ],
            required: [
                (v) => !!v || "This field is required",
            ],
        }
    },
    methods: {
        ...mapActions("app", ["getStoreData"]),
        getStoreData() {
            axios.get("/api/store").then((response) => {
                this.storeDetails = response.data.storeDetails;
                // set store details to vuex
                this.$store.commit("app/setStoreDetails", response.data.storeDetails);
                // this.getSmtpData();
                // this.getSmsData();
            }).catch((error) => {
                if (error.response.status == 403) {
                    this.$router.push("/admin/unauthorized");
                } else {
                    console.log(error);
                }
            });
        },
        updateStoreDetails() {
            //  console.log(this.storeDetails);
            axios.post("/api/admin/settings/update", {
                name: this.storeDetails.name,
                address: this.storeDetails.address,
                contact: this.storeDetails.contact,
                email: this.storeDetails.email,
                logo: this.storeDetails.logo,
                enablesms: this.storeDetails.enablesms,
            }).then((response) => {
                if (response.data.success) {
                    this.storeDetails = response.data.storeDetails;
                    this.$store.commit("app/setStoreDetails", response.data.storeDetails);
                    // this.updateSmtpDetails();
                    // this.updateSmsDetails();
                    this.$toasted.success("General Settings updated successfully").goAway(2000);
                } else {
                    this.$toasted.error("Something went wrong").goAway(2000);
                }
            });
        },
        ...mapActions("app", ["getSmtpData"]),
        getSmtpData() {
            axios.get("/api/smtp").then((response) => {
                this.smtpSettings = response.data.smtpSettings;
                // set store details to vuex
                this.$store.commit("app/setSmtpDetails", response.data.smtpSettings);
            });
        },
        updateSmtpDetails() {
            //  console.log(this.smtpSettings);
            // check if smtp details are filled
            if (!this.smtpSettings.host || !this.smtpSettings.port || !this.smtpSettings.username || !this.smtpSettings.password) {
                this.$toasted.error("Please fill all SMTP details").goAway(2000);
                return;
            }
            axios.post("/api/admin/settings/smtp/update", this.smtpSettings).then((response) => {
                if (response.data.success) {
                    this.smtpSettings = response.data.smtpSettings;
                    this.$store.commit("app/setSmtpDetails", response.data.smtpSettings);

                    this.$toasted.success("SMTP details updated successfully").goAway(2000);
                } else {
                    this.$toasted.error("Something went wrong").goAway(2000);
                }
                this.getSmtpData();
            });
        },
        testsmtp() {
            axios.post("/api/admin/settings/smtp/test").then((response) => {
                if (response.data.success) {
                    this.$toasted.success("SMTP details are correct").goAway(2000);
                } else {
                    this.$toasted.error("SMTP details are incorrect").goAway(2000);
                }
            });
        },
        ...mapActions("app", ["getSmsData"]),
        getSmsData() {
            axios.get("/api/sms").then((response) => {
                this.smsSettings = response.data.smsSettings;
                // set store details to vuex
                this.$store.commit("app/setSmsDetails", response.data.smtpSettings);
            });
        },
        updateSmsDetails() {
            console.log(this.smsSettings);
            axios.post("/api/admin/settings/sms/update", this.smsSettings).then((response) => {
                if (response.data.success) {
                    this.smsSettings = response.data.smsSettings;
                    this.$store.commit("app/setSmsDetails", response.data.smsSettings);

                    this.$toasted.success("SMS details updated successfully").goAway(2000);
                } else {
                    this.$toasted.error("Something went wrong").goAway(2000);
                }
            });
        },
    },
    mounted() {
        this.getStoreData();

    },
    computed: {

    },
}
</script>