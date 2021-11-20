<template> 
<div class="container">
  <div class="container-fluid">
  <div class="row main-content bg-success text-center">
    <div class="col-md-4 text-center company__info">
      <span class="company__logo"><img :src="this.logoImageURL"></span>
    </div>
    <div class="col-md-8 col-xs-12 col-sm-12 confirmationCode_form ">
      <div class="container-fluid">
        <button type="button" class="close" @click="close" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row p-3">
          <h2 class="w-75 mx-auto">Confirmation Code</h2>
        </div>
        <div class="row">
          <Form @submit="handleConfirmationCode" :validation-schema="schema" class="w-75 mx-auto">
            <div class="form-group">
              <Field name="confirmationCode" type="password" class="form-control" />
              <ErrorMessage name="confirmationCode" class="error-feedback" />
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

export default {
  name: "ConfirmationCode",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      confirmationCode: yup.string().required("Confirmation Code is required!"),
    });

    return {
      loading: false,
      message: "",
      schema,
    };
  },
  
  created() {
    if (this.confirmationCode) {
      this.$router.go(-1);
    }
  },
  props: {
    forceConfirmationCode: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    confirmationCode() {
      return this.forceConfirmationCode ? '' : this.$store.state.auth.user.confirmationCode ?? this.$store.state.auth.user.user_type == 'A';
    },
  },
  emits: [
    'success',
  ],
  methods: {
    handleConfirmationCode(user) {
        this.loading = true;
        this.message = "";
        user["id"] = this.$store.state.auth.user.id;
        this.$store.dispatch("auth/confirmationCode", user).then(
            () => {
                this.$store.dispatch("auth/updateVCardBalance", user).then(() => {
                  this.loading = false;
                  this.close(true);
                },
                (error) => {
                  this.loading = false;
                  this.message =
                      (error.response &&
                      error.response.data &&
                      error.response.data.message) ||
                      error.message ||
                      error.toString();
                })
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
    close(bool=false){
      if (this.forceConfirmationCode) {
        this.$emit('success', {"confirmationCode": bool})
      } else {
        this.$router.go(-1);
      }
    }
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
	.confirmationCode_form{
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
.confirmationCode_form{
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