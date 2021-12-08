<template>
<div class="container">
    <div class="pt-5">
        <button class="btn btn-outline-info" @click.prevent="back" style="width: 100px">
                <font-awesome-icon :icon="['fas', 'arrow-left']" size="lg" /> Back
        </button>
    </div>
    <div>
        <h1 class="text-center">{{paymenttype ? 'Edit' : 'Create' }} PaymentType</h1>
    </div>
    <hr>
    <Form @submit="handleEdit" :validation-schema="schema" class="w-75 mx-auto">
        <div class="form-group">
            <label for="code">Code</label>
            <Field :value="!isCreate ? paymenttype.code : ''" name="code" type="text" class="form-control" :disabled="!isCreate" />
            <ErrorMessage name="code" class="error-feedback" />
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <Field :value="!isCreate ? paymenttype.name.charAt(0).toUpperCase() + paymenttype.name.slice(1) : ''" name="name" type="text" class="form-control" />
            <ErrorMessage name="name" class="error-feedback" />
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <Field :value="!isCreate ? paymenttype.description : ''" name="description" type="text" class="form-control" />
            <ErrorMessage name="description" class="error-feedback" />
        </div>
        <div class="form-group">
            <div v-if="messageEdit" class="alert alert-danger" role="alert">
                {{ messageEdit }}
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

import PaymentTypeService from "../../services/payment-type.service";

export default {
    name: "createEditPaymentType",
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
        };
    },
    props: {
        paymenttype: {
            type: Object,
            default: () => {}
        },
        isCreate: {
            type: Boolean,
            default: true
        }
    },
    emits: [
        'edit',
        'close',
    ],
    methods: {
        async handleEdit(paymenttype) {
            this.loading = true;
            if (this.isCreate) {
                await this.createCategory(paymenttype);
            } else {
                await this.editCategory(paymenttype);
            }
            this.loading = false;
        },
        editCategory(paymenttype){
            PaymentTypeService.patchPaymentType(paymenttype).then(
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
        createCategory(paymenttype){
            PaymentTypeService.postPaymentType(paymenttype).then(
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