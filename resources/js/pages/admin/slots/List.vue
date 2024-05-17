<template>
  <div style="width: 100%">
    <div>
      <div class="display-1">List Slots</div>
      <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
    </div>

    <v-card>
      <v-card-text>
        <!-- <v-col cols="6" class="d-flex align-center">
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
            hide-details></v-text-field>
        </v-col> -->
        <v-expansion-panels>
          <v-expansion-panel v-for="(category, index) in categories" :key="index">
            <v-expansion-panel-header>
              <strong>{{ category.name }}</strong> <v-spacer></v-spacer> Total Slots ({{ category.slot_count }})
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-expansion-panels>
                <v-expansion-panel v-for="(dates, index) in category.dates" :key="index" v-if="dates != null">
                  <v-expansion-panel-header>
                    <strong>{{ fomartdate(dates.start_date) }} </strong><v-spacer></v-spacer> {{ dates.slots.length }}
                    Slots
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-data-table :headers="headers" :items="dates.slots" sort-by="id" :options.sync="options"
                      :search="search" :loading="loading" class="elevation-1">
                      <template v-slot:item.days="{ item }">
                        <v-chip v-for="(day, index) in item.days" :key="index" color="green" outlined dark
                          class="mr-1 mb-1" style="font-size: 11px; padding: 0px 5px; height: 20px">
                          {{ getDayName(day) }}
                        </v-chip>
                      </template>
                      <template v-slot:item.actions="{ item }">
                        <v-btn color="red" icon fab small @click="deleteSlot(item)"><v-icon small
                            color="red">mdi-delete</v-icon></v-btn>
                      </template>
                    </v-data-table>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>


            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card-text>
    </v-card>
    <confirm ref="confirm"></confirm>
  </div>
</template>

<script>
import axios from "axios";
import Confirm from "@/components/common/Confirm.vue";
import moment from "moment";
export default {
  components: {
    Confirm,
  },
  data() {
    return {
      options: {},
      search: "",
      totalslot: 0,
      loading: false,
      categories: [],
      headers: [
        {
          text: "# ID",
          align: "start",
          value: "id",
        },
        {
          text: "Title",
          sortable: false,
          value: "title",
        },
        {
          text: "Slot Time",
          value: "slot_time",
        },
        {
          text: "Slot Date",
          value: "slot_date",
        },
        {
          text: "Available Days",
          value: "days",
        },
        // {
        //   text: "Category Name",
        //   value: "category.name",
        //   sortable: false,
        // },
        {
          text: "Advance Price",
          value: "advanceprice",
        },
        {
          text: "Price",
          value: "price",
        },
        {
          text: "Actions",
          value: "actions",
          sortable: false,
        },
      ],
      breadcrumbs: [
        {
          text: "Slots",
          disabled: false,
          to: "/admin/slots",
        },
        {
          text: "List",
        },
      ],
      slots: [],
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    fomartdate(date) {
      return moment(date).format("DD MMM YYYY (dddd)");
    },

    getDayName(day) {
      const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      return dayNames[parseInt(day)];
    },

    getCategories() {
      try {
        const response = axios
          .get(
            "/api/admin/category/all?page=" +
            this.options.page +
            "&limit=" +
            this.options.itemsPerPage +
            "&search=" +
            this.search
          )
          .then((response) => {
            this.categories = response.data.categories;
          });
      } catch (error) {
        console.error(error);
      }
    },

    async deleteSlot(slot) {
      const ok = await this.$refs.confirm.open({
        title: "Are you sure?",
        message: "Are you sure you want to delete this slot?",
        okButtonText: "Yes",
        cancelButtonText: "No",
        options: {
          color: "error",
          width: 290,
          zIndex: 200,
        },
        icon: "mdi-delete",
      });
      if (ok) {
        try {
          const response = await axios
            .post(`/api/admin/slots/delete`, { id: slot.id })
            .then((response) => {
              this.$toasted.success("Slot deleted successfully", {
                duration: 2000,
              });
            });
         this.getCategories();
        } catch (error) {
          console.error(error);
        }
      }
    },
  },
};
</script>
<style>
.theme--light.v-calendar-weekly {
  border: none !important;
}

.v-calendar-weekly__head {
  background: #efefef;
  line-height: 30px;
  border: none;
}

.theme--light.v-calendar-weekly .v-calendar-weekly__day {
  border: none;
}

.theme--light.v-calendar-weekly .v-calendar-weekly__head-weekday {
  border: none;
}

.v-present .v-btn {
  background-color: transparent !important;
  border: solid 1px #000 !important;
  color: #000 !important;
}

.v-present .theme--light.v-btn:focus {
  color: #fff !important;
  background: #0096c7 !important;
}

.v-present .theme--light.v-btn:focus::before {}

.v-future .v-btn::before {
  background-color: #0096c7 !important;
}

.v-future .theme--light.v-btn:focus {
  color: #fff !important;
}

.v-future .theme--light.v-btn:focus::before {
  opacity: 0.8 !important;
}
</style>