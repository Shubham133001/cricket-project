<template>
  <div>
    <v-card class="text-center pa-1">
      <v-card-title class="justify-center display-1 mb-2">{{ $t('forgot.title') }}</v-card-title>
      <v-card-subtitle>
        {{ $t('forgot.subtitle') }}
      </v-card-subtitle>

      <!-- reset form -->
      <v-card-text>
        <v-form ref="form" v-model="isFormValid" lazy-validation @submit.prevent="submit">
          <v-text-field
            v-model="email"
            :rules="[rules.required]"
            :validate-on-blur="false"
            :error="error"
            :error-messages="errorMessages"
            :label="$t('forgot.email')"
            name="email"
            outlined
            @keyup.enter="submit"
            @change="resetErrors"
          ></v-text-field>

          <v-btn
            :loading="isLoading"
            block
            x-large
            color="primary"
            @click="submit"
          >{{ $t('forgot.button') }}</v-btn>
        </v-form>
      </v-card-text>
    </v-card>

    <div class="text-center mt-6">
      <router-link to="/auth/signin">
        {{ $t('forgot.backtosign') }}
      </router-link>
    </div>
  </div>
</template>

<script>
/*
|---------------------------------------------------------------------
| Forgot Page Component
|---------------------------------------------------------------------
|
| Template to send email to remember/replace password
|
*/
export default {
  data() {
    return {
      // reset button
      isLoading: false,

      // form
      isFormValid: true,
      email: '',

      // form error
      error: false,
      errorMessages: '',

      // input rules
      rules: {
        required: (value) => (value && Boolean(value)) || 'Required'
      }
    }
  },
  methods: {
    // submit(e) {
    //   if (this.$refs.form.validate()) {
    //     console.log('submit')
    //   }
    // },
    // resetEmail(email, password) {
    // },

    submit(e) {
      if (this.$refs.form.validate()) {
        this.isLoading = true;
        this.isSignInDisabled = true;
        if (this.robot == false && this.showCaptcha == true) {
          this.$toasted
            .show("Please confirm you are not a robot.", {
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
          this.isSignInDisabled = false;
        } else {
          this.forgotpasswprd(this.email);
        }
      }
    },
    forgotpasswprd(email) {
      var postdata = {
        email: email,
      };
      const requestOptions = {
        headers: { "Content-Type": "application/json" },
      };
      axios
        .post("/api/forgot-password", postdata, requestOptions)
        .then((res) => {
          this.isLoading = false;
          this.isSignInDisabled = false;

          if (res.status == 200) {
            if (res.data.success) {
              localStorage.setItem("resetemail", email);
              this.$toasted
                .show("Password Reset Email Sent Successfully.", {
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
            } else {
              if (typeof res.data.message !== "undefined") {
                if (res.data.message == "Email Not Verified.") {
                  this.$toasted
                    .show(
                      "Email Not Verified. Please verify your email first.",
                      {
                        type: "error",
                        icon: "",
                        action: {
                          text: "Close",
                          onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                          },
                        },
                      }
                    )
                    .goAway(10000);
                } else {
                  this.$toasted
                    .show("Email ID Not found.", {
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
                }

                this.isLoading = false;
              }
            }
          }
        })
        .catch((err) => {
          if (err.status != 200) {
            this.$toasted
              .show("Invalid Login Details", {
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
          } else if (err.request) {
            // The request was made but no response was received
            this.$toasted.show(err.message, {
              type: "error",
              icon: "",
              duration: 10000,
              action: {
                text: "Retry",
                onClick: (e, toastObject) => {
                  toastObject.goAway(0);
                  this.submit();
                },
              },
            });
          } else {
            // Something happened in setting up the request that triggered an Error
            this.$toasted.show(err, {
              type: "error",
              icon: "",
              action: {
                text: "Close",
                onClick: (e, toastObject) => {
                  toastObject.goAway(0);
                },
              },
            });
          }
          this.isLoading = false;
          this.isSignInDisabled = false;
        });
    },
    resetErrors() {
      this.error = false;
      this.errorMessages = "";
    },
     

    // resetErrors() {
    //   this.error = false
    //   this.errorMessages = ''
    // }
  }
}
</script>
