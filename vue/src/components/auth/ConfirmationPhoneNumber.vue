<template> 
<div class="container">
  <div class="container-fluid">
  <div class="row main-content bg-success text-center">
    <div class="col-md-4 text-center company__info">
      <span class="company__logo"><img src="./../../assets/images/logo.png"></span>
    </div>
    <div class="col-md-8 col-xs-12 col-sm-12 confirmationPhoneNumber_form ">
      <div class="container-fluid">
        <button type="button" class="close" @click="close" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row p-3">
          <h2 class="w-75 mx-auto">Confirm Phone Number</h2>
          <h6 class="w-75 mx-auto">Check your phone number and insert the validation code that you recieved</h6>
        </div>
        <div class="row">
          <Form @submit="handleConfirmationPhoneNumber" :validation-schema="schema" class="w-75 mx-auto">
            <div class="form-group">
              <Field name="code" type="text" class="form-control" />
              <ErrorMessage name="code" class="error-feedback" />
            </div>

            <div class="form-group">
              <button class="btn btn-primary btn-block mx-auto" :disabled="loading">
                <span
                  v-show="loading"
                  class="spinner-border spinner-border-sm"
                ></span>
                <span> Ok</span>
              </button>
            </div>

            <div class="form-group">
              <div v-if="message" class="alert alert-danger" role="alert">
                {{ message }}
              </div>
            </div>
          </Form>
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

import VCardService from "../../services/vcard.service"

export default {
  name: "ConfirmationPhoneNumber",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      code: yup.string().required("Confirmation Phone Number is required!"),
    });

    return {
      loading: false,
      message: "",
      schema,
      phoneNumber: this.$route.params.phoneNumber
    };
  },
  props: {
    forceConfirmationPhoneNumber: {
      type: Boolean,
      default: false,
    },
  },
  emits: [
    'success',
  ],
  created() {
    this.loading = true;
    this.message = "";
    let user = {}
    user["phoneNumber"] = this.$route.params.phoneNumber
    VCardService.makeConfirmationPhoneNumber(user).then(
        () => {
          this.loading = false;
        },
        (error) => {
        this.loading = false;
        this.message =
            (error.response &&
            error.response.data &&
            error.response.data.message) ||
            error.message ||
            error.toString();
        }
    );
  },
  beforeUnmount() {
    let user = {};
    user["phoneNumber"] = this.phoneNumber
    VCardService.closeConfirmationPhoneNumber(user).then(
        () => {
          //console.log(e);
        },
        (error) => {
        this.loading = false;
        this.message =
            (error.response &&
            error.response.data &&
            error.response.data.message) ||
            error.message ||
            error.toString();
        }
    );
  },
  methods: {
    handleConfirmationPhoneNumber(user) {
        this.loading = true;
        this.message = "";
        user["phoneNumber"] = this.phoneNumber
        VCardService.verifyConfirmationPhoneNumber(user).then(
            () => {
                this.$router.push({name: 'login'});
            },
            (error) => {
            this.loading = false;
            this.message =
                (error.response &&
                error.response.data &&
                error.response.data.message) ||
                error.message ||
                error.toString();
            }
        );
    },
    close(){
      this.$router.push({name: 'login'});
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
	.confirmationPhoneNumber_form{
		border-top-left-radius:20px;
		border-bottom-left-radius:20px;
	}
}
@media screen and (min-width: 642px) and (max-width:800px){
	.main-content{width: 70%;}
}
.row > h2{
	color:#23a0ed;
}
.confirmationPhoneNumber_form{
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

.error-feedback{
  color: #db3b3b;
  font-size: 12px;
}
</style>