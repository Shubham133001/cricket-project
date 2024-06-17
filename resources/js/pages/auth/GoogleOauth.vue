<template>
    <div>
        <v-card-text>
            <v-alert type="info" transition="scale-transition">
                <h3>Please wait ..</h3>
            </v-alert>
        </v-card-text>
    </div>
</template>
<script>
import { EventBus } from "../../event-bus.js";
import { mapState, mapActions } from "vuex";
export default {
    data() {
        return {
            googlecode: this.$route.query.code,
        };
    },
    mounted() {
        this.verifyGoogleCode();
    },

    created() {
        EventBus.$on("isUserLogin", (status) => {
            this.isUserlogin = status;
        });
    },
    methods: {
        ...mapActions("app", ["getStoreData", "setUserdetails", "getUserLogin"]),
        verifyGoogleCode() {
            axios
                .post("/api/loginwithgoogle", {
                    google_code: this.googlecode,
                })
                .then((response) => {
                    if (response.data.success == true) {
                        this.$toasted.show(response.data.message, {
                            type: "success",
                        });
                        localStorage.setItem(
                            "userdetails",
                            JSON.stringify(response.data.user)
                        );
                        this.userdetails = response.data.user;
                        localStorage.setItem("token", response.data.access_token);
                        this.isUserlogin = true;
                        EventBus.$emit("isUserLogin", true);
                        this.openlogindialog = false;
                        this.$toasted.show(response.data.message, {
                            type: "success",
                            duration: 5000,
                        });
                        this.$router.push("/");
                    } else {
                        this.$toasted.show(response.data.message, {
                            type: "error",
                        });
                        this.$router.push("/");
                    }
                });
        },
    },
};
</script>