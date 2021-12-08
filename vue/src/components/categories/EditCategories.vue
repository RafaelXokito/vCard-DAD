<template>
<div class="container">
    <div class="pt-5">
        <button class="btn btn-outline-info" @click.prevent="back" style="width: 100px">
                <font-awesome-icon :icon="['fas', 'arrow-left']" size="lg" /> Back
        </button>
    </div>
    <div>
        <h1 class="text-center">Edit Category</h1>
    </div>
    <hr>
    <Form @submit="handleEdit" :validation-schema="schema" class="w-75 mx-auto">
        <div class="form-group">
            <label for="name">Name</label>
            <Field :value="category.name.charAt(0).toUpperCase() + category.name.slice(1)" name="name" type="text" class="form-control" />
            <ErrorMessage name="name" class="error-feedback" />
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label" for="type">Debit</label>
                <Field name="type" type="radio" value="D" class="form-check-input" v-model="categoryType" />
                <ErrorMessage name="type" class="error-feedback" />
            </div>
            <div class="form-check">
                <label class="form-check-label" for="type">Credit</label>
                <Field name="type" type="radio" value="C" class="form-check-input" v-model="categoryType" />
                <ErrorMessage name="type" class="error-feedback" />
            </div>
        </div>
        <div class="form-group">
            <div v-if="content" class="alert alert-danger" role="alert">
                {{ content }}
            </div>
        </div>
        <div class="form-group text-center pt-3">
            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                <span> Save</span>
            </button>
        </div>
    </Form>
</div>  
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import CategoryService from "../../services/category.service";

export default {
    name: "editCategories",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    data() {
        const schema = yup.object().shape({
            name: yup.string().required("Name is required!"),
        });

        return {
            loading: false,
            schema,
            content: "",
            categoryType: ''
        };
    },
    props: {
        category: {
            type: Object,
        },
    },
    mounted() {
        this.categoryType = this.category.type;
    },
    emits: [
        'edit',
        'close',
    ],
    methods: {
        async handleEdit(category) {
            this.loading = true;
            await this.editCategory(category);
            this.loading = false;
        },
        editCategory(category){
            category["id"] = this.category.id
            CategoryService.patchCategory(category).then(
                () => {
                    this.$toast.success(`Category ${category.name} updated successful.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.back();
                },
                (error) => {
                    this.content =
                    (error.response &&
                        error.response.data &&
                        error.response.data.message) ||
                    error.message ||
                    error.toString();
                    this.$toast.error(`Category was not ${category.name} created. ${this.content}`, {autoHideDelay: 2000, appendToast: true}) 
                }
            );
        },
        back(){
            this.$emit('close');
        }
  },
}
</script>

<style>
ul {
  list-style-type: none;
}
.error-feedback{
  color: #db3b3b;
  font-size: 12px;
}
</style>