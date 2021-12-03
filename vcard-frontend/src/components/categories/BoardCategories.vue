<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categories</h1>
  </div>
  <div class="container">
    <div v-if="!categoryToEditShow">
      <router-view @edit="editCategory" @delete="deleteCategory" :categories="categories" @list="list" :showEdit="true"></router-view>
    </div>
    <div v-else-if="categoryToEditShow">
        <edit-categories :category="categoryToEdit" @close="closeCategoryEdit"></edit-categories>
    </div>
  </div>
  <confirm-dialog></confirm-dialog>
</div>
</template>

<script>
import CategoryService from "../../services/category.service";
import EditCategories from './EditCategories.vue';

import ConfirmDialog from 'primevue/confirmdialog';

export default {
  components: { EditCategories,ConfirmDialog },
  name: "Categories",
  data() {
    return {
      content: "",
      categories: [],
      messageCreate: "",
      categoryToEdit: {},
      categoryToEditShow: false,
    };
  },
  computed: {
    totalCategorys () {
      return this.categories.length
    }
  },
  methods: {
    editCategory(category){
      this.categoryToEditShow = true;
      this.categoryToEdit = category;
    },
    deleteCategory(category){
      
      this.$confirm.require({
          message: `Do you really want to delete category ${category.name}?`,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          accept: async () => {
              CategoryService.deleteCategory(category).then(
            () => {
              this.$toast.success(`Category ${category.name} deleted successful.`, {autoHideDelay: 2000, appendToast: true}) 
              this.list();
            },
            (error) => {
                this.content =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
              this.$toast.error(`During deleting ${category.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
            });
          },
      });
    },
    closeCategoryEdit(){
      this.categoryToEditShow = false;
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
  mounted() {
    this.list();
  },
};
</script>