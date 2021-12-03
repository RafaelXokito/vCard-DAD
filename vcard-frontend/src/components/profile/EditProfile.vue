<template>
    <div class="container">
        <div v-if="isUserVisible">
            <section class="section about-section gray-bg" id="about">
                <h3 class="text-center pt-3">Edit Profile</h3>
                <div class="container pt-5">
                    <Form @submit="handleSave" :validation-schema="schema" class="w-75 mx-auto" method="PATCH" enctype="multipart/form-data">
                    <div v-if="!successful">
                        <div class="form-group image-upload text-center" v-if="user.user_type === 'V'">
                            <label for="file-input">
                            <img class="rounded-circle" :src="img_src" :title="user.username+'Photo'">
                            </label>
                            <Field name="photo_url" id="file-input" @change="onFileChange" type="file" />
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" :value="user['name']" class="form-control" />
                            <ErrorMessage name="name" class="error-feedback" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" :value="user['email']" class="form-control" />
                            <ErrorMessage name="email" class="error-feedback" />
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
            photo_url: "",
        };
    },
    async mounted() {
        this.user = await this.$store.dispatch('auth/getMe');
        this.img_src = this.baseURL + this.user.photo_url;
        this.isUserVisible = true;
    },
    methods: {
        async handleSave(user) {
            this.message = "";
            this.successful = false;
            this.loading = true;

            user["photo_url"] = this.photo_url
            user["username"] = this.user["id"] ?? this.user["username"]

            console.log(user["photo_url"])

            var form_data = new FormData();

            for ( var key in user ) {
                form_data.append(key, user[key]);
            }

            UserService.updateUser(user).then(
                () => {
                    if (user["photo_url"] && user.user_type === 'V'){
                        UserService.updateVCardPhoto(form_data).then(
                            () => { 
                                this.$toast.success(`Profile was updated successful.`, {autoHideDelay: 2000, appendToast: true}) 
                                this.$router.push({name: 'showProfile'})
                            },
                            (error) => {
                                this.errors = error.response.data.errors;
                                this.message =
                                    (error.response &&
                                    error.response.data &&
                                    error.response.data) ||
                                    error.message ||
                                    error.toString();
                                this.successful = false;
                                this.$toast.error(`Profile was not updated.`, {autoHideDelay: 2000, appendToast: true}) 
                                this.loading = false;
                        })
                    }else {
                        this.$toast.success(`Profile was updated successful.`, {autoHideDelay: 2000, appendToast: true}) 
                        this.$router.push({name: 'showProfile'})
                    }
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
        onFileChange(e) {
            var files = e.target.files;
            if (!files.length){
                return;
            }
            this.photo_url = files[0];
            const file = e.target.files[0];
            this.img_src = URL.createObjectURL(file);
        },
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