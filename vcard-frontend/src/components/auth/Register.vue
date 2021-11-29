<template>
<div class="container">
  <div class="container-fluid">
  <div class="row main-content bg-success text-center">
    <div class="col-md-4 text-center company__info">
      <span class="company__logo"><img :src="this.logoImageURL"></span>
    </div>
    <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
      <div class="container-fluid">
        <div class="row p-3">
          <h2 class="w-75 mx-auto">Register</h2>
        </div>
        <div class="row">
          <Form @submit="handleRegister" :validation-schema="schema" class="w-75 mx-auto" method="POST" enctype="multipart/form-data">
            <div v-if="!successful">
              <div class="form-group image-upload">
                <label for="file-input">
                  <img class="rounded-circle" :src="img_src">
                </label>
                <Field name="photo_url" id="file-input" @change="onFileChange" type="file" />
              </div>
              <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <Field name="phone_number" type="text" class="form-control" />
                <ErrorMessage name="phone_number" class="error-feedback" />
              </div>
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
                <label for="confirmation_code">Confirmation Code</label>
                <Field name="confirmation_code" type="password" class="form-control" />
                <ErrorMessage name="confirmation_code" class="error-feedback" />
              </div>

              <div class="form-group">
                <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                  <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                  <span> Sign Up</span>
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
        <div class="row">
          <p>Already have a account? <router-link to="login"><a>Login Here</a></router-link></p>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

export default {
  name: "Register",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      phone_number: yup
        .string()
        .required("Phone Number is required!")
        .matches(/^([9][1236])[0-9]*$/, "Phone Number must be a portuguese phone number!"),
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
      confirmation_code: yup
        .number()
        .typeError("Confirmation Code must be numbers")
        .required("Confirmation Code is required!")
        .test('len', 'Confirmation Code must be exactly 4 numbers', val => (val+ "").length === 4),
    });
    return {
      successful: false,
      loading: false,
      message: "",
      schema,
      img_src: this.defaultImageProfileURL,
      errors: {},
      photo_url: ""
    };
  },
  computed: {
    loggedIn() {
      return this.$store.state.auth.status.loggedIn;
    },
  },
  mounted() {
    if (this.loggedIn) {
      this.$router.push("/profile");
    }
  },
  methods: {
    handleRegister(user) {
      this.message = "";
      this.successful = false;
      this.loading = true;

      user["photo_url"] = this.photo_url
      user["username"] = user["phone_number"]

      var form_data = new FormData();

      for ( var key in user ) {
          form_data.append(key, user[key]);
      }

      this.$store.dispatch("auth/register", form_data).then(
        () => {
            this.$router.push({name: "confirmationPhoneNumber", params: {phoneNumber: user["phone_number"] }});
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
};
</script>

<style scoped>
.main-content{
	width: 100%;
	border-radius: 20px;
	box-shadow: 0 5px 5px rgba(0,0,0,.4);
	margin: 5em auto;
	display: flex;
}
.company__logo>img{
  width: 70%;
}
.company__info{
	background-color: #e7f0fe;
	border-top-left-radius: 20px;
	border-bottom-left-radius: 20px;
	display: flex;
	flex-direction: column;
	justify-content: center;
	color: #fff;
}
.fa-android{
	font-size:3em;
}
@media screen and (max-width: 640px) {
	.main-content{width: 90%;}
	.company__info{
		display: none;
	}
	.login_form{
		border-top-left-radius:20px;
		border-bottom-left-radius:20px;
	}
}
@media screen and (min-width: 641px) and (max-width:800px){
	.main-content{width: 70%;}
  .company__info{
		border-top-right-radius:20px;
		border-bottom-right-radius:0px;
		border-bottom-left-radius:0px;
	}
  .login_form{
		border-top-left-radius:0px;
    border-top-right-radius:0px !important;
		border-bottom-left-radius:20px;
	}
}
.row > h2{
	color:#23a0ed;
}
.login_form{
	background-color: #fff;
	border-top-right-radius:20px;
	border-bottom-right-radius:20px;
	border-top:1px solid #ccc;
	border-right:1px solid #ccc;
}
form{
	padding: 0 2em;
}
.form__input{
	width: 100%;
	border:0px solid transparent;
	border-radius: 0;
	border-bottom: 1px solid #aaa;
	padding: 1em .5em .5em;
	padding-left: 2em;
	outline:none;
	margin:1.5em auto;
	transition: all .5s ease;
}
.form__input:focus{
	border-bottom-color: #e7f0fe;
	box-shadow: 0 0 5px rgba(0,80,80,.4); 
	border-radius: 4px;
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