<template>
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
        <td class="align-middle">{{ category.name.charAt(0).toUpperCase() + category.name.slice(1) }}</td>
        <td class="align-middle">{{ category.type }}</td>
        <td v-if="showEdit"
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
            <button class="btn btn-xs btn-danger" @click="deleteClick(category)">
              <font-awesome-icon :icon="['fas', 'trash']" size="xs" />
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
    'delete'
  ],
  data() {
      return {
          isTableVisible: false,
      }
  },
  mounted() {
    this.list()
  },
  methods: {
    editClick (category) {
      this.$emit('edit', category)
    },
    deleteClick(category) {
      this.$emit('delete', category)
    },
    list(link){
      this.$emit('list', link)
    },
  },
  watch:{
    categories(newVal){
      if (newVal) {
        this.isTableVisible = true;
      }
    }
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
</style>
