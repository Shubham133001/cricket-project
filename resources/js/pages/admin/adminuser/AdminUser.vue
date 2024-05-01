<template>
  <div class="d-flex flex-column flex-grow-1">

    <v-card>
      <v-card-title>
        Admin List
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-btn elevation="2" v-if="isvisiable" large color="primary" to="/admin/adminuser/add">Add New</v-btn>
      </v-card-title>
      <v-data-table :headers="headers" :items="desserts" :options.sync="options" :server-items-length="totalDesserts"
        :search="search" :loading="loading" class="elevation-1">
        <template v-slot:item.created_at="{ item }">
          <div>
            {{ item.created_at }}
          </div>
        </template>
        <template v-slot:item.id="{ item }">
          <td>
            <div style="cursor: pointer" @click="editAdmin(item)" v-if="item.id != 1">
              <b class="clickablewithborder"># {{ item.id }}</b>
            </div>
            <div v-if="item.id == 1">
              <b class="clickablewithborder"># {{ item.id }}</b>
            </div>
          </td>
        </template>
        <template v-slot:item.status="{ item }">
          <div class="d-flex align-center font-weight-bold">
            <div v-if="item.status == '0'" class="grey--text">
              <span>Inactive</span>
            </div>
            <div v-if="item.status == '1'" class="success--text">
              <span>Active</span>
            </div>
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <div class="actions" v-if="item.id != 1">
            <v-btn class="pa-0" small fab icon color="primary" @click="editAdmin(item)">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>

            <v-btn class="pa-0" small color="error" fab icon @click="deleteAdmin(item.id)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </div>
        </template>
      </v-data-table>

    </v-card>
    <confirm ref="confirm"></confirm>
  </div>

</template>

<script>
import confirm from "@/components/common/Confirm.vue";
export default {
  components: {
    confirm,
  },
  data() {
    return {
      isvisiable: true,
      totalDesserts: 0,
      search: '',
      desserts: [],
      loading: true,
      options: {},
      headers: [{
        text: 'ID',
        align: 'start',
        value: 'id',
      },
      {
        text: 'Name',
        sortable: false,
        value: 'name',
      },
      {
        text: 'Email',
        sortable: false,
        value: 'email',
      },
      {
        text: 'Group',
        sortable: false,
        value: 'admingroup.name',
      },
      {
        text: 'Created',
        sortable: false,
        value: 'created_at',
      },
      {
        text: "Status",
        sortable: false,
        value: "status",
      },
      {
        text: "",
        sortable: false,
        value: "actions",
      },
        //   { text: 'Calories', value: 'calories' },
        //   { text: 'Fat (g)', value: 'fat' },
        //   { text: 'Carbs (g)', value: 'carbs' },
        //   { text: 'Protein (g)', value: 'protein' },
        //   { text: 'Iron (%)', value: 'iron' },
      ],
    }
  },
  watch: {
    options: {
      handler() {
        // console.log(this.options.sortBy.length);
        this.getDataFromApi()
      },
      deep: true,
    },
    search: {
      handler() {
        this.getDataFromApi()
      },
      deep: true,
    },
  },
  methods: {
    getDataFromApi() {
      this.loading = true
      this.fakeApiCall().then(data => {
        this.desserts = data.items
        this.totalDesserts = data.total
        this.loading = false
      })
    },
    /**
     * In a real application this would be a call to fetch() or axios.get()
     */
    fakeApiCall() {
      return new Promise((resolve, reject) => {
        const {
          sortBy,
          sortDesc,
          page,
          itemsPerPage
        } = this.options
        const searchvariable = this.search;

        axios
          .get("/api/admin/getadmins?search=" + searchvariable + "&page=" + page + "&itemsPerPage=" + itemsPerPage + "&sortBy=" + sortBy + "&sortDesc=" + sortDesc + "")
          .then((response) => {
            //console.log(response);
            // let items =  [
            //         {
            //             name: 'Frozen Yogurt',
            //             calories: 159,
            //             fat: 6.0,
            //             carbs: 24,
            //             protein: 4.0,
            //             iron: 1,
            //         }];

            let items = response.data.records.data;
            const total = response.data.records.total;
            //const total = items.length;
            // console.log(itemsPerPage);
            // console.log(page);

            //No need of this one
            // if (itemsPerPage > 0) {
            //     console.log((page - 1) * itemsPerPage, page * itemsPerPage);
            //     items = items.slice((page - 1) * itemsPerPage, page * itemsPerPage)
            // }


            //console.log(items);
            resolve({
              items,
              total,
            })
          })
          .catch((error) => {
            if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            } else {
              console.log(error);
            }
          });

      })
    },
    editAdmin(itemid) {
      console.log(itemid);
      this.$router.push({
        name: "admin-edit-adminuser",
        params: {
          id: itemid.id,
        },
      });
    },
    async deleteAdmin(itemid) {
      const ok = await this.$refs.confirm.open({
        title: "Delete Admin User",
        message: "Are you sure you want to delete this admin (#" + itemid + ")?",
        options: {
          color: "error",
          width: 290,
          zIndex: 200,
        },
        icon: "mdi-delete",
      });
      if (ok) {
        axios
          .get("/api/admin/deleteadminuser/" + itemid)
          .then((response) => {
            console.log(response);
            if (response.data.success == true) {
              this.$toasted.show(response.data.message, {
                type: "success",
                icon: 'success_outline',
                action: {
                  text: 'Cancel',
                  onClick: (e, toastObject) => {
                    toastObject.goAway(0);
                  }
                }
              }).goAway(2000);
              this.getDataFromApi();
            } else {

              this.$toasted.show(response.data.message, {
                type: "error",
                icon: 'error_outline',
                action: {
                  text: 'Cancel',
                  onClick: (e, toastObject) => {
                    toastObject.goAway(0);
                  }
                }
              }).goAway(2000);
            }
          })
          .catch((error) => {
            if (error.response.status == 403) {
              this.$router.push("/admin/unauthorized");
            } else {
              console.log(error);
              this.$toasted.show(error, {
                type: "error",
                icon: 'error_outline',
                action: {
                  text: 'Cancel',
                  onClick: (e, toastObject) => {
                    toastObject.goAway(0);
                  }
                }
              }).goAway(2000);
            }
          });
      }
    },
  },
}
</script>