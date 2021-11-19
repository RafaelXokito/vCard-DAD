<template>
<div v-if="isTableVisible">
  <table class="table">
    <thead>
      <tr>
        <th
          v-if="showId"
          class="align-middle"
        >#</th>
        <th class="align-middle">name</th>
        <th class="align-middle">type</th>
        <th>
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
        <td class="align-middle">{{ category.name }}</td>
        <td class="align-middle">{{ category.type }}</td>
        <td
          class="text-end align-middle"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(category)"
            > <i class="bi bi-xs bi-pencil"></i>e
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
    <div class="row">
        <pagination class="mx-auto" align="center" :data="categories" @pagination-change-page="list"></pagination>
    </div>
</div>
</template>

<script>
import Pagination from './Pagination.vue'
export default {

  name: "CategoryTable",
  components: {
      Pagination,
  },
  props: {
    categories: {
      type: Object,
      default: () => {},
    },
    showId: {
      type: Boolean,
      default: true,
    },
  },
  emits: [
    'edit',
    'list'
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
    async list(link){
        await this.$emit('list', link)
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
