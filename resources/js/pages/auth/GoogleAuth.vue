<template>
  <v-card class="pa-2 text-center">
    <h1>Please Wait Authenticating User</h1>

    <v-btn depressed large class="mt-2" color="primary" to="/" :loading="disabled" :disabled="disabled">Take Me
      Back</v-btn>
    <!-- <div class="mb-6 overline">Please check your email for the link to verify the email.</div> -->

    <!-- <v-btn :loading="isLoading" :disabled="disabled" block depressed x-large color="primary" @click="resend">Re-send
      email {{ seconds }}</v-btn> -->
  </v-card>
</template>

<script>
/*
|---------------------------------------------------------------------
| Verify Email Page Component
|---------------------------------------------------------------------
|
| Template to wait for the verification on the user email
|
*/

const TIMEOUT = 30
import { EventBus } from "../../event-bus.js";
export default {
  data() {
    return {
      isLoading: false,
      disabled: true,
      times: 0,
      resendInterval: null,
      secondsToEnable: TIMEOUT,
      seconds: ''
    }
  },
  mounted() {
    this.setTimer()
    this.authgoogle()
  },
  beforeDestroy() {

  },
  methods: {
    setTimer() {
      this.disabled = true
      this.secondsToEnable = TIMEOUT
      this.resendInterval = setInterval(() => {
        this.secondsToEnable--
        if (this.secondsToEnable <= 0) {
          clearInterval(this.resendInterval)
          this.disabled = false
        }
      }, 1000)
    },
    async authgoogle() {
      this.isLoading = true

      await axios
        .post('/api/user/auth/google', {
          code: this.$route.query.code
        })
        .then(response => {
          if (response.data.success) {
            this.isLoading = false
            localStorage.setItem("token", response.data.token);
            localStorage.setItem("userdetails", JSON.stringify(response.data.user));
            EventBus.$emit("isUserLogin", true);
            this.$toasted.show('User Loggedin SuccessFully', {
              type: 'success',
              duration: 5000
            })
            window.location.href = '/user/team'
          }
          else {
            this.isLoading = false

            this.$router.push('/')
            this.$toasted.show('User Creation Failed', {
              type: 'error',
              duration: 5000
            })
          }
        })
        .catch(error => {
          this.isLoading = false

          this.$router.push('/')
          this.$toasted.show('User Creation Failed', {
            type: 'error',
            duration: 5000
          })
        })


    },
  }
}
</script>
