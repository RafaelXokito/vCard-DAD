<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categories</h1>
  </div>
  <div class="container">
      {{content}}
    <create-category @create="createCategory" :messageCreate="messageCreate"></create-category>
    <categories-table
    :categories="categories"
    :showId="false"
    @edit="editCategory"
    @list="list"
  ></categories-table>
  </div>
</template>

<script>
import CategoryService from "../../services/category.service";

import CategoriesTable from "./CategoriesTable.vue"
import CreateCategory from './CreateCategory.vue'

export default {
  name: "Categories",
  components: {
    CategoriesTable,
    CreateCategory
  },
  data() {
    return {
      content: "",
      categories: [],
      messageCreate: "",
    };
  },
  computed: {
    totalCategorys () {
      return this.categories.length
    }
  },
  methods: {
    editCategory(category){
      console.log(category);
    },
    createCategory(category){
        CategoryService.postCategory(category).then(
            () => {
                this.list();
            },
            (error) => {
                this.messageCreate =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
            }
        );
    },
    list(link){
        CategoryService.getCategoryBoard(this.$store.state.auth.user.id, link).then(
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
    }
  },
  mounted() {
    this.list();
  },
};
</script>