<template>
  <v-card class="pa-2">
    <v-form ref="form" v-model="isFormValid" lazy-validation>
      <v-card-title class="justify-center display-1 mb-2">Set new password</v-card-title>
      <div class="overline" style="font-size: 12px;">{{ status }}</div>
      <div class="error--text mt-2 mb-4">{{ error }}</div>

      <a v-if="error" href="/">Back to Sign In</a>
      <v-text-field v-model="newPassword" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        :rules="[rules.required]" :type="showPassword ? 'text' : 'password'" :error="errorNewPassword"
        :error-messages="errorNewPasswordMessage" name="password" label="New Password" outlined class="mt-4"
        @change="resetErrors" @click:append="showPassword = !showPassword"></v-text-field>

      <v-text-field v-model="newConfirmPassword" :append-icon="showPassword1 ? 'mdi-eye' : 'mdi-eye-off'"
        :rules="[rules.required, rules.confirm]" :type="showPassword1 ? 'text' : 'password'"
        :error="errorNewConfirmPassword" :error-messages="errorNewConfirmPasswordMessage" name="password"
        label="Confirm Password" outlined class="mt-4" @change="resetErrors" @keyup.enter="confirmPasswordReset"
        @click:append="showPassword1 = !showPassword1"></v-text-field>

      <v-btn :loading="isLoading" block depressed x-large color="primary" @click="confirmPasswordReset">Set new password
        and Sign In</v-btn>
    </v-form>
  </v-card>
</template>

this.$router.push('/auth/verify-email')
<script>
export default {
  data() {
    return {
      isFormValid: true,
      isLoading: true,
      isDisabled: true,
      resendemail: false,
      showNewPassword: true,
      showNewConfirmPassword: true,
      newPassword: '',
      newConfirmPassword: '',
      token: this.$route.query.token,
      email: this.$route.query.email,

      // form error
      errorNewPassword: false,
      errorNewPasswordMessage: '',

      errorNewConfirmPassword: false,
      errorNewConfirmPasswordMessage: '',

      // show password field
      showPassword: false,
      showPassword1: false,

      status: 'Resetting password',
      error: null,
      verified: false,
      rules: {
        required: (value) => (value && Boolean(value)) || 'Required',
        confirm: (value) => value === this.newPassword || 'Password does not match'
      }
    }
  },
  methods: {
    confirmPasswordReset() {
      this.isLoading = true
      axios.post('/api/user/resetpassword', {
        email: this.email,
        token: this.token,
        password: this.newPassword,
        confirmPassword: this.newConfirmPassword
      }).then(response => {
        this.isLoading = false
        if (response.data.success) {
          this.status = 'Password reset successful'
          this.$toasted.show('Password reset successful', { type: 'success', duration: 5000 })
          this.$router.push('/')

        } else {
          this.error = response.data.message
          this.$toasted.show(response.data.message, { type: 'error', duration: 5000 })
        }

      }).catch(error => {
        this.isLoading = false
        this.error = error.response.data.message
      })
    },
    resetErrors() {
      this.errorNewPassword = false;
      this.errorNewPasswordMessage = '';
      this.errorConfirmPassword = false;
      this.errorConfirmPasswordMessage = '';
    },
  },
  mounted() {
    this.verifytoken();
  },
};
</script>
