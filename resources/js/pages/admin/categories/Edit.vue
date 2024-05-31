<template>
  <div style="width: 100%">
    <div>
      <div class="display-1">Edit Category</div>
      <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
    </div>
    <v-card>
      <!-- <v-card-title>
                <span class="headline">Edit Category</span>
            </v-card-title> -->
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-row>
            <v-col cols="12" md="12">
              <v-text-field v-model="category.name" outlined :rules="nameRules" label="Name" required></v-text-field>
            </v-col>
            <v-col cols="12" md="12">
              <v-textarea v-model="category.description" outlined :rules="descriptionRules" label="Description"
                required></v-textarea>
            </v-col>
            <v-col cols="12" md="12">
              <!-- <v-img
                            :src="'/storage/images/' + category.image"
                            v-if="category.image != '' && category.image != null"
                            class="align-center"
                            /> -->

              <!-- <v-hover v-slot:default="{ hover }">
                  <v-avatar
                    :size="150"
                    :color="hover ? 'primary' : ''"
                    color="#cccccc"
                  >
                    <v-img
                            :src="'/storage/images/' + category.image"
                            v-if="category.image != '' && category.image != null"
                            class="align-center"
                            /> 
                    <span class="headline text-h1" v-else>{{
                      category.image.charAt(0)
                    }}</span>
                  </v-avatar>
                </v-hover> -->
              <v-combobox v-model="currentimages" label="Image Name" variant="solo" chips multiple>
              </v-combobox>

              <v-file-input small-chips truncate-length="15" chips multiple outlined label="Image"
                @change="onimagechange" required></v-file-input>
            </v-col>
            <v-col cols="12" md="12">
              <div id="searchbox"></div>
              <div class="map" id="map" style="height: 450px"></div>
            </v-col>
            <v-col cols="12" md="12">
              <v-select v-model="category.parent_id" :items="categories" outlined label="Parent Category"
                required></v-select>
            </v-col>

            <v-col cols="12" md="12">
              <v-spacer></v-spacer>
              <v-btn color="error" @click="$router.push('/admin/categories')">Cancel</v-btn>
              <v-btn color="primary" @click="save" :loading="loading" :diabled="fetchinglocation">Save</v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>
<style>
.mapboxgl-popup {
  max-width: 400px;
  font:
    12px/20px 'Helvetica Neue',
    Arial,
    Helvetica,
    sans-serif;
}

#searchbox,
#searchbox .mapboxgl-ctrl {
  width: 100% !important;
}

#searchbox {
  margin-bottom: 15px;
}
</style>
<script>
import axios from "axios";
import VueEditor from "vue2-editor";

export default {
  components: {
    VueEditor,
  },
  data() {
    return {
      category: {
        name: "",
        description: "",
        location: "",
        location_data: ''
      },
      categoryimage: [],
      breadcrumbs: [
        {
          text: "Categories",
          disabled: false,
          to: "/admin/categories",
        },
        {
          text: "Edit Category",
        },
      ],
      categories: [],
      valid: true,
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => (v && v.length <= 50) || "Name must be less than 50 characters",
      ],
      descriptionRules: [
        (v) => !!v || "Description is required",
        (v) =>
          (v && v.length <= 255) ||
          "Description must be less than 255 characters",
      ],
      locationdata: [],
      fetchinglocation: false,
      currentimages: [],
      loading: false,
    };
  },
  methods: {
    onimagechange(e) {
      const files = e;

      for (let index = 0; index < files.length; index++) {
        this.categoryimage.push(files[index]);
        // this.partnerimages.push(URL.createObjectURL(files[index]));
      }
      // this.newimage = URL.createObjectURL(files);
    },
    async save() {
      this.loading = true;
      let formData = new FormData();
      // multipart form data
      this.categoryimage.forEach((image) => {
        formData.append("image[]", image);
      });
      formData.append("name", this.category.name);
      formData.append("description", this.category.description);

      formData.append("status", "Active");
      formData.append("parent_id", this.category.parent_id);
      formData.append("id", this.category.id);
      formData.append("location", this.category.location);
      formData.append("location_data", this.locationdata);
      await axios
        .post("/api/admin/category/update", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.$toasted.show("Category Updated successfully", {
              type: "success",
              duration: 2000,
            });
            this.$router.push("/admin/categories");
            this.loading = false;
          } else {
            this.$toasted.show("Category not Updated", {
              type: "error",
              duration: 2000,
            });
            this.loading = false;
          }
        });
    },
    async getlocationdata() {
      // console.log('getlocationdata');
      // this.showmap = true;
      var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

      mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
      const map = new mapboxgl.Map({
        container: 'map', // container id
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
        style: 'mapbox://styles/amartchsmarters/clwri9yls013g01pn700x455p',
        center: [JSON.parse(this.category.location_data).lng, JSON.parse(this.category.location_data).lat], // starting position
        zoom: 16 // starting zoom
      });
      const geolocation = new mapboxgl.GeolocateControl({
        positionOptions: {
          enableHighAccuracy: true
        },
        // When active the map will receive updates to the device's location as it changes.
        trackUserLocation: true,
        // Draw an arrow next to the location dot to indicate which direction the device is heading.
        showUserHeading: true
      })


      // load js from url and use it

      const searchBox = new MapboxSearchBox();
      searchBox.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
      searchBox.options = {
        types: 'address,poi',
        // proximity: [-73.99209, 40.68933],
        language: 'en',
        country: 'IN',
      };

      searchBox.marker = false;
      searchBox.mapboxgl = mapboxgl;


      map.addControl(geolocation);
      map.addControl(searchBox);
      document.getElementById('searchbox').appendChild(searchBox.onAdd(map));
      searchBox.placeholder = 'Enter Full Location';
      searchBox.value = this.category.location;
      let marker = new mapboxgl.Marker({ color: 'black' })
      marker.setLngLat(JSON.parse(this.category.location_data))
        .addTo(map)
      map.on('click', (e) => {
        console.log(e)
        marker.setLngLat(e.lngLat)
          .addTo(map)

        axios.get('https://api.mapbox.com/search/geocode/v6/reverse?longitude=' + e.lngLat.lng + '&latitude=' + e.lngLat.lat + '&access_token=' + mapboxgl.accessToken + '').then(response => {

          searchBox.value = response.data.features[0].properties.full_address;
          this.category.location = response.data.features[0].properties.full_address;

        })

        this.locationdata = JSON.stringify({ lng: e.lngLat.lng, lat: e.lngLat.lat });
      });

    },
    async getcategory(id) {
      await axios
        .get("/api/admin/category/edit/" + id)
        .then((response) => {
          this.category = response.data.category;
          this.currentimages = this.category.image.split(",");

          this.getlocationdata();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getcategories() {
      await axios
        .get("/api/admin/category/list")
        .then((response) => {
          this.categories.push({
            value: 0,
            text: "No Parent",
          });
          response.data.categories.forEach((category) => {
            if (category.id != this.$route.params.id) {
              this.categories.push({ value: category.id, text: category.name });
            }
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    if (this.$route.params.id) {
      this.getcategory(this.$route.params.id);
    }
    if (this.category.location_data == null) {
      this.category.location_data = '{"lng": 77.5946, "lat": 12.9716}';
    }
    this.getcategories();
    const mapboxglcss = document.createElement("link");
    mapboxglcss.setAttribute("rel", "stylesheet");
    mapboxglcss.setAttribute(
      "href",
      "https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.css"
    );
    document.head.appendChild(mapboxglcss);
    // const gljs = document.createElement("script");
    // gljs.setAttribute(
    //     "src",
    //     "https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.js"
    // );
    // gljs.async = true;
    // document.head.appendChild(gljs);
    // load js from url and use it
    const plugin = document.createElement("script");
    plugin.setAttribute(
      "src",
      "//api.mapbox.com/search-js/v1.0.0-beta.21/web.js"
    );
    plugin.async = true;
    document.head.appendChild(plugin);

    // add geocoder
    const geocoder = document.createElement("script");
    geocoder.setAttribute(
      "src",
      "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"
    );
    geocoder.async = true;
    document.head.appendChild(geocoder);

    // add geocoder css
    const geocodercss = document.createElement("link");
    geocodercss.setAttribute("rel", "stylesheet");
    geocodercss.setAttribute(
      "href",
      "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
    );
    document.head.appendChild(geocodercss);

  },
};
</script>