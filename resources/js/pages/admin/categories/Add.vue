<template>
    <div style="width: 100%">
        <div>
            <div class="display-1">Add New Category</div>
            <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
        </div>
        <v-card>
            <!-- <v-card-title>
                <span class="headline">Add New Category</span>
            </v-card-title> -->
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-row>
                        <v-col cols="12" md="12">
                            <v-text-field v-model="category.name" outlined :rules="nameRules" label="Name"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="12">
                            <v-textarea v-model="category.description" outlined :rules="descriptionRules"
                                label="Description" required></v-textarea>
                        </v-col>
                        <v-col cols="12" md="12">
                            <v-file-input multiple outlined label="Image" @change="onimagechange"
                                required></v-file-input>
                        </v-col>
                        <v-col cols="12" md="12">
                            <!-- <v-text-field v-model="category.location" outlined label="Location"></v-text-field> -->
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
                            <v-btn color="primary" @click="save" :loading="loading"
                                :disabled="(locationdata == '') ? true : false">Save</v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
        <v-dialog v-model="showmap" persistent max-width="80%">
            <v-card>
                <v-card-title>
                    <span class="headline">Select Location</span> <v-spacer></v-spacer>
                    <v-btn icon @click="showmap = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <!-- <v-text-field v-model="category.location" outlined id="search-js" label="Location"></v-text-field> -->

                    <div class="text-right mt-2">
                        <v-btn color="primary" @click="showmap = false">Confirm Location</v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
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
import axios from 'axios';
import VueEditor from 'vue2-editor';

export default {
    components: {
        VueEditor
    },
    data() {
        return {
            showmap: false,
            loading: false,
            category: {
                name: '',
                description: '',
                image: null,
                parent_id: 0,
                location: ''
            },
            fetchinglocation: false,
            breadcrumbs: [{
                text: 'Categories',
                disabled: false,
                to: '/admin/categories'
            }, {
                text: 'Add New Category'
            }],
            categoryimage: [],
            categories: [],
            valid: true,
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 50) || 'Name must be less than 50 characters'
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
                v => (v && v.length <= 255) || 'Description must be less than 255 characters'
            ],
            imageRules: [
                v => !!v || 'Image is required'
            ],
            locationdata: ''

        }
    },
    mounted() {
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



        this.getlocationdata();



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
        async getlocationdata() {
            // console.log('getlocationdata');
            // this.showmap = true;
            var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

            mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            const map = new mapboxgl.Map({
                container: 'map', // container id
                // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
                style: 'mapbox://styles/amartchsmarters/clwri9yls013g01pn700x455p',
                center: [78.08312224925453, 26.69108073495788], // starting position
                zoom: 4 // starting zoom
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
            // searchBox.value = this.category.location;
            let marker = new mapboxgl.Marker({ color: 'black' })
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

            // this.fetchinglocation = true;
            // if (this.category.location == '') {
            //     this.fetchinglocation = false;
            //     return;
            // }
            // await axios.get('https://api.mapbox.com/search/geocode/v6/forward?q=' + this.category.location + '&access_token=pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A').then(response => {
            //     this.fetchinglocation = false;
            //     this.locationdata = response.data.features[0].geometry.coordinates;
            // });
        },
        save() {
            this.loading = true;
            let formData = new FormData();
            this.categoryimage.forEach((image) => {
                formData.append('image[]', image);
            });
            formData.append('name', this.category.name);
            formData.append('description', this.category.description);
            // formData.append('image', this.category.image);
            formData.append('status', 'Active');
            formData.append('parent_id', 0);
            formData.append('location', this.category.location);
            formData.append('location_data', this.locationdata);
            axios.post('/api/admin/category/add', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.data.success) {
                    this.$toasted.show('Category added successfully', {
                        type: 'success',
                        duration: 2000
                    });
                    this.$router.push('/admin/categories');
                    this.loading = false;
                }
                else {
                    this.$toasted.show('Category not added', {
                        type: 'error',
                        duration: 2000
                    });
                    this.loading = false;
                }
            }).catch(error => {
                console.log(error);
                this.$toasted.show('Category not added', {
                    type: 'error',
                    duration: 2000
                });
                this.loading = false;
            });

        },
        getcategories() {
            axios.get('/api/admin/category/list')
                .then(response => {
                    this.categories.push({
                        value: 0,
                        text: 'No Parent'
                    });
                    response.data.categories.forEach(category => {
                        this.categories.push({ value: category.id, text: category.name });
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}




</script>