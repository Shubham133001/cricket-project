<template>
    <div class="d-flex flex-column flex-grow-1">
        <v-row>
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-title>
                        <span class="headline">SMTP Settings</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn color="primary" @click="updateSmtpDetails">Save</v-btn>
                        <v-btn color="error" @click="askpassword = false">Cancel</v-btn>
                        <v-btn color="error" @click="testsmtp">Test SMTP</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
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
                askpassword: false,
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
            ...mapActions("app", ["getSmtpData"]),
            getSmtpData() {
                axios.get("/api/smtp").then((response) => {
                    this.smtpSettings = response.data.smtpSettings;
                    // set store details to vuex
                    this.$store.commit("app/setSmtpDetails", response.data.smtpSettings);
                });
            },
            updateSmtpDetails() {
                console.log(this.smtpSettings);
                axios.post("/api/admin/settings/smtp/update", this.smtpSettings).then((response) => {
                    if (response.data.success) {
                        this.smtpSettings = response.data.smtpSettings;
                        this.$store.commit("app/setSmtpDetails", response.data.smtpSettings);

                        this.$toasted.success("SMTP details updated successfully").goAway(2000);
                    } else {
                        this.$toasted.error("Something went wrong").goAway(2000);
                    }
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
            }
        },
        mounted() {
            this.getSmtpData();

        },
        computed: {

        },
    }
</script>