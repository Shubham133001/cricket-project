<template>
    <div style="width: 100%">

        <v-card>
            <v-card-title>
                <span class="headline">Add Email Templates</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="template.name" outlined :rules="nameRules" label="Name"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="template.subject" outlined :rules="subjectRules" label="Subject"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="12">
                            <VueEditor v-model="template.body" :rules="bodyRules" label="Body" required />
                        </v-col>
                        <v-col cols="12" md="12">
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="$router.push('/admin/emailtemplates')">Cancel</v-btn>
                            <v-btn color="primary" @click="save">Save</v-btn>
                        </v-col>
                    </v-row>
                </v-form>
                <v-row>
                    <v-col cols="12" md="6">
                        <v-simple-table>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Available Variables</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{patient.name}" />
                                    </td>
                                    <th>
                                        Patient Name
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{patient_email}" />
                                    </td>
                                    <th>
                                        Patient Email
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{patient_phone}" />
                                    </td>
                                    <th>
                                        Patient Phone Number
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{patient_address}" />
                                    </td>
                                    <th>
                                        Patient Address
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{patient_age}" />
                                    </td>
                                    <th>
                                        Patient Age
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{appointment.date}" />
                                    </td>
                                    <th>
                                        Patient Appointment Date
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{appointment.time}" />
                                    </td>
                                    <th>
                                        Patient Appointment Time
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{appointment.doctor.name}" />
                                    </td>
                                    <th>
                                        Doctor Name
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <copy-label text="{appointment.status}" />
                                    </td>
                                    <th>
                                        Appointment Status
                                    </th>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>

            </v-card-actions>
        </v-card>

    </div>

</template>

<script>
import axios from 'axios';
import {
    VueEditor
} from "vue2-editor";
import CopyLabel from "@/components/common/CopyLabel";
export default {
    components: {
        VueEditor,
        CopyLabel
    },
    data() {
        return {
            valid: true,
            template: {},
            nameRules: [
                v => !!v || 'Name is required',
            ],
            subjectRules: [
                v => !!v || 'Subject is required',
            ],
            bodyRules: [
                v => !!v || 'Body is required',
            ],
        }
    },
    methods: {
        save() {
            this.$refs.form.validate();
            if (this.valid) {
                axios.post('/api/admin/addtemplate', this.template).then(response => {
                    this.$router.push('/admin/emailtemplates');
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
}
</script>