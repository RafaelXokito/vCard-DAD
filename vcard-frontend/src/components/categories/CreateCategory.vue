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
            <label for="type">Debit</label>
            <Field name="type" type="radio" value="D" class="form-control" />
            <ErrorMessage name="type" class="error-feedback" />
            <label for="type">Credit</label>
            <Field name="type" type="radio" value="C" class="form-control"/>
            <ErrorMessage name="type" class="error-feedback" />
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

export default {
    name: "createCategoryd",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    props: {
        messageCreate: {
        type: String,
        default: () => "",
        },
    },
    data() {
        const schema = yup.object().shape({
            name: yup.string().required("Name is required!"),
        });

        return {
            loading: false,
            schema,
        };
    },
    emits: [
        'create',
    ],
    methods: {
    async handleCreate(category) {
      this.loading = true;
      await this.$emit('create', category)
      this.loading = false;
    },
  },
}
</script>

<style>

</style>