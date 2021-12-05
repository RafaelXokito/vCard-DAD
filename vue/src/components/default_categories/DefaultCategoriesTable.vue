<template>
<div>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="name"
        class="form-label"
      >Filter by name:</label>
      <input
        class="form-control"
        id="name"
        name="name"
        placeholder="John Doe"
        v-model.lazy="filterByName"
      />
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="type"
        class="form-label"
      >Filter by type:</label>
      <select
        class="form-select"
        id="type"
        name="type"
        v-model.lazy="filterByType"
      >
        <option value="">Any</option>
        <option value="C">Credit</option>
        <option value="D">Debit</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <a
        class="btn btn-success px-4 btn-addtask"
        href="#"
        @click.prevent="showCreate"
      ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Default Category</a>
    </div>
  </div>
  <div v-if="isTableVisible">
    <table class="table">
      <thead>
        <tr>
          <th
            v-if="showId"
            class="align-middle"
          >#</th>
          <th class="align-middle">Name</th>
          <th class="align-middle">Type</th>
          <th style="width: 50px" v-if="showEdit">
          </th>
          <th style="width: 50px">
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="category in categories.data"
          :key="category.id"
          :class="category.transaction_type == 'A' ? 'table-success' : ''"
        >
          <td
            v-if="showId"
            class="align-middle"
          >{{ category.id }}</td>
          <td class="align-middle" v-html="highlightNameMatches(category.name.charAt(0).toUpperCase() + category.name.slice(1))"></td>
          <td class="align-middle" v-html="highlightTypeMatches(category.type === 'C' ? 'Credit' : 'Debit')"></td>
          <td v-if="showEdit && category.deleted === 0" 
            class="text-end align-middle"
          >
            <div class="d-flex justify-content-end" >
              <button class="btn btn-xs btn-warning" @click="editClick(category)">
                <font-awesome-icon :icon="['fas', 'pen']" size="xs" />
              </button>
            </div>
          </td>
          <td
            class="text-end align-middle"
          >
            <div class="d-flex justify-content-end">
              <button class="btn btn-xs btn-danger" @click="deleteClick(category)" v-if="category.deleted === 0">
                <font-awesome-icon :icon="['fas', 'trash']" size="xs" />
              </button>
              <button class="btn btn-xs btn-success" @click="restoreClick(category)" v-else-if="category.deleted === 1">
                <font-awesome-icon :icon="['fas', 'trash-restore']" size="xs" />
              </button>
              <button class="btn btn-xs btn-warning" v-else>
                  <span class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
      <div class="d-flex justify-content-center">
          <pagination class="mx-auto" align="center" :data="categories" @pagination-change-page="list"></pagination>
      </div>
  </div>
  <div class="d-flex justify-content-center p-5" v-else>
      <div class="spinner-grow text-info" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
      </div>
  </div>
</div>
</template>

<script>
import Pagination from '../global/Pagination.vue'

export default {
  name: "categoriesTable",
  components: {
      Pagination,
  },
  props: {
    showId: {
      type: Boolean,
      default: false,
    },
    showEdit: {
      type: Boolean,
      default: false,
    },
    categories: {
      type: Object,
    },
  },
  emits: [
    'edit',
    'list',
    'delete',
    'showCreate'
  ],
  data() {
      return {
          filterByName: "",
          filterByType: "",
      }
  },
  computed: {
    isTableVisible(){
      return this.categories != null && this.categories.links != null
    }
  },
  methods: {
    editClick (category) {
      this.$emit('edit', category)
    },
    deleteClick(category) {
      this.$emit('delete', category)
    },
    restoreClick(category) {
      this.$emit('restoreClick', category)
    },
    async list(link){
      this.$emit('list', link)
    },
    showCreate(){
      this.$emit('showCreate')
    },
    highlightTypeMatches(text) {
      let filter = this.filterByType != "" ? (this.filterByType == 'C' ? 'Credit' : 'Debit') : ""
      const matchExists = text
        .toLowerCase()
        .includes(filter.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(filter, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    },
    highlightNameMatches(text) {
      const matchExists = text
        .toLowerCase()
        .includes(this.filterByName.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(this.filterByName, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    },
  },
  watch:{
    async filterByName(newVal){
      this.isTableVisible = false
      let link = this.categories.links.first
      if (newVal) {
        link += '&name='+newVal
      }
      if (this.filterByType) {
        link += '&type='+this.filterByType
      }
      await this.$emit('list', link)
      this.isTableVisible = true
    },
    async filterByType(newVal){
      this.isTableVisible = false
      let link = this.categories.links.first
      if (this.filterByName) {
        link += '&name='+this.filterByName
      }
      if (newVal) {
        link += '&type='+newVal
      }
      await this.$emit('list', link)
      this.isTableVisible = true
    },
  }
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
.btn-addtask {
  margin-top: 1.85rem;
}
</style>
