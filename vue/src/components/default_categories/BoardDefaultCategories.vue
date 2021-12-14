<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Default Categories</h1>
  </div>
  <div class="container">
    <div v-if="!defaultcategoryToCreateEditShow">
      <router-view @edit="editDefaultCategory" @delete="deleteDefaultCategory" @restoreClick="restoreDefaultCategory" @forceDelete="forceDeleteDefaultCategory" :categories="categories" @list="list" :showEdit="true" @showCreate="showCreate"></router-view>
    </div>
    <div v-else-if="defaultcategoryToCreateEditShow">
        <create-edit-categories :defaultcategory="defaultcategoryToCreateEdit" @close="closeDefaultCategoryCreateEdit" :isCreate="isCreate"></create-edit-categories>
    </div>
  </div>
  <confirm-dialog></confirm-dialog>
</div>
</template>

<script>
import DefaultCategoryService from "../../services/default-category.service";
import CreateEditCategories from './CreateEditDefaultCategories.vue';

import ConfirmDialog from 'primevue/confirmdialog';

export default {
  components: { CreateEditCategories, ConfirmDialog },
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
      this.defaultcategoryToCreateEditShow = true;
      this.defaultcategoryToCreateEdit = defaultcategory;
      this.isCreate = false
    },
    showCreate(){
      this.defaultcategoryToCreateEditShow = true;
    },
    deleteDefaultCategory(defaultcategory){

      let deletedState = defaultcategory.deleted;

      let message = `Do you really want to delete default category ${defaultcategory.name}?`;

      this.$confirm.require({
          message: message,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          accept: async () => {
              defaultcategory.deleted = 'loading'
              await DefaultCategoryService.deleteDefaultCategory(defaultcategory).then(
                  () => {
                    //alert("DefaultCategory Deleted")
                    defaultcategory.deleted = !defaultcategory.deleted
                    this.$toast.success(`Default Category ${defaultcategory.name} deleted successful.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.list();
                  },
                  (error) => {
                      this.messageEdit =
                      (error.response &&
                          error.response.data &&
                          error.response.data.message) ||
                      error.message ||
                      error.toString();
                      this.$toast.error(`During deleting ${defaultcategory.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                      defaultcategory.deleted = deletedState;
                  }
              );
          },
          reject: () => {
            defaultcategory.deleted = deletedState
          }
      });
    },
    forceDeleteDefaultCategory(defaultcategory){
      
      let deletedState = defaultcategory.deleted;

      let message = `Do you really want to force delete default category ${defaultcategory.name}?`;

      this.$confirm.require({
          message: message,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-circle',
          accept: async () => {
              defaultcategory.deleted = 'loading'
              await DefaultCategoryService.forceDeleteDefaultCategory(defaultcategory).then(
                  () => {
                    //alert("DefaultCategory Deleted")
                    defaultcategory.deleted = !defaultcategory.deleted
                    this.$toast.success(`Default Category ${defaultcategory.name} force deleted successful.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.list();
                  },
                  (error) => {
                      this.messageEdit =
                      (error.response &&
                          error.response.data &&
                          error.response.data.message) ||
                      error.message ||
                      error.toString();
                      this.$toast.error(`During force deleting ${defaultcategory.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                      defaultcategory.deleted = deletedState;
                  }
              );
          },
          reject: () => {
            defaultcategory.deleted = deletedState
          }
      });
    },
    restoreDefaultCategory(defaultcategory){
      let deletedState = defaultcategory.deleted;

      let message = `Do you really want to restore default category ${defaultcategory.name}?`;

      this.$confirm.require({
          message: message,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          accept: async () => {
              defaultcategory.deleted = 'loading'
              await DefaultCategoryService.restoreDefaultCategory(defaultcategory).then(
                  () => {
                    //alert("DefaultCategory Deleted")
                    defaultcategory.deleted = !defaultcategory.deleted
                    this.$toast.success(`Default category ${defaultcategory.name} restore successful.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.list();
                  },
                  (error) => {
                      this.content =
                      (error.response &&
                          error.response.data &&
                          error.response.data.message) ||
                      error.message ||
                      error.toString();
                      this.$toast.error(`During restoring ${defaultcategory.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                      defaultcategory.deleted = deletedState;
                  }
              );
          },
          reject: () => {
            defaultcategory.deleted = deletedState
          }
      });
    },
    closeDefaultCategoryCreateEdit(){
      this.isCreate = true
      this.list()
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