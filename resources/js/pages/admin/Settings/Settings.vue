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
                        <v-tab>Smtp Setting</v-tab>
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
                                </tr>
                                <h3>Google Credential</h3>

                                <tr>
                                    <th>Client ID</th>
                                    <td><v-text-field label="Google Client Id"
                                            v-model="storeDetails.clientid"></v-text-field>
                                    </td>
                                    <th>Client Secret</th>
                                    <td><v-text-field label="Google Client Secret"
                                            v-model="storeDetails.clientsecret"></v-text-field>
                                    </td>
                                </tr>
                            </v-simple-table>
                        </v-tab-item>
                        <v-tab-item>
                            <v-simple-table>
                                <tr>
                                    <th>
                                        Host
                                    </th>
                                    <td>
                                        <v-text-field label="Host*" v-model="smtpSettings.host"
                                            :rules="required"></v-text-field>
                                    </td>
                                    <th>
                                        Port
                                    </th>
                                    <td>
                                        <v-text-field label="Port*" v-model="smtpSettings.port"
                                            :rules="required"></v-text-field>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Username
                                    </th>
                                    <td>
                                        <v-text-field label="Username*" v-model="smtpSettings.username"
                                            :rules="required"></v-text-field>
                                    </td>
                                    <th>
                                        Password
                                    </th>
                                    <td>
                                        <v-text-field label="Password*" v-model="smtpSettings.password"
                                            :rules="required"></v-text-field>
                                    </td>
                                </tr>
                                <tr>
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
            tab: null,
            askpassword: false,
            storeDetails: {
                name: "",
                address: "",
                contact: "",
                email: "",
                logo: "",
                clientsecret: "",
                clientid: "",
                enablesms: true,
            },
            smtpSettings: {
                host: "",
                port: "587",
                username: "",
                password: "",
                encryption: "tls",
            },
            encryptionOptions: [
                "tls",
                "ssl"
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
                this.smtpSettings.encryption = response.data.storeDetails.smtpencryption
                this.smtpSettings.host = response.data.storeDetails.smtphost
                this.smtpSettings.port = response.data.storeDetails.smtpport
                this.smtpSettings.username = response.data.storeDetails.smtpusername
                this.smtpSettings.password = response.data.storeDetails.smtppassword
                // set store details to vuex 
                this.$store.commit("app/setStoreDetails", response.data.storeDetails);
            }).catch((error) => {
                if (error.response.status == 403) {
                    this.$router.push("/admin/unauthorized");
                } else {
                    console.log(error);
                }
            });
        },
        updateStoreDetails() {
            axios.post("/api/admin/settings/update", {
                name: this.storeDetails.name,
                address: this.storeDetails.address,
                contact: this.storeDetails.contact,
                email: this.storeDetails.email,
                logo: this.storeDetails.logo,
                clientid: this.storeDetails.clientid,
                clientsecret: this.storeDetails.clientsecret,
                enablesms: this.storeDetails.enablesms,
                smtphost: this.smtpSettings.host,
                smtpport: this.smtpSettings.port,
                smtpusername: this.smtpSettings.username,
                smtppassword: this.smtpSettings.password,
                smtpencryption: this.smtpSettings.encryption,
            }).then((response) => {
                if (response.data.success) {
                    this.storeDetails = response.data.storeDetails;
                    this.$store.commit("app/setStoreDetails", response.data.storeDetails);
                    this.$toasted.success("Settings updated successfully").goAway(2000);
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