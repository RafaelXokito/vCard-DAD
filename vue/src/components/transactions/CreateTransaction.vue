<template>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class=" col-lg-6 col-md-8 pt-5" >
            <div class="card p-3" v-show="(!loadingDependencies && !showConfirmationCode)">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="heading text-center">Create Transaction</h2>
                    </div>
                </div>
                <div class="form-group" v-if="messageCreate">
                    <ul class="alert alert-danger" role="alert">
                        <li v-for="(value, key) in errors" :key="key">
                            {{value[0]}}
                        </li>
                    </ul>
                </div>
                <Form @submit="handleCreate" :validation-schema="schema" class="form-card">
                    <div class="row justify-content-center mb-4 radio-group">
                        <div class="col-sm-3 col-5" v-for="paymentType in paymentTypes" v-bind:key="paymentType.code">
                            <Field v-model="payment_type" type="radio" :id="'paymentType'+paymentType.code" name="payment_type" :title="'Payment Type '+paymentType.code" :value="paymentType.code" class='radio mx-auto d-none'></Field>
                            <label :for="'paymentType'+paymentType.code">
                                <img v-if="getImage(paymentType.custom_data)" :ref="'paymentType'+paymentType.code" :class="{'selected': paymentType.code == payment_type}" :for="'paymentType'+paymentType.code" :alt="paymentType.code + ' Image'" class="fit-image radio" :src="getImage(paymentType.custom_data)" style="width: 105px; height: 55px;"> 
                                <span v-else class="radio text-center" :class="{'selected': paymentType.code === payment_type}" style="width: 105px; height: 55px;">{{ paymentType.name }}</span> 
                            </label>
                        </div>
                        <br>
                    </div>
                    <ErrorMessage name="payment_type" class="error-feedback" />
                    <div class="row justify-content-center" v-if="payment_type">
                        <div class="col-12 text-center">
                            <span>{{paymentTypes.filter((e)=>e.code===payment_type)[0].description}}</span>
                        </div>
                    </div>
                    <!-- NOT IMPLEMENTED YET
                    <div class="row justify-content-center form-group">
                        <div class="col-12 px-auto">
                            <div class="custom-control custom-radio custom-control-inline"> <input id="customRadioInline1" type="radio" name="customRadioInline1" class="custom-control-input" checked="true"> <label for="customRadioInline1" class="custom-control-label label-radio">Private</label> </div>
                            <div class="custom-control custom-radio custom-control-inline"> <input id="customRadioInline2" type="radio" name="customRadioInline1" class="custom-control-input"> <label for="customRadioInline2" class="custom-control-label label-radio">Business</label> </div>
                        </div>
                    </div>-->
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="input-group"> 
                                <Field type="text" class="card" name="payment_reference" placeholder="9XX XXX XXX" title="Payment Reference" /> <label>Payment Reference</label>
                                <ErrorMessage name="payment_reference" class="error-feedback" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" v-if="currentUser.user_type === 'A'">
                        <div class="col-12">
                            <div class="input-group"> 
                                <Field type="text" class="card" name="vcard" placeholder="9XX XXX XXX" title="vCard" /> <label>vCard</label>
                                <ErrorMessage name="vcard" class="error-feedback" />
                            </div>
                        </div>
                    </div>
                    <!--<div class="row justify-content-center">
                        <div class="col-12">
                            <div class="input-group"> <Field type="text" id="cr_no" name="card-no" placeholder="0000 0000 0000 0000" minlength="19" maxlength="19" /> <label>Card Number</label> </div>
                        </div>
                    </div>-->
                    <div class="row justify-content-center" v-if="currentUser.user_type === 'V'">
                        <div class="col-12">
                            <div class="input-group"> 
                                <select v-model="transaction.category" name="category" title="Category" style="border-radius: 10px !important;"> 
                                    <option value="" selected>None</option>
                                    <option v-for="category in categories" v-bind:key="category.id" :value="category.id"><span class="text-capitalize">{{ titleCase(category.name) }}</span></option>
                                </select>
                                <label>Category</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="input-group">
                                <Field class="card" v-model="value" type="number" name="value" placeholder="0.00" min="0.00" value="0.00" step="0.01" title="Currency" pattern="^\d*(\.\d{0,2})?$" /> 
                                <label class="mx-auto">Value (€) {{currentUser.user_type === 'V' ? balance + ' €' : ''}}</label> 
                                <ErrorMessage name="value" class="error-feedback" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" v-if="currentUser.user_type === 'V'">
                        <div class="col-12">
                            <div class="input-group"> 
                                <Field class="card" type="text" name="description" placeholder="Vacations Hotel" title="Description" /> <label>Description</label>
                                <ErrorMessage name="description" class="error-feedback" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12"> <button class="btn btn-pay placeicon" >Pay (EUR)</button> </div>
                    </div>
                </Form>
            </div>
            <div class="d-flex justify-content-center p-5" v-if="(loadingDependencies && !showConfirmationCode)">
                <div class="spinner-grow text-info" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <confirmation-code v-show="(!loadingDependencies && showConfirmationCode)" @success="handleConfirmationCode" :forceConfirmationCode="true" style="width: 100%;"></confirmation-code>
    </div>
</div>
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import ConfirmationCode from "../auth/ConfirmationCode.vue"

import PaymentTypeService from "../../services/payment-type.service";
import CategoryService from "../../services/category.service";
import TransactionService from "../../services/transaction.service";

import Utils from "../../utils/utils"

export default {
    name: "CreateTransaction",
    components: {
        Form,
        Field,
        ErrorMessage,
        ConfirmationCode,
    },
    data() {
        const schema = yup.object().shape({
            vcard: yup
                .string(),
            payment_reference: yup.string().required("Payment Reference is required!"),
            payment_type: yup.string().required("Payment Type is required!"),
            value: yup.string().required("Value is required!").min(0.01),
        });

        return {
            loadingDependencies: false,
            showConfirmationCode: false,
            loading: false,
            messageCreate: "",
            errors: {},
            schema,
            paymentTypes: [],
            categories: [],
            category: '',
            payment_type: "",
            transaction: {},
            balance: 0,
            value: 0
        };
    },
    computed: {
        contentShow(){
            if (this.currentUser) {
                return this.currentUser && this.currentUser.username; 
            }
            return true;
        },
        currentUser() {
            return this.$store.state.user.data;
        },
    },
    methods: {
        handleConfirmationCode(user){
            this.messageCreate = "";
            if (user.confirmationCode === true) {
                this.loadingDependencies = true;
                this.showConfirmationCode = false;
                this.postTransaction()
            }else{
                this.showConfirmationCode = false;
            }
        },
        postTransaction(){
            this.transaction["type"] = this.currentUser.user_type == 'A' ? "C":"D";
            TransactionService.postTransaction(this.transaction).then(
                (transaction) => {
                    this.$socket.emit('newTransaction', transaction.data.data)
                    this.$toast.success(`Transaction done with success.`, {autoHideDelay: 2000, appendToast: true})
                    this.$store.dispatch("user/setBalance", transaction.data.data.new_balance) 
                    this.$router.push("/transactions")
                    // this.$store.dispatch("user/getMe", '?balance=true').then(
                    // () => {
                    //     this.$router.push("/transactions");
                    // },
                    // () => {
                    //     this.$router.push("/logout");
                    // })
                },
                (error) => {
                    this.loadingDependencies = false;
                    this.errors = error.response.data.errors;
                    this.messageCreate =
                    (error.response &&
                        error.response.data &&
                        error.response.data.errors["value"] &&
                        error.response.data.errors["value"][0]) ||
                    error.message ||
                    error.toString();
                    this.$toast.error(`Transaction done without success.`, {autoHideDelay: 2000, appendToast: true}) 
                }
            );
        },
        handleCreate(transaction) {
            transaction.category = this.transaction.category;
            this.transaction = transaction;
            if (this.currentUser.user_type === 'V') {
                this.showConfirmationCode = true;
            } else {
                this.postTransaction()
            }
        },
        getImage(json){
            let obj = JSON.parse(json);
            return obj != null ? obj.image : "";
        },
        titleCase(str){
            return Utils.titleCase(str);
        },
    },
    async mounted() {
        this.loadingDependencies = true;
        this.balance = this.currentUser.balance
        await PaymentTypeService.getPaymentType().then(
            ({data}) => {
                if (this.currentUser.user_type == 'V') {
                    this.paymentTypes = data.data;
                    this.payment_type = "VCARD";
                } else {
                    this.paymentTypes = data.data.filter((e)=>e.code!=='VCARD');
                    this.payment_type = data.data[0].code;
                }
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
        if (this.currentUser.user_type === 'V')
        await CategoryService.getCategoryBoard(this.currentUser.username,`vcards/${this.currentUser.username}/categories?type=D`).then(
            ({data}) => {
                this.categories = data.data;
                this.transaction.category = "";
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
    watch: {
        value(newVal){
            this.balance = this.currentUser["balance"] - newVal;
        },
    }
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