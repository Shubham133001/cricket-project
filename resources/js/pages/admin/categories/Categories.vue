<template>
    <div style="width: 100%">

        <v-card>
            <v-card-title>
                <span class="headline">Categories</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" to="/admin/category/add">Add New Category</v-btn>
            </v-card-title>
            <v-card-text>
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
                                <div class="mt-1 mb-1 categ_image" @click="opencategory(item)">
                                    <v-img :src="'/storage/images/' + image" height="200px" width="350px"
                                        gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                        lazy-src="https://picsum.photos/id/11/350/200"
                                        v-if="item.image == null || item.image == ''">
                                    </v-img>
                                    <v-carousel hide-delimiters cycle show-arrows-on-hover v-else>

                                        <v-carousel-item v-for="(image, i) in item.image" :key="i"
                                            class="white--text align-center mt-0">

                                            <v-img :src="'/storage/images/' + image" height="200px"
                                                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                lazy-src="https://picsum.photos/id/11/350/200">
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
                                        <v-btn small fab icon color="primary" @click="edit(item)" v-bind="attrs"
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
        opencategory(category) {
            // check if category have children
            if (category.children.length > 0) {
                this.$router.push(`/admin/subcategory/${category.id}`);
            } else {
                this.$router.push(`/admin/category/slots/${category.id}`);
            }
        },
        getcategories() {
            axios.get('/api/admin/category/list')
                .then(response => {
                    let categories = response.data.categories;
                    let newcatgories = [];
                    categories.forEach(category => {
                        category.is_child = false;
                        category.show = false;
                        category.image = (category.image != null) ? category.image.split(',') : null;
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
        viewcategory(category) {
            this.$router.push(`/admin/subcategory/${category.id}`);
        },
        edit(category) {
            this.$router.push(`/admin/category/edit/${category.id}`);
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
    },
    mounted() {
        this.getcategories();
    }
}
</script>
<style scoped>
.categ_image {
    max-width: 350px;
}

.v-carousel {
    height: 200px !important;
    border-radius: 5px;
    border: solid 2px #ececec;
}

@media screen and (max-width: 600px) {
    .categ_image {
        max-width: 150px !important;
    }

    .v-carousel {
        height: 100px !important;
    }

    .categ_image .v-img {
        max-height: 100px !important;
    }
}
</style>
