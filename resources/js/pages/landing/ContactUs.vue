<template>
  <div>
    <v-row style="display: flex; justify-content: center; align-items: center">
      <v-col cols="6" md="3">
        <div class="text-h6 text-lg-h5 font-weight-bold">Contact Us</div>
        <div style="width: 80px; height: 2px" class="mb-5 mt-1 primary" />
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2"
            >mdi-map-marker-outline</v-icon
          >
          S.C.F 24-25 FF, Phase 5, Sector 59, Sahibzada Ajit Singh Nagar, Punjab
          160059, Tel. 2261900,5096333
        </div>
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2"
            >mdi-phone-outline</v-icon
          >
          <a href="#" class="text-decoration-none text--primary"
            >Mobile: 54223223223
          </a>
        </div>
        <div class="d-flex mb-2 font-weight-bold">
          <v-icon color="primary lighten-1" class="mr-2"
            >mdi-email-outline</v-icon
          >
          <a href="#" class="text-decoration-none text--primary"
            >Email: dummy@techsmarters.com</a
          >
        </div>
      </v-col>

      <v-col cols="8" md="6">
         <div class="text-h6 text-lg-h5 font-weight-bold contactform">Contact Us</div>
        <v-form v-model="valid" ref="form">
          <v-text-field
            v-model="name"
            :rules="nameRules"
            label="Name"
            required
          ></v-text-field>

          <v-text-field
            v-model="email"
            :rules="emailRules"
            label="Email"
            required
          ></v-text-field>

          <v-text-field
            v-model="phone"
            :rules="phoneRules"
            label="Phone"
            required
          ></v-text-field>

          <v-textarea
            v-model="message"
            :rules="messageRules"
            label="Message"
            required
          ></v-textarea>

          <v-btn @click="submit" :disabled="!valid" color="primary"
            >Submit</v-btn
          >
        </v-form>
      </v-col>
      <v-col cols="9" md="9">
        <div class="google-map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3429.9940956989876!2d76.67900817497208!3d30.718566386318113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fef353d082d21%3A0x71f726a3f15b6bff!2sBEST%20CRICKET%20ACADEMY!5e0!3m2!1sen!2sin!4v1715835343639!5m2!1sen!2sin"
            width="600"
            height="450"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          >
          </iframe>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  data() {
    return {
      valid: false,
      name: "",
      email: "",
      phone: "",
      message: "",
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => (v && v.length <= 50) || "Name must be less than 50 characters",
      ],
      emailRules: [
        (v) => !!v || "Email is required",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ],
      phoneRules: [
        (v) => !!v || "Phone is required",
        (v) => /^\d{10}$/.test(v) || "Phone must be 10 digits",
      ],
      messageRules: [
        (v) => !!v || "Message is required",
        (v) =>
          (v && v.length <= 500) || "Message must be less than 500 characters",
      ],
    };
  },
  methods: {
    async submit() {
      if (this.$refs.form.validate()) {
        try {
          const formData = {
            name: this.name,
            email: this.email,
            phone: this.phone,
            message: this.message,
          };
          axios.post("/api/contactus", formData, {}).then((response) => {
            if (response.data.success) {
              this.$toasted.show(response.data.message, {
                type: "success",
                duration: 2000,
              });
            }
          });
          this.$refs.form.reset();
        } catch (error) {
          this.$toast.error(response.data.message);
        }
      }
    },
  },
};
</script>
<style scoped>
.google-map {
  padding-bottom: 50%;
  position: relative;
}

.contactform{
    margin-top: 194px;
}

.google-map iframe {
  height: 100%;
  width: 100%;
  left: 0;
  top: 0;
  position: absolute;
}
</style>