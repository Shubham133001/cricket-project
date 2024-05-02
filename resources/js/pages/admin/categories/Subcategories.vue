<template>
    <div style="width: 100%">
         <div>
        <div class="display-1">Sub Category</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
        <v-card>
            <v-card-title>
                <v-btn color="black" small icon fab dense @click="back"><v-icon small>mdi-arrow-left</v-icon></v-btn>
                <span class="headline">Sub Categories</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" :to="'/admin/category/addsubcategory/' + this.$route.params.id">Add New
                    Sub-Category</v-btn>
            </v-card-title>
            <v-card-text>
                <v-row>
                </v-row>

                <v-simple-table :headers="headers" :items="categories" item-key="name" class="elevation-0">
                    <thead style="background: #ececec">
                        <tr>
                            <th v-for="header in headers" :key="header.text" :class="`text-${header.align || 'start'}`">
                                {{ header.text }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in categories" :key="item.id">
                            <td>
                                {{ item.id }}
                            </td>
                            <td>
                                <div style="max-width: 350px" min-width="150px" width="auto" class="mt-1 mb-1"
                                    @click="opencategory(item)">
                                    <v-img height="200px" width="350px"
                                        gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                        src="https://picsum.photos/id/11/100/60" v-if="item.image == null">
                                    </v-img>
                                    <v-carousel height="200" hide-delimiters cycle show-arrows-on-hover v-else>

                                        <v-carousel-item v-for="(image, i) in item.image" :key="i"
                                            class="white--text align-center mt-0">

                                            <v-img :src="'/storage/images/' + image" height="200px" min-width="150px"
                                                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                lazy-src="https://picsum.photos/id/11/100/60">
                                            </v-img>
                                        </v-carousel-item>
                                        <h3 class="text-h5 pl-1 pb-1"
                                            style="position: absolute; bottom: 0px; color: #fff;">{{
                    item.name }}</h3>
                                    </v-carousel>
                                </div>
                            </td>
                            <td>{{ item.description }}</td>
                            <!-- <td><v-btn small @click="addslot(item)" color="primary"
                                    v-if="item.type == 'slot' || item.type == null"><v-icon small
                                        class="mr-1">mdi-calendar</v-icon>
                                    Add
                                    Slot</v-btn>
                                <v-btn small @click="addcategory(item)" color="info"
                                    v-if="(item.type == 'category' || item.type == null) && !item.is_child"><v-icon
                                        small class="mr-1">mdi-folder-multiple-outline</v-icon>
                                    Add
                                    Sub Category</v-btn>

                            </td> -->
                            <td>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small fab icon color="primary" @click="editsub(item)" v-bind="attrs"
                                            v-on="on"><v-icon small>mdi-pencil</v-icon></v-btn>
                                    </template>
                                    <span>Edit</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small fab icon color="red" @click="deleteCategory(item)" v-bind="attrs"
                                            v-on="on"><v-icon small>mdi-delete</v-icon></v-btn>
                                    </template>
                                    <span>Delete</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small fab icon color="info" @click="viewcategory(item)" v-bind="attrs"
                                            v-on="on"><v-icon small>mdi-format-list-bulleted-square</v-icon></v-btn>
                                    </template>
                                    <span>Sub Categories</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small icon fab color="green" @click="addslot(item)" v-bind="attrs"
                                            v-on="on"><v-icon small>mdi-plus</v-icon></v-btn>
                                    </template>
                                    <span>Add Slots</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn small icon fab color="info" @click="viewslot(item)" v-bind="attrs"
                                            v-on="on"><v-icon small>mdi-eye</v-icon></v-btn>
                                    </template>
                                    <span>View Slots</span>
                                </v-tooltip>

                            </td>

                        </tr>

                    </tbody>
                </v-simple-table>

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
            categories: [],
            breadcrumbs: [{
                text: 'Categories',
                disabled: false,
                to: '/admin/categories'
            }, {
                text: 'Sub Categories'
            }],
            show: false,
            headers: [
                { text: 'ID', align: 'start', value: 'id' },
                { text: 'Name', align: 'start', value: 'name' },
                { text: 'Description', value: 'description' },
                // { text: 'Options', value: 'options', sortable: false },
                { text: 'Actions', value: 'actions', sortable: false }
            ],

        }
    },
    methods: {
        opencategory(category) {
            // check if category have children
            if (category.children.length > 0) {
                this.$router.push(`/admin/category/subcategory/${category.id}`);
            } else {
                this.$router.push(`/admin/category/slots/${category.id}`);
            }
        },
        deleteCategory(category) {
            axios.post('/api/admin/category/delete/', {
                id: category.id
            })
                .then(response => {
                    if (response.data.success) {
                        this.getcategories();
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getcategories() {
            axios.post('/api/admin/category/getsubcategories', { id: this.$route.params.id })
                .then(response => {
                    let categories = response.data.categories;
                    let newcatgories = [];
                    categories.forEach(category => {
                        category.is_child = false;
                        category.show = false;
                        category.image = (category.image != null) ? category.image.split(',') : '';
                        newcatgories.push(category);
                        // if (category.children.length > 0) {
                        //     category.children.forEach(child => {
                        //         child.is_child = true;
                        //         newcatgories.push(child);
                        //     });
                        // }
                    });
                    this.categories = newcatgories;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        editsub(category) {
            this.$router.push(`/admin/subcategory/edit/${category.id}`);
        },
        addslot(item) {
            axios.post('/api/admin/category/changetype', {
                type: 'slot',
                id: item.id
            })
                .then(response => {
                    this.$router.push(`/admin/slot/add/` + item.id);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        viewslot(item) {
            this.$router.push(`/admin/category/slots/${item.id}`);
        },
        addcategory(item) {
            axios.post('/api/admin/category/changetype', {
                type: 'category',
                id: item.id
            })
                .then(response => {
                    this.$router.push(`/admin/category/addsubcategory/` + item.id);
                })
                .catch(error => {
                    console.log(error);
                });

        },
        viewcategory(category) {
            this.$router.push(`/admin/subcategory/${category.id}`);
            this.getcategories();
        },
        back() {
            // history.back(-1);
            window.history.go(-1);
        }
    },
    mounted() {
        this.getcategories();
    },
    watch: {
        $route(to, from) {
            this.getcategories();
        }
    }
}




</script>