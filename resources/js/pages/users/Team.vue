<template>
    <div>
        <v-container>
            <v-card rounded :loading="fetching">

                <v-col cols="12" class="text-center">
                    <h2 class="text-h3">My Team</h2>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="2" xs="12">
                            <v-hover v-slot:default="{ hover }">
                                <v-avatar :size="150" :color="hover ? 'primary' : ''" color="#cccccc">
                                    <v-img :lazy-src="temimagecurrent" :src="'/storage/' + team.image"
                                        v-if="team.image != '' && team.image != null" class="align-center" />
                                    <span class="headline text-h1" v-else>{{ team.name.charAt(0) }}</span>
                                    <v-fade-transition>
                                        <v-overlay v-if="hover" absolute color="#036358">
                                            <v-btn icon fab small
                                                @click="handleFileImport"><v-icon>mdi-pencil</v-icon></v-btn>
                                        </v-overlay>
                                    </v-fade-transition>
                                </v-avatar>

                            </v-hover>
                        </v-col>
                        <v-col cols="8">
                            <v-row>
                                <v-col cols="12">
                                    <h3 class="text-h4">{{ team.name }}</h3>
                                    <v-chip color="orange" v-if="team.designation != ''" dark small
                                        style="font-family:'Pacifico';">{{
                                        team.designation }}</v-chip><br />
                                    <!-- <v-btn color="primary" v-if="team.id != 0" class="mt-1">View As User</v-btn> -->
                                </v-col>
                                <input type="file" ref="uploader" style="display:none" @change="onfilechange" />
                            </v-row>
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="team.name" outlined label="Name"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select v-model="team.designation" :items="designations" outlined
                                label="Skill Lavel"></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="team.experience" outlined placeholder="For Eg. 1 Year"
                                label="Experience (How old is your team?)"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="12">
                            <v-textarea v-model="team.description" outlined label="Description"></v-textarea>
                        </v-col>
                        <v-col cols="12" md="12">
                            <v-btn color="primary" v-if="team.id == 0" @click="addteam">Save</v-btn>
                            <v-btn color="primary" v-else @click="updateteam" :loading="fetching">Save</v-btn>
                        </v-col>
                    </v-row>
                </v-col>

            </v-card>
        </v-container>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            team: {
                id: 0,
                name: '',
                designation: '',
                experience: '',
                description: '',
                image: ''
            },
            temimagecurrent: '',
            designations: [
                'Beginner', 'Intermediate', 'Expert'
            ],
            fetching: true
        }
    },
    methods: {
        getuserteam() {
            axios.get('/api/user/getuserteam').then(response => {
                if (response.data.team == null) {
                    this.team = {
                        id: 0,
                        name: '',
                        designation: '',
                        experience: '',
                        description: '',
                        image: ''
                    }
                } else {
                    this.team = response.data.team;
                }

                this.temimagecurrent = this.team.image;
                this.fetching = false;
            }).catch(error => {
                console.log(error);
                this.fetching = false;
            })
        },
        onfilechange(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(file);
            const files = e.target.files[0];
            this.team.image = files;
            reader.onload = (e) => {
                this.temimagecurrent = e.target.result;
            }
        },
        handleFileImport() {
            this.$refs.uploader.click();
        },
        addteam() {
            this.fetching = true;
            let formdata = new FormData();
            formdata.append('name', this.team.name);
            formdata.append('designation', this.team.designation);
            formdata.append('experience', this.team.experience);
            formdata.append('description', this.team.description);
            formdata.append('image', this.team.image);

            axios.post('/api/user/addteam', formdata).then(() => {
                this.$toasted.show('Team added successfully', {
                    type: 'success'
                }).goAway(2000);
                this.getuserteam();
            })
        },
        updateteam() {
            this.fetching = true;
            let formdata = new FormData();
            formdata.append('name', this.team.name);
            formdata.append('designation', this.team.designation);
            formdata.append('experience', this.team.experience);
            formdata.append('description', this.team.description);
            formdata.append('image', this.team.image);
            formdata.append('id', this.team.id);

            axios.post('/api/user/updateteam', formdata).then(() => {
                this.$toasted.show('Team updated successfully', {
                    type: 'success'
                }).goAway(2000);
                this.getuserteam();
            })
        }
    },
    mounted() {
        this.getuserteam();
    }
}
</script>