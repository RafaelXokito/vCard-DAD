<template>
<div class="container">
    <div class="pt-5">
        <button class="btn btn-outline-info" @click.prevent="back" style="width: 100px">
                <font-awesome-icon :icon="['fas', 'arrow-left']" size="lg" /> Back
        </button>
    </div>
    <div>
        <h1 class="text-center">{{defaultcategory ? 'Edit' : 'Create' }} Default Category</h1>
    </div>
    <hr>
    <Form @submit="handleEdit" :validation-schema="schema" class="w-75 mx-auto">
        <div class="form-group">
            <label for="name">Name</label>
            <Field :value="!isCreate ? defaultcategory.name.charAt(0).toUpperCase() + defaultcategory.name.slice(1) : ''" name="name" type="text" class="form-control" />
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
            <div v-if="messageEdit" class="alert alert-danger" role="alert">
                {{ messageEdit }}
            </div>
        </div>
        <div class="form-group text-center pt-3">
            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                <span> Guardar</span>
            </button>
        </div>
    </Form>
</div>  
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import CategoryService from "../../services/default-category.service";

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
            messageEdit: "",
            categoryType: ''
        };
    },
    props: {
        defaultcategory: {
            type: Object,
            default: () => {}
        },
        isCreate: {
            type: Boolean,
            default: true
        }
    },
    mounted() {
        this.categoryType = this.defaultcategory != null ? this.defaultcategory.type : '' ;
    },
    emits: [
        'edit',
        'close',
    ],
    methods: {
        async handleEdit(defaultcategory) {
            this.loading = true;
            if (this.isCreate) {
                await this.createCategory(defaultcategory);
            } else {
                await this.editCategory(defaultcategory);
            }
            this.loading = false;
        },
        editCategory(defaultcategory){
            defaultcategory["id"] = this.defaultcategory.id
            CategoryService.patchDefaultCategory(defaultcategory).then(
                () => {
                    this.back();
                },
                (error) => {
                    this.messageEdit =
                    (error.response &&
                        error.response.data &&
                        error.response.data.message) ||
                    error.message ||
                    error.toString();
                }
            );
        },
        createCategory(defaultcategory){
            defaultcategory["id"] = this.defaultcategory.id
            CategoryService.postDefaultCategory(defaultcategory).then(
                () => {
                    this.back();
                },
                (error) => {
                    this.messageEdit =
                    (error.response &&
                        error.response.data &&
                        error.response.data.message) ||
                    error.message ||
                    error.toString();
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