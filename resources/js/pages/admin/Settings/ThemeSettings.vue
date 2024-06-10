<template>
    <div class="d-flex flex-column flex-grow-1">
        <v-card>
            <v-card-title>
                <h3 class="headline mb-0">Theme Options</h3>
            </v-card-title>
            <v-card-text>
                <h2>Landing Page Settings</h2>
                <v-form ref="form">
                    <v-expansion-panels accordion class="mt-4">

                        <v-expansion-panel>
                            <v-expansion-panel-header>Banner Image</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-file-input v-model="banner.image" accept="image/*" label="Select Image"
                                    prepend-icon="mdi-camera" @change="onBannerimgchnage"></v-file-input>
                                <!-- <v-checkbox v-model="banner.imageshow" label="Show (Tick to show)"
                                    prepend-icon="mdi-eye" value="true"></v-checkbox> -->
                                <v-img :src="newimage" class="mx-auto" height="300" max-width="500"
                                    style="margin: auto;"></v-img>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Banner Title</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <vue-editor v-model="banner.title" outlined label="Banner Title" persistent-hint
                                    clearable rows="4" cols="40"></vue-editor>
                            </v-expansion-panel-content>
                        </v-expansion-panel>

                        <v-expansion-panel>
                            <v-expansion-panel-header>Banner Button</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="banner.btntext" label="Banner Button Text"></v-text-field>
                                <v-text-field v-model="banner.btnlink" label="Banner Button Link"></v-text-field>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Banner Button1</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="banner.btntext1" label="Banner Button Text"></v-text-field>
                                <v-text-field v-model="banner.btnlink1" label="Banner Button Link"></v-text-field>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Call To action</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <vue-editor v-model="calltotitle" outlined label="Title" persistent-hint clearable
                                    rows="4" cols="40"></vue-editor>
                                <v-text-field v-model="calltobutton" label="Button Text"></v-text-field>
                                <!-- <v-text-field v-model="calltobtnlink1" label="Button Link"></v-text-field> -->
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>About Excerpts</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="abouteTitle" label="Title"></v-text-field>
                                <vue-editor v-model="aboutexcerpts" outlined label="Description" persistent-hint
                                    clearable rows="4" cols="40"></vue-editor>
                                <v-file-input v-model="whyusimage" accept="image/*" label="Select Image"
                                    prepend-icon="mdi-camera" @change="aboutusimagechange"></v-file-input>
                                <v-img :src="newwhyusimage" class="mx-auto" height="300" max-width="500"
                                    style="margin: auto;"></v-img>
                            </v-expansion-panel-content>
                        </v-expansion-panel>

                        <v-expansion-panel>
                            <v-expansion-panel-header>Features</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-row>
                                    <v-col cols="12" md="12" v-for="feature, index in features" :key="index">
                                        <v-autocomplete v-model="feature.icon" :items="icons" item-text="name"
                                            item-value="name" label="Icon"></v-autocomplete>
                                        <v-text-field v-model="feature.title" label="Feature Title"></v-text-field>
                                        <v-text-field v-model="feature.description"
                                            label="Feature Subtitle"></v-text-field>
                                        <v-btn color="error" class="mt-2 mb-2 float-right"
                                            @click="removefeaturesfeature(index)">Remove Feature</v-btn>
                                    </v-col>
                                </v-row>
                                <v-btn color="primary" class="mt-2 mb-2 float-right" @click="addfeaturesfeature">Add
                                    Feature</v-btn>
                            </v-expansion-panel-content>
                        </v-expansion-panel>

                    </v-expansion-panels>
                    <v-btn color="primary" class="mt-2 mb-2 float-right" @click="save">Save</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
import axios from 'axios';
import {
    VueEditor
} from "vue2-editor";
import Icons from '../../../assets/icons.json'
export default {
    components: {
        VueEditor
    },
    data() {
        return {
            icons: Icons,
            banner: {
                image: "/storage/uploads/cricket.png",
                title: '<h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue with<br /><span class="primary--text ">Smarters Booking Management</span></h1>',
                btntext: "Book Slot",
                btntext1: "Contact Us",
                btnlink: "/categories",
                btnlink1: "/contact-us",
            },
            bannerimage: [],
            bannerbackground: [],
            newbackground: '',
            newimage: '',
            calltotitle: '<div><div class="text-h3">Ready to talk?</div> <div class="text-h3 primary--text">Our team is here to help.</div></div>',
            calltobutton: '',
            calltobtnlink1: '',
            abouteTitle: '',
            // partners: [],
            // partnerimages: [],
            aboutexcerpts: '<h1 class="text-h4 text-sm-h3 text-md-h2">Best Cricket Academy</h1>',
            showabout: 'false',
            whyusimage: '',
            newwhyusimage: '/storage/uploads/cricket.png',

            //  showwhyus: 'false',
            features: [{
                icon: 'mdi-account-check-outline',
                title: 'Account Verification',
                description: "We ensure that the that only authorized users can access the website's features and services. This helps to prevent unauthorized access and ensures the security of user data."
            }, {
                icon: 'mdi-lifebuoy',
                title: 'Dedicated Support',
                description: 'We offer users can contact a support team if they encounter any issues while using the website\'s features. This ensures that users have access to timely assistance and can resolve any problems quickly.'
            }, {
                icon: 'mdi-email-open-multiple-outline',
                title: 'Email Integration',
                description: 'It means that users can receive notifications and updates via email. This is a useful feature for users who want to stay informed about their account activity and website updates.'
            }, {
                icon: 'mdi-clock-outline',
                title: 'Save Time',
                description: 'We are offering convenient and efficient services that reduce the time and effort required for completing tasks. This can be a valuable benefit for users that want to streamline their processes and increase productivity.'
            }],
        }
    },
    methods: {
        addfeature() {
            this.whyusfeatures.push({
                title: '',
                description: ''
            });
        },
        removefeature(index) {
            this.whyusfeatures.splice(index, 1);
        },
        removefeaturesfeature(index) {
            this.features.splice(index, 1);
        },
        addfeaturesfeature() {
            this.features.push({
                title: '',
                description: ''
            });
        },
        onBannerbgChange(e) {
            const files = e;
            this.bannerbackground = files;
            this.newbackground = URL.createObjectURL(files);
        },
        onBannerimgchnage(e) {
            const files = e;
            this.bannerimage = files;
            this.newimage = URL.createObjectURL(files);
        },
        aboutusimagechange(e) {
            const files = e;
            this.whyusimage = files;
            this.newwhyusimage = URL.createObjectURL(files);
        },
        save() {
            const self = this;
            const formData = new FormData();
            formData.append('bannerimage', this.bannerimage);
            formData.append('calltotitle', this.calltotitle);
            formData.append('bannertitle', this.banner.title);
            formData.append('bannerbtntext', this.banner.btntext);
            formData.append('bannerbtntext1', this.banner.btntext1);
            formData.append('bannerbtnlink', this.banner.btnlink);
            formData.append('bannerbtnlink1', this.banner.btnlink1);
           // formData.append('bannerimageshow', (this.banner.imageshow != 'undefined') ? this.banner.imageshow : 'false');
            formData.append('calltobutton', this.calltobutton);
            formData.append('calltobtnlink1', this.calltobtnlink1);
            formData.append('abouteTitle', this.abouteTitle);
            formData.append('whyusimage', this.whyusimage);
            formData.append('aboutexcerpts', this.aboutexcerpts);
            formData.append('features', JSON.stringify(this.features));
            //formData.append('action', 'savesettings');
            axios.post('/api/admin/themesetting', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                    self.$toasted.show(response.data.message, {
                        theme: "bubble",
                        position: "top-right",
                        duration: 10000,
                        type: response.data.success ? 'success' : 'error',
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    });
                })
                .catch(function (error) {
                    self.$toasted.show(response.data.message, {
                        theme: "bubble",
                        position: "top-right",
                        duration: 10000,
                        type: 'error',
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    });
                });
        },
        getthemeoptions() {
            var self = this;
            axios.get('/api/getpageoption')
                .then(function (response) {
                    console.log(response,"page data");
                    self.banner.title = response.data.options.bannertitle;
                    self.banner.btntext = response.data.options.bannerbtntext;
                    self.banner.btntext1 = response.data.options.bannerbtntext1;
                    self.banner.btnlink = response.data.options.bannerbtnlink;
                    self.banner.btnlink1 = response.data.options.bannerbtnlink1;
                   // self.banner.imageshow = response.data.options.bannerimageshow;
                    self.newimage = response.data.options.bannerimage;
                    self.calltotitle = response.data.options.calltotitle;
                    self.calltobutton = response.data.options.calltobutton;
                    self.abouteTitle = response.data.options.abouteTitle;
                    self.aboutexcerpts = response.data.options.aboutexcerpts;
                    self.newwhyusimage = response.data.options.whyusimage;
                    self.features = JSON.parse(response.data.options.features);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.getthemeoptions();
    }
}
</script>