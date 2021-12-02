<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categories</h1>
  </div>
  <div class="container">
    <div v-if="!defaultcategoryToCreateEditShow">
      <router-view @edit="editDefaultCategory" @delete="deleteDefaultCategory" :categories="categories" @list="list" :showEdit="true" @showCreate="showCreate"></router-view>
    </div>
    <div v-else-if="defaultcategoryToCreateEditShow">
        <create-edit-categories :defaultcategory="defaultcategoryToCreateEdit" @close="closeDefaultCategoryCreateEdit" :isCreate="isCreate"></create-edit-categories>
    </div>
  </div>
</div>
</template>

<script>
import DefaultCategoryService from "../../services/default-category.service";
import CreateEditCategories from './CreateEditDefaultCategories.vue';

export default {
  components: { CreateEditCategories },
  name: "Categories",
  data() {
    return {
      content: "",
      categories: [],
      messageCreate: "",
      defaultcategoryToCreateEdit: {},
      defaultcategoryToCreateEditShow: false,
      isCreate: true
    };
  },
  computed: {
    totalDefaultCategorys () {
      return this.categories.length
    }
  },
  methods: {
    editDefaultCategory(defaultcategory){
      this.isCreate = false
      this.defaultcategoryToCreateEditShow = true;
      this.defaultcategoryToCreateEdit = defaultcategory;
    },
    showCreate(){
      this.defaultcategoryToCreateEditShow = true;
    },
    deleteDefaultCategory(defaultcategory){
      if(confirm(`Do you really want to delete defaultcategory ${defaultcategory.name}?`)){
        DefaultCategoryService.deleteDefaultCategory(defaultcategory).then(
            () => {
              alert("DefaultCategory Deleted")
              this.list();
            },
            (error) => {
                this.messageEdit =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
              alert(this.messageEdit)

            }
        );
      }
    },
    closeDefaultCategoryCreateEdit(){
      this.defaultcategoryToCreateEditShow = false;
    },
    list(link){
        DefaultCategoryService.getDefaultCategoryBoard(link).then(
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