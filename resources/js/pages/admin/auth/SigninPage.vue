<template>
  <div>
    <v-card class="text-center pa-1">
      <v-card-title class="justify-center display-1 mb-2">Welcome</v-card-title>
      <v-card-subtitle>Sign in to your account</v-card-subtitle>

      <!-- sign in form -->
      <v-card-text>
        <v-form ref="form" v-model="isFormValid" lazy-validation>
          <v-text-field
            v-model="email"
            :rules="[rules.required]"
            :validate-on-blur="false"
            :error="error"
            :label="$t('login.email')"
            name="email"
            outlined
            @keyup.enter="submit"
            @change="resetErrors"
          ></v-text-field>

          <v-text-field
            v-model="password"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required]"
            :type="showPassword ? 'text' : 'password'"
            :error="error"
            :error-messages="errorMessages"
            :label="$t('login.password')"
            name="password"
            outlined
            @change="resetErrors"
            @keyup.enter="submit"
            @click:append="showPassword = !showPassword"
          ></v-text-field>

          <v-btn
            :loading="isLoading"
            :disabled="isSignInDisabled"
            block
            x-large
            color="primary"
            @click="submit"
          >{{ $t('login.button') }}</v-btn>

          <div v-if="errorProvider" class="error--text">{{ errorProviderMessages }}</div>

          <!-- <div class="mt-5">
            <router-link to="/auth/forgot-password">
              {{ $t('login.forgot') }}
            </router-link>
          </div> -->
        </v-form>
      </v-card-text>
    </v-card>


  </div>
</template>

<script>
/*
|---------------------------------------------------------------------
| Sign In Page Component
|---------------------------------------------------------------------
|
| Sign in template for user authentication into the application
|
*/
export default {
  data() {
    return {
      // sign in buttons
      isLoading: false,
      isSignInDisabled: false,

      // form
      isFormValid: true,
      email: '',
      password: '',

      // form error
      error: false,
      errorMessages: '',

      errorProvider: false,
      errorProviderMessages: '',

      // show password field
      showPassword: false,

      providers: [{
        id: 'google',
        label: 'Google',
        isLoading: false
      }, {
        id: 'facebook',
        label: 'Facebook',
        isLoading: false
      }],

      // input rules
      rules: {
        required: (value) => (value && Boolean(value)) || 'Required'
      }
    }
  },
  mounted: function () {
    // set the menu items
    if (localStorage.getItem("adminData")) {
      this.$router.push("/admin/dashboard");
    }
  },
  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true
        this.isSignInDisabled = true
        this.signIn(this.email, this.password)
      }
    },
    signIn(email, password) {
        const article = {
        password: password,
        email: email
      };

      axios
        .post("/api/admin/signin", article)
        .then((response) => {
            const anythng = btoa(JSON.stringify(response.data));
            localStorage.setItem("adminData",anythng);
            this.$router.push("/admin/dashboard");
        })
        .catch((error) => {
          if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            }
        this.$toasted.show(error.response.data.message,{
            type: "error",
            icon : 'error_outline',
            action : {
                text : 'Cancel',
                onClick : (e, toastObject) => {
                    toastObject.goAway(0);
                }
            }
        }).goAway(2000);
        this.isLoading = false;
        this.isSignInDisabled = false
        });
    },
    signInProvider(provider) {},
    resetErrors() {
      this.error = false
      this.errorMessages = ''

      this.errorProvider = false
      this.errorProviderMessages = ''
    }
  }
}
</script>
