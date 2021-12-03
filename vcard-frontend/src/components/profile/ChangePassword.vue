<template>
    <div class="container">
        <div v-if="isUserVisible">
            <section class="section about-section gray-bg" id="about">
                <h3 class="text-center pt-3">Change Password</h3>
                <div class="container pt-5">
                    <Form @submit="handleSave" :validation-schema="schema" class="w-75 mx-auto" method="PATCH" enctype="multipart/form-data">
                    <div v-if="!successful">
                        <div class="form-group">
                            <label for="password_old">Old Password</label>
                            <Field name="password_old" v-model="oldPassword" @change="oldPasswordChanged" type="password" class="form-control" />
                            <ErrorMessage name="password_old" class="error-feedback" />
                        </div>
                        <div class="form-group">
                            <label for="password_new">New Password</label>
                            <Field name="password_new" type="password" class="form-control" />
                            <ErrorMessage name="password_new" class="error-feedback" />
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                                <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                                <span> Guardar</span>
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
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import UserService from "../../services/user.service"

export default {
    name: "EditProfile",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    data() {
        const schema = yup.object().shape({
            password_old: yup
                .string()
                .required("Old Password is required!")
                .min(4, "Password must be at least 4 characters!")
                .max(40, "Password must be maximum 40 characters!"),
            password_new: yup
                .string()
                .required("New Password is required!")
                .min(4, "Password must be at least 4 characters!")
                .max(40, "Password must be maximum 40 characters!"),
        });
        return {
            successful: false,
            loading: false,
            message: "",
            schema,
            img_src: this.defaultImageProfileURL,
            errors: {},
            isUserVisible: false,
            user: {},
            oldPassword: '',
            regexPassword: '',
        };
    },
    async mounted() {
        this.user = await this.$store.dispatch('user/getMe');
        this.isUserVisible = true;
    },
    methods: {
        handleSave(user) {
            this.message = "";
            this.successful = false;
            this.loading = true;

            user["username"] = this.user["username"]

            UserService.updatePasswordUser(user).then(
                () => {
                    this.$toast.success(`Password updated successful.`, {autoHideDelay: 2000, appendToast: true}) 
                    this.$router.push({name: 'showProfile'})
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
                    this.$toast.error(`Password was not updated. ${this.message}`, {autoHideDelay: 2000, appendToast: true}) 
                    this.successful = false;
                    this.loading = false;
                }
            );
        },
        oldPasswordChanged() {
            this.regexPassword = '/^((?!'+this.oldPassword+').)*$/';
        }
    },
}
</script>

<style scoped>
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