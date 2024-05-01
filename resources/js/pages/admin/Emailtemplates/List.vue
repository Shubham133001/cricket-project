<template>
    <div style="width: 100%">

        <v-card>
            <v-card-title>
                <span class="headline">Email Templates</span>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="newItem">New Template</v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table :headers="headers" :items="templates" sort-by="id" sort-desc="true" :search="search"
                    :loading="loading" class="elevation-1">
                    <template v-slot:item.id="{ item }">
                        <span style="font-weight: bold; cursor: pointer" @click="editItem(item)">{{ item.id }}</span>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon color="primary" class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
                        <v-icon color="error" @click="deleteItem(item)">mdi-delete</v-icon>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
        <confirm ref="confirm"></confirm>
    </div>
</template>

<script>
import axios from 'axios';
import confirm from '@/components/common/Confirm.vue'

export default {
    components: {
        confirm
    },
    data() {
        return {
            dialog: false,
            dialogDelete: false,

            options: {},
            loading: false,

            search: '',
            totalTemplates: 0,
            templates: [],
            template: {},
            headers: [{
                text: 'ID',
                value: 'id'
            },
            {
                text: 'Name',
                value: 'name'
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            }
            ],
        }
    },
    created() {
        this.getTemplates();
    },
    methods: {
        getTemplates() {
            this.loading = true;
            axios.get('/api/admin/gettemplates')
                .then(response => {
                    this.templates = response.data.templates;
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                })
        },
    }
}
</script>