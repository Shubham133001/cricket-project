<template>
    <div style="width: 100%">

        <v-card>
            <v-card-title>
                <span class="headline">Edit Sub Category</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-row>
                        <v-col cols="12" md="6">
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
            categoryimage: [],
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
            locationdata: [],
            fetchinglocation: false,
            loading: false,
        }
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
            formData.append('parent_id', this.category.parent_id);
            formData.append('id', this.category.id);
            formData.append('location', this.category.location);
            formData.append('location_data', this.locationdata);
            await axios.post('/api/admin/category/update', formData).then(response => {
                if (response.data.success) {
                    this.$toasted.show('Category Updated successfully', {
                        type: 'success',
                        duration: 2000
                    });
                    this.$router.push('/admin/categories');
                    this.loading = false;
                }
                else {
                    this.$toasted.show('Category not Updated', {
                        type: 'error',
                        duration: 2000
                    });
                    this.loading = false;
                }
            });
        },
        async getcategory(id) {
            await axios.get('/api/admin/category/edit/' + id)
                .then(response => {
                    this.category = response.data.category;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        if (this.$route.params.id) {
            this.getcategory(this.$route.params.id);
        }
    }
}




</script>