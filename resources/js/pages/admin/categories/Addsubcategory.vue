<template>
    <div style="width: 100%">
         <div>
        <div class="display-1">Add New Sub-Category</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card>
            <!-- <v-card-title>
                <span class="headline">Add New Sub-Category</span>
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
                            <v-text-field v-model="category.location" outlined label="Location"
                                @blur="getlocationdata"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="$router.push('/admin/categories')">Cancel</v-btn>
                            <v-btn color="primary" @click="save" :loading="loading"
                                :diabled="fetchinglocation">Save</v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
import axios from 'axios';
import VueEditor from 'vue2-editor';

export default {
    components: {
        VueEditor,
    },
    data() {
        return {
            category: {
                name: '',
                description: '',
                image: null
            },
            valid: true,
            breadcrumbs: [{
                text: 'Categories',
                disabled: false,
                to: '/admin/categories'
            }, {
                text: 'Add New Sub Categories'
            }],
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
            categoryid: this.$route.params.id,
            categoryimage: [],
            locationdata: [],
            fetchinglocation: false,
            loading: false,
        }
    },
    methods: {

        async getlocationdata() {
            this.fetchinglocation = true;
            if (this.category.location == '') {
                this.fetchinglocation = false;
                return;
            }
            await axios.get('https://api.mapbox.com/search/geocode/v6/forward?q=' + this.category.location + '&access_token=pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A').then(response => {
                this.fetchinglocation = false;
                this.locationdata = response.data.features[0].geometry.coordinates;
            });
        },
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
            this.categoryimage.forEach((image) => {
                formData.append('image[]', image);
            });
            formData.append('name', this.category.name);
            formData.append('description', this.category.description);
            // formData.append('image', this.category.image);
            formData.append('status', 'Active');
            formData.append('parent_id', this.categoryid);
            formData.append('location', this.category.location);
            formData.append('location_data', this.locationdata);
            await axios.post('/api/admin/category/add', formData).then(response => {
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
            });
        }
    }
}




</script>