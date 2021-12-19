<template>
    <div class="container">
        <div class="pt-5">
            <button class="btn btn-outline-info" @click.prevent="back" style="width: 100px">
                    <font-awesome-icon :icon="['fas', 'arrow-left']" size="lg" /> Back
            </button>
        </div>
        <div class="row pt-5">
            <h1 class="text-center">Update Transaction</h1>
            <h4 class="text-center danger">
                <p :style="transaction.type === 'D' ? 'color:red' : 'color:green'">Before: {{transaction.old_balance + ' € '}}</p>
                <p :style="transaction.type === 'D' ? 'color:red' : 'color:green'">After: {{transaction.new_balance + ' €'}}</p>
                <p>Date Time: {{getDate(transaction.datetime)}}</p>
            </h4>
        </div>
        <hr>
        <div v-if="!loadingDependencies">
            <section class="section about-section gray-bg" id="about">
                <div class="container pt-5">
                    <Form @submit="handleSave" :validation-schema="schema" class="w-75 mx-auto" method="PATCH" enctype="multipart/form-data">
                    <div v-if="!successful" class="w-100">
                        <div class="row justify-content-center mb-4 radio-group">
                            <div class="col-sm-3 col-5" v-for="paymentType in paymentTypes" v-bind:key="paymentType.code">
                                <Field v-model="payment_type" type="radio" :id="'paymentType'+paymentType.code" name="payment_type" :title="'Payment Type '+paymentType.code" :value="paymentType.code" class='radio mx-auto d-none' disabled></Field>
                                <label :for="'paymentType'+paymentType.code">
                                    <img v-if="getImage(paymentType.custom_data)" :ref="'paymentType'+paymentType.code" :class="{'selected': paymentType.code == payment_type}" :for="'paymentType'+paymentType.code" :alt="paymentType.code + ' Image'" class="fit-image radio" :src="getImage(paymentType.custom_data)" style="width: 105px; height: 55px;"> 
                                    <span v-else class="radio text-center" :class="{'selected': paymentType.code === payment_type}" style="width: 105px; height: 55px;">{{ paymentType.name }}</span> 
                                </label>
                            </div>
                            <br>
                        </div>
                        <div class="row justify-content-center pt-3">
                            <div class="col-12">
                                <div class="input-group"> 
                                    <Field type="text" class="card w-100" :value="transaction.payment_reference" name="payment_reference" placeholder="9XX XXX XXX" title="Payment Reference" disabled/> <label>Payment Reference</label>
                                    <ErrorMessage name="payment_reference" class="error-feedback" />
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center pt-3">
                            <div class="col-12">
                                <div class="input-group"> 
                                    <select v-model="category" name="category" title="Category" class="w-100" style="border-radius: 10px !important;border-width: 2px;"> 
                                        <option value="" selected>None</option>
                                        <option v-for="category in categories" v-bind:key="category.id" :value="category.id"><span class="text-capitalize">{{ titleCase(category.name) }}</span></option>
                                    </select>
                                    <label>Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center pt-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <Field class="card w-100" :value="transaction.value" type="number" name="value" :placeholder="0.00" min="0.00" step="0.01" title="Currency" disabled /> 
                                    <label class="mx-auto">Value (€)</label> 
                                    <ErrorMessage name="value" class="error-feedback" />
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center pt-3">
                            <div class="col-12">
                                <div class="input-group"> 
                                    <Field class="w-100" type="text" name="description" :value="transaction.description" placeholder="Vacations Hotel" title="Description" style="border-radius: 10px !important;"/> <label>Description</label>
                                    <ErrorMessage name="description" class="error-feedback" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center pt-3">
                            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                                <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                                <span> Save</span>
                            </button>
                        </div>
                        <div class="form-group" v-if="message">
                            <ul class="alert alert-danger" role="alert">
                                <li>
                                    {{message.message}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </Form>
            </div>
            </section>
        </div>
        <div class="d-flex justify-content-center p-5" v-else>
            <div class="spinner-grow text-info" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";

import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import TransactionService from "../../services/transaction.service"
import CategoryService from "../../services/category.service";
import PaymentTypeService from "../../services/payment-type.service";

import Utils from "../../utils/utils"

export default {
    name: "EditTransaction",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    props: {
        transaction: {
            type: Object,
        },
    },
    data() {
        const schema = yup.object().shape({
            category: yup.string(),
        });
        return {
            loadingDependencies: true,
            successful: false,
            loading: false,
            message: "",
            schema,
            errors: {},
            paymentTypes: [],
            categories: [],
            category: '',
            payment_type: "",
        };
    },
    async mounted() {
        let username = this.$store.state.user.data.username;
        await PaymentTypeService.getPaymentType().then(
            ({data}) => {
                this.paymentTypes = data.data;
                this.payment_type = this.transaction.payment_type;
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
        await CategoryService.getCategoryBoard(username,`vcards/${username}/categories?type=${this.transaction.type}`).then(
            ({data}) => {
                this.categories = data.data;
                this.category = this.transaction.category ?? '';
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
        this.loadingDependencies = false;
    },
    methods: {
        async handleSave(transaction){
            transaction["id"] = this.transaction.id;
            transaction["category"] = this.category;
            await TransactionService.patchTransaction(transaction).then(
                () => {
                    this.$toast.success(`Transaction saved with success.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.back();
                },
                (error) => {
                    this.loadingDependencies = false;
                    this.errors = error.response.data.errors;
                    this.messageCreate =
                    (error.response &&
                        error.response.data &&
                        error.response.data.errors["value"][0]) ||
                    error.message ||
                    error.toString();
                    this.$toast.error(`Transaction was not saved. ${this.messageCreate}`, {autoHideDelay: 2000, appendToast: true}) 
                }
            );
        },
        getImage(json){
            let obj = JSON.parse(json);
            return obj != null ? obj.image : "";
        },
        titleCase(str){
            return Utils.titleCase(str);
        },
        back(){
            this.$emit('close');
        },
        getDate : function (date) {
            return moment(date).utc().fromNow();
        }
    },
    emits: [
        'close'
    ],
}
</script>

<style scoped>

.heading {
    font-size: 40px;
    margin-top: 35px;
    margin-bottom: 30px;
    padding-left: 20px
}

.card {
    border-radius: 10px !important;
}

.form-card {
    margin-left: 20px;
    margin-right: 20px
}

.form-card select{
    padding-top: 20px !important; 
}

.form-card input,
.form-card button,
.form-card select,
.form-card textarea {
    padding: 10px 15px 5px 15px;
    border: none;
    border: 1px solid lightgrey;
    border-radius: 6px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: arial;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px
}

.form-card input:focus,
.form-card button:focus,
.form-card select:focus,
.form-card textarea:focus {
    -moz-box-shadow: 0px 0px 0px 1.5px skyblue !important;
    -webkit-box-shadow: 0px 0px 0px 1.5px skyblue !important;
    box-shadow: 0px 0px 0px 1.5px skyblue !important;
    font-weight: bold;
    border: 1px solid #304FFE;
    outline-width: 0
}

.input-group {
    position: relative;
    width: 100%;
    overflow: hidden
}

.input-group input,select {
    position: relative;
    height: 80px;
    margin-left: 1px;
    margin-right: 1px;
    border-radius: 6px;
    padding-top: 30px;
    padding-left: 25px
}

.input-group div,
.input-group label  {
    position: absolute;
    height: 24px;
    background: none;
    border-radius: 6px;
    line-height: 48px;
    font-size: 15px;
    color: gray;
    width: 100%;
    font-weight: 100;
    padding-left: 25px
}

input:focus+label {
    color: #304FFE
}

select:focus+label {
    color: #304FFE
}

.btn-pay {
    background-color: #304FFE;
    height: 60px;
    color: #ffffff !important;
    font-weight: bold
}

.btn-pay:hover {
    background-color: #3F51B5
}

.fit-image {
    width: 100%;
    object-fit: cover
}

img {
    border-radius: 5px;
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    border-radius: 6px;
    box-sizing: border-box;
    border: 2px solid lightgrey;
    cursor: pointer;
    margin: 12px 25px 12px 0px
}

.radio:hover {
    box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.2)
}

.radio.selected {
    box-shadow: 0px 0px 0px 1px rgba(0, 0, 155, 0.4);
    border: 2px solid blue
}

.label-radio {
    font-weight: bold;
    color: #000000
}
ul {
  list-style-type: none;
}
.error-feedback{
  color: #db3b3b;
  font-size: 12px;
}
</style>