<template>
<div class="container">
    <h1 class="text-center">Create Category</h1>
    <Form @submit="handleCreate" :validation-schema="schema" class="w-75 mx-auto">
        <div class="form-group">
            <label for="name">Name</label>
            <Field name="name" type="text" class="form-control" />
            <ErrorMessage name="name" class="error-feedback" />
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label" for="type">Debit</label>
                <Field name="type" type="radio" value="D" class="form-check-input" />
                <ErrorMessage name="type" class="error-feedback" />
            </div>
            <div class="form-check">
                <label class="form-check-label" for="type">Credit</label>
                <Field name="type" type="radio" value="C" class="form-check-input"/>
                <ErrorMessage name="type" class="error-feedback" />
            </div>
        </div>
        <div class="form-group">
            <div v-if="messageCreate" class="alert alert-danger" role="alert">
                {{ messageCreate }}
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
            <span
                v-show="loading"
                class="spinner-border spinner-border-sm"
            ></span>
            <span> Create</span>
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
    name: "createCategoryd",
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
            messageCreate: ""
        };
    },
    emits: [
        'create',
    ],
    methods: {
    async handleCreate(category) {
      this.loading = true;
      await this.createCategory(category);
      this.loading = false;
    },
    createCategory(category){
        CategoryService.postCategory(category).then(
            () => {
                this.$router.push({name: 'categoriesTable'});
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
  },
}
</script>

<style>

</style>