<template>
    <div class="container">
        <div v-if="isUserVisible">
            <section class="section about-section gray-bg" id="about">
                <h3 class="text-center pt-3">Create Administrator</h3>
                <div class="container pt-5">
                    <Form @submit="handleSave" :validation-schema="schema" class="w-75 mx-auto" method="PATCH" enctype="multipart/form-data">
                    <div v-if="!successful">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" />
                            <ErrorMessage name="name" class="error-feedback" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control" />
                            <ErrorMessage name="email" class="error-feedback" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <Field name="password" type="password" class="form-control" />
                            <ErrorMessage name="password" class="error-feedback" />
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmation">Confirm Password</label>
                            <Field name="passwordConfirmation" type="password" class="form-control" />
                            <ErrorMessage name="passwordConfirmation" class="error-feedback" />
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                                <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                                <span> Guardar</span>
                            </button>
                        </div>
                        <div class="form-group" v-if="message">
                            <ul class="alert alert-danger" role="alert">
                                <li v-for="(value, key) in errors" :key="key">
                                    {{value[0]}}
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
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import AdminService from "../../services/admin.service"

export default {
    name: "CreateAdmin",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    data() {
        const schema = yup.object().shape({
        name: yup
            .string()
            .required("Name is required!")
            .min(2, "Name must be at least 2 characters!")
            .max(50, "Name must be maximum 50 characters!"),
        email: yup
            .string()
            .required("Email is required!")
            .email("Email is invalid!")
            .max(50, "Must be maximum 50 characters!"),
        password: yup
            .string()
            .required("Password is required!")
            .min(4, "Password must be at least 4 characters!")
            .max(40, "Password must be maximum 40 characters!"),
        passwordConfirmation: yup.string()
            .oneOf([yup.ref('password'), null], 'Passwords must match')
        });

        return {
            successful: false,
            loading: false,
            message: "",
            schema,
            errors: {},
            isUserVisible: false,
            photo_url: "",
        };
    },
    async mounted() {
        this.isUserVisible = true;
    },
    methods: {
        async handleSave(user) {
            this.message = "";
            this.successful = false;
            this.loading = true;

            AdminService.postAdmin(user).then(
                () => {
                    this.$router.push({name: 'users'})                
                },
                (error) => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                    this.message =
                        (error.response &&
                        error.response.data &&
                        error.response.data) ||
                        error.message ||
                        error.toString();
                    this.successful = false;
                    this.loading = false;
                }
            );
        },
    },
}
</script>

<style scoped>
.form-group{
    padding-top: 0.5rem;
}
.gray-bg {
    background-color: #f5f5f5;
}   
.btn{
	transition: all .5s ease;
	width: 70%;
	border-radius: 30px;
	color:#23a0ed;
	font-weight: 600;
	background-color: #fff;
	border: 1px solid #23a0ed;
	margin-top: 1.5em;
	margin-bottom: 1em;
}
.btn:hover, .btn:focus{
	background-color: #23a0ed;
	color:#fff;
}

.image-upload>input {
  display: none;
}

.rounded-circle {
  width: 120px; 
  cursor: pointer;
}
ul {
  list-style-type: none;
}
.error-feedback{
  color: #db3b3b;
  font-size: 12px;
}
</style>