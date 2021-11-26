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
        <td class="align-middle">{{ category.name.charAt(0).toUpperCase() + category.name.slice(1) }}</td>
        <td class="align-middle">{{ category.type }}</td>
        <td
          class="text-end align-middle"
        >
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-warning" @click="editClick(transaction)">
              <font-awesome-icon :icon="['fas', 'pen']" size="xs" />
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
import CategoryService from "../../services/category.service";

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
  },
  emits: [
    'edit',
    'list'
  ],
  data() {
      return {
          isTableVisible: false,
          categories: {}
      }
  },
  mounted() {
    console.log("mount");
    this.list()
  },
  methods: {
    editClick (category) {
      this.$emit('edit', category)
    },
    list(link){
      CategoryService.getCategoryBoard(this.$store.state.auth.user.username, link).then(
          ({data}) => {
              this.categories = data;
          },
          (error) => {
              this.content =
              (error.response &&
                  error.response.data &&
                  error.response.data.message) ||
              error.message ||
              error.toString();
          }
      );
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
