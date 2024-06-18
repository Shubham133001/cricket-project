<template>
  <v-card class="pa-2">
     <v-form ref="form" v-model="isFormValid" lazy-validation>
    <v-card-title class="justify-center display-1 mb-2"
      >Set new password</v-card-title
    >
    <div class="overline" style="font-size: 12px;">{{ status }}</div>
    <div class="error--text mt-2 mb-4">{{ error }}</div>

    <a v-if="error" href="/">Back to Sign In</a>

    <v-text-field
      v-if="verified"
      v-model="newPassword"
      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
      :rules="[  passwordrules.required,
            passwordrules.alpha,
            passwordrules.capsAlpha,
            passwordrules.num,
            passwordrules.minLength,]"
      :type="showPassword ? 'text' : 'password'"
      :error="errorNewPassword"
      :error-messages="errorNewPasswordMessage"
      name="password"
      label="New Password"
      outlined
      class="mt-4"
      @change="resetErrors"
      @keyup.enter="confirmPasswordReset"
      @click:append="showPassword = !showPassword"

    ></v-text-field>

    <v-text-field
      v-if="verified"
      v-model="confirmPassword"
      :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
      :rules="[ 
        passwordrules.required,
        passwordrules.matchNewPassword
      ]"
      :type="showConfirmPassword ? 'text' : 'password'"
      :error="errorConfirmPassword"
      :error-messages="errorConfirmPasswordMessage"
      name="confirmPassword"
      label="Confirm Password"
      outlined
      class="mt-4"
      @change="resetErrors"
      @keyup.enter="confirmPasswordReset"
      @click:append="showConfirmPassword = !showConfirmPassword"
    ></v-text-field>

    <v-btn
      :loading="isLoading"
      :disabled="isDisabled"
      block
      depressed
      x-large
      color="primary"
      @click="confirmPasswordReset"
      >Set new password and Sign In</v-btn
    >
   </v-form>
  </v-card>
</template>

this.$router.push('/auth/verify-email')
<script>
/*
|---------------------------------------------------------------------
| Reset Page Component
|---------------------------------------------------------------------
|
| Page Form to insert new password and proceed to sign in
|
*/
export default {
  data() {
    return {
      isFormValid: true,
      isLoading: true,
      isDisabled: true,
      resendemail: false,
      showNewPassword: true,
      newPassword: "",
      confirmPassword: '',
      errorConfirmPassword: false,
      errorConfirmPasswordMessage: '',
      showConfirmPassword: false,
      // form error
      errorNewPassword: false,
      errorNewPasswordMessage: "",

      // show password field
      showPassword: false,

      status: "Verifying Token",
      error: null,

      verified: false,

      // input rules
      rules: {
        required: (value) => (value && Boolean(value)) || "Required",
      },
      passwordrules: {
          required: (value) => (value && Boolean(value)) || "Required",
          alpha: (value) =>
            /[a-z]+/.test(value) || "Required atleast one Lowercase Letter required",
          capsAlpha: (value) =>
            /[A-Z]+/.test(value) || "Required atleast one Uppercase Letter required",
          num: (value) => /[0-9]+/.test(value) || "Required atleast one numeric",
          minLength: (value) =>
            value.length >= 8 || "Password Must be of min. 8 characters",
          matchNewPassword: v => v === this.newPassword || 'Passwords do not match'
        },
    };
  },
  methods: {
    resetErrors() {
      this.errorNewPassword = false;
      this.errorNewPasswordMessage = '';
      this.errorConfirmPassword = false;
      this.errorConfirmPasswordMessage = '';
    },
    verifytoken() {
      // console.log("Verifying Token");
      var postdata = {
        email: this.$route.query.email,
        token: this.$route.query.token,
      };
      const requestOptions = {
        headers: { "Content-Type": "application/json" },
      };
      axios
        .post("/api/verifyresetpasswordtoken", postdata, requestOptions)
        .then((res) => {
          
          if (res.data == "success") {
            this.verified = true;
            this.status = "Reset your password";
            this.isLoading = false;
            this.isDisabled = false;
            this.resendemail = false;
          } else {
            this.verified = true;
            this.status = "Verification Link Expired/Invalid.";
            this.isLoading = false;
            this.isDisabled = true;
            this.resendemail = true;
          }
        });
    },
    confirmPasswordReset() {
      if (this.$refs.form.validate()) {
        if (this.newPassword !== this.confirmPassword) {
        this.errorConfirmPassword = true;
        this.errorConfirmPasswordMessage = 'Passwords do not match';
      } else {
        this.errorConfirmPassword = false;
        this.errorConfirmPasswordMessage = '';
        // Proceed with password reset logic
      }
      this.isLoading = true;
      var postdata = {
        email: this.$route.query.email,
        password: this.newPassword,
        token: this.$route.query.token,
      };
      const requestOptions = {
        headers: { "Content-Type": "application/json" },
      };
      axios
        .post("/api/reset-password", postdata, requestOptions)
        .then((res) => {
          if(res.data.success==false){
            this.$toasted
              .show(res.data.message, {
                type: "error",
                icon: "",
                action: {
                  text: "Close",
                  onClick: (e, toastObject) => {
                    toastObject.goAway(0);
                  },
                },
              })
              .goAway(10000);
            this.isLoading = false;
            this.isDisabled = false;
          }else{
            this.$toasted
              .show("Password Reset Successfully.", {
                type: "success",
                icon: "",
                action: {
                  text: "Close",
                  onClick: (e, toastObject) => {
                    toastObject.goAway(0);
                  },
                },
              })
              .goAway(10000);
            this.$router.push("/");
            this.isLoading = false;
            this.isDisabled = false;
          }
        });
      }
      // setTimeout(() => {
      //   this.isLoading = false
      // }, 500)
    },
    resetErrors() {
      this.errorNewPassword = false;
      this.errorNewPasswordMessage = "";
    },
  },
  mounted() {
    this.verifytoken();
  },
};
</script>
