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
                            <v-expansion-panel-header>Hero Background</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-file-input v-model="banner.background" accept="image/*" label="Select Image"
                                    prepend-icon="mdi-camera" @change="onBannerbgChange"></v-file-input>
                                <v-checkbox v-model="banner.backgroundshow" label="Show (Tick to show)"
                                    prepend-icon="mdi-eye" value="true"></v-checkbox>
                                <v-img :src="newbackground" class="mx-auto" height="300" max-width="500" style="margin: auto;"></v-img>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Hero Image</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-file-input v-model="banner.image" accept="image/*" label="Select Image"
                                    prepend-icon="mdi-camera" @change="onBannerimgchnage"></v-file-input>
                                <v-checkbox v-model="banner.imageshow" label="Show (Tick to show)"
                                    prepend-icon="mdi-eye" value="true"></v-checkbox>
                                <v-img :src="newimage" class="mx-auto" height="300" max-width="500"
                                    style="margin: auto;"></v-img>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Hero Title</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <vue-editor v-model="banner.title" outlined label="Banner Title" persistent-hint
                                    clearable rows="4" cols="40"></vue-editor>
                                <v-row>
                                    <v-col cols="12" md="4">
                                        <p>Banner Title Color</p>
                                        <v-color-picker v-model="banner.titlecolor"
                                            label="Banner Title Color"></v-color-picker>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <p>Banner Title Font Size</p>
                                        <v-text-field v-model="banner.titlesize"
                                            label="Banner Title Font Size"></v-text-field>
                                    </v-col>
                                </v-row>

                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Hero Subtitle</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <vue-editor v-model="banner.subtitle" outlined label="Banner Title" persistent-hint
                                    clearable rows="4" cols="40"></vue-editor>
                                <v-row>
                                    <v-col cols="12" md="4">
                                        <p>Banner Subtitle Color</p>
                                        <v-color-picker v-model="banner.subtitlecolor"
                                            label="Banner Subtitle Color"></v-color-picker>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <p>Banner Subtitle Font Size</p>
                                        <v-text-field v-model="banner.subtitlesize"
                                            label="Banner Subtitle Font Size"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Hero Button</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="banner.btntext" label="Banner Button Text"></v-text-field>
                                <v-text-field v-model="banner.btnlink" label="Banner Button Link"></v-text-field>
                                <p>Banner Button Background Color</p>
                                <v-color-picker v-model="banner.btncolor" label="Banner Button Color"></v-color-picker>
                                <p>Banner Button Text Color</p>
                                <v-color-picker v-model="banner.btntextcolor"
                                    label="Banner Button Text Color"></v-color-picker>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Hero Button1</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="banner.btntext1" label="Banner Button Text"></v-text-field>
                                <v-text-field v-model="banner.btnlink1" label="Banner Button Link"></v-text-field>
                                <p>Banner Button Background Color</p>
                                <v-color-picker v-model="banner.btncolor1" label="Banner Button Color"></v-color-picker>
                                <p>Banner Button Text Color</p>
                                <v-color-picker v-model="banner.btntextcolor1" label="Banner Button Text Color"></v-color-picker>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>About Excerpts</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <vue-editor v-model="aboutexcerpts" outlined label="About Excerpts" persistent-hint
                                    clearable rows="4" cols="40"></vue-editor>
                                <v-checkbox v-model="showabout" label="Show (Tick to show)" prepend-icon="mdi-eye"
                                    value="true"></v-checkbox>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Why us</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <p>Why us Image</p>
                                <v-file-input accept="image/*" label="Select Image" prepend-icon="mdi-camera"
                                    @change="onwhyusimagechange"></v-file-input>
                                <v-img :src="newwhyusimage" class="mx-auto" height="500" max-width="440"
                                    style="margin: auto;"></v-img>
                                <v-checkbox v-model="showwhyus" label="Show (Tick to show)" prepend-icon="mdi-eye"
                                    value="true"></v-checkbox>
                                <v-row>
                                    <v-col cols="12" md="12" v-for="feature, index in whyusfeatures" :key="index">
                                        <v-text-field v-model="feature.title" label="Feature Title"></v-text-field>
                                        <v-text-field v-model="feature.description"
                                            label="Feature Subtitle"></v-text-field>
                                        <v-btn color="error" class="mt-2 mb-2 float-right"
                                            @click="removefeature(index)">Remove Feature</v-btn>
                                    </v-col>
                                    <v-btn color="primary" class="mt-2 mb-2 float-right" @click="addfeature">Add
                                        Feature</v-btn>
                                </v-row>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>Features</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <!-- <v-text-field v-model="featureheading" label="Feature Heading"></v-text-field> -->
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
                        <v-expansion-panel>
                            <v-expansion-panel-header>Faqs</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-text-field v-model="faqtitle" label="Faq Title"></v-text-field>
                                <v-row>
                                    <v-col cols="12" md="12" v-for="faq, index in faqs" :key="index">
                                        <v-text-field v-model="faq.title" label="Faq Title"></v-text-field>
                                        <v-text-field v-model="faq.description" label="Faq Description"></v-text-field>
                                        <v-btn color="error" class="mt-2 mb-2 float-right"
                                            @click="removefaq(index)">Remove Faq</v-btn>
                                    </v-col>
                                </v-row>
                                <v-btn color="primary" class="mt-2 mb-2 float-right" @click="addfaq">Add Faq</v-btn>
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
                image: "",
                background: "",
                title: "How can we help you today?",
                subtitle: "We bring the best services to you! Discover the Ultimate Solution for Your Service Needs",
                btntext: "Get Started",
                btntext1: "Get Started",
                btnlink: "/categories",
                btnlink1: "/contact-us",
                backgroundshow: 'true',
                imageshow: 'true',
                titlecolor: "#000000",
                subtitlecolor: "#000000",
                btncolor: "var(--v-primary-base)",
                btncolor1: "var(--v-secondary-lighten4)",
                btntextcolor: "#ffffff",
                btntextcolor1: "#ffffff",
                titlesize: "96px",
                subtitlesize: "24px",
            },
            // valid: true,
            bannerimage: [],
            bannerbackground: [],
            newbackground: '',
            newimage: '',
            partners: [],
            partnerimages: [],
            aboutexcerpts: '<h1 class="text-h4 text-sm-h3 text-md-h2">Best Cricket Academy?</h1>',
            showabout: 'false',
            whyusimage: '',
            newwhyusimage: '/images/cricket.png',
            whyusfeatures: [{
                title: 'Secure your data',
                description: 'Protect your data from being stolen. When you use a public WiFi hotspot, your entire browsing activity while connected to that hotspot could be monitored and spied on. It is very much possible that the next person sitting beside you can read your communication easily. Do you know, how easy it is for bad people to create devil twin WiFi Hotspots. Devil twin is like a mirror copy of the original WiFi, which confuses you, but in real it is a trap.'
            },
            {
                title: 'Protect your privacy',
                description: 'Change your IP address and remain anonymous while browsing the Internet. Many countries want to control what their citizens can read, share, and discuss. Because a VPN encrypts your communication to its server, it doesn\'t matter who\'s on the public network or trying to eavesdrop: all they see is gibberish. It\'s almost as if you\'re using an open network in a completely different location!'
            },
            {
                title: 'Unblock websites',
                description: 'Bypass censorship and unblock websites. In some countries, governments block websites that they don\'t want their citizens to read. When you connect to a VPN server, you effectively get the IP address of one of their servers in whatever region that server may be — hiding your IP address behind it in the process. Anyone who come snooping around on your activities will only be able to find the IP address of your VPN provider. Not yours.'
            },
            ],
            showwhyus: 'false',
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
            faqtitle: 'Frequently Asked Questions',
            faqs: [{
                title: 'What is a VPN?',
                description: 'A VPN, or Virtual Private Network, is a private network that encrypts and transmits data while it travels from one place to another on the internet. Using a VPN to connect to the internet allows you to surf websites privately and securely as well as gain access to restricted websites and overcome censorship blocks. VPNs aren\'t just for desktops or laptops -- you can set up a VPN on your iPhone, iPad or Android phone, too.'
            }, {
                title: 'Why do I need a VPN?',
                description: 'A VPN provides an extra layer of security for your internet connection. Without a VPN, your internet service provider (ISP) can see your browsing history. With an encrypted VPN connection, your data is protected and your browsing history is hidden. Learn More'
            }, {
                title: 'What does a VPN hide?',
                description: 'A VPN hides your IP address by encrypting your data and routing it through remote servers, keeping your activity, your identity and your location private even if you don’t have any level of technical experience.'
            }, {
                title: 'Is a VPN safe?',
                description: 'VPNs are safe to use and are recommended as a means of protecting your privacy online. However, as with any other technology, it is important to use a reputable service provider. A VPN is only as safe as the company that is offering it.'
            }, {
                title: 'Can I use a VPN to watch Netflix?',
                description: 'Yes, you can use a VPN to watch Netflix. However, not all VPNs will work with Netflix. Some VPNs will not work with Netflix because they have been blocked by Netflix. Other VPNs will work with Netflix, but they may not be able to unblock all of the content that you want to watch.'
            }],


        }
    },
    methods: {
        //
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
        addfaq() {
            this.faqs.push({
                title: '',
                description: ''
            });
        },
        removefaq(index) {
            this.faqs.splice(index, 1);
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

        onwhyusimagechange(e) {
            const files = e;
            this.whyusimage = files;
            this.newwhyusimage = URL.createObjectURL(files);
        },
        save() {
            //alert('sfsfsff');
            const self = this;
            const formData = new FormData();
            formData.append('bannerimage', this.bannerimage);
            formData.append('bannerbackground', this.bannerbackground);
            formData.append('bannertitle', this.banner.title);
            formData.append('bannersubtitle', this.banner.subtitle);
            formData.append('bannerbtntext', this.banner.btntext);
            formData.append('bannerbtntext1', this.banner.btntext1);
            formData.append('bannerbtnlink', this.banner.btnlink);
            formData.append('bannerbtnlink1', this.banner.btnlink1);
            formData.append('bannerbackgroundshow', (this.banner.backgroundshow != 'undefined') ? this.banner.backgroundshow : 'false');
            formData.append('bannerimageshow', (this.banner.imageshow != 'undefined') ? this.banner.imageshow : 'false');
            formData.append('bannertitlecolor', this.banner.titlecolor);
            formData.append('bannersubtitlecolor', this.banner.subtitlecolor);
            formData.append('bannerbtncolor', this.banner.btncolor);
            formData.append('bannerbtncolor1', this.banner.btncolor1);
            formData.append('bannerbtntextcolor', this.banner.btntextcolor);
            formData.append('bannerbtntextcolor1', this.banner.btntextcolor1);
            formData.append('showabout', this.showabout);
            formData.append('whyusimage', this.whyusimage);
            formData.append('aboutexcerpts', this.aboutexcerpts);
            formData.append('showwhyus', (this.showwhyus != 'undefined') ? this.showwhyus : 'false');
            formData.append('whyusfeatures', JSON.stringify(this.whyusfeatures));
            formData.append('features', JSON.stringify(this.features));
            formData.append('faqtitle', this.faqtitle);
            formData.append('faqs', JSON.stringify(this.faqs));
            formData.append('titlesize', this.banner.titlesize);
            formData.append('subtitlesize', this.banner.subtitlesize);
            formData.append('action', 'savesettings');

            axios.post('/api/admin/themesetting', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(function (response) {
                    console.log(response, "pagePtion")
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
                   // self.banner.image = response.data.options.bannerimage;
                    self.banner.background = response.data.options.bannerbackground;
                    self.banner.title = response.data.options.bannertitle;
                    self.banner.subtitle = response.data.options.bannersubtitle;
                    self.banner.btntext = response.data.options.bannerbtntext;
                    self.banner.btntext1 = response.data.options.bannerbtntext1;
                    self.banner.btnlink = response.data.options.bannerbtnlink;
                    self.banner.btnlink1 = response.data.options.bannerbtnlink1;
                    self.banner.backgroundshow = response.data.options.bannerbackgroundshow;
                    self.banner.imageshow = response.data.options.bannerimageshow;
                    self.banner.titlecolor = response.data.options.bannertitlecolor;
                    self.banner.subtitlecolor = response.data.options.bannersubtitlecolor;
                    self.banner.btncolor = response.data.options.bannerbtncolor;
                    self.banner.btncolor1 = response.data.options.bannerbtncolor1;
                    self.banner.btntextcolor = response.data.options.bannerbtntextcolor;
                    self.banner.btntextcolor1 = response.data.options.bannerbtntextcolor1;
                    self.newbackground = response.data.options.bannerbackground;
                    self.newimage = response.data.options.bannerimage;
                    // self.partnerimages = JSON.parse(response.data.options.partners);
                    //self.showpartners = response.data.options.showpartners;
                    self.showabout = response.data.options.showabout;
                    self.aboutexcerpts = response.data.options.aboutexcerpts;
                    self.newwhyusimage = response.data.options.whyusimage;
                    // self.whyusimage = response.data.options.whyusimage;
                    self.whyusfeatures = JSON.parse(response.data.options.whyusfeatures);
                    self.showwhyus = response.data.options.showwhyus;
                    // self.featureheading = response.data.options.featureheading;
                    self.features = JSON.parse(response.data.options.features);
                    self.faqtitle = response.data.options.faqtitle;
                    self.faqs = JSON.parse(response.data.options.faqs);
                    self.banner.titlesize = response.data.options.titlesize;
                    self.banner.subtitlesize = response.data.options.subtitlesize;
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