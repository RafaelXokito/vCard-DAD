<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="changePassword"
  >
    <h3 class="mt-5 mb-3">Change Password</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputCurrentPassword"
          class="form-label"
        >Current Password</label>
        <input
          type="password"
          class="form-control"
          id="inputCurrentPassword"
          required
          v-model="passwords.current_password"
        >
        <field-error-message
          :errors="errors"
          fieldName="current_password"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPassword"
          class="form-label"
        >New Password</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="passwords.password"
        >
        <field-error-message
          :errors="errors"
          fieldName="password"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPasswordConfirm"
          class="form-label"
        >Password Confirmmation</label>
        <input
          type="password"
          class="form-control"
          id="inputPasswordConfirm"
          required
          v-model="passwords.password_confirm"
        >
        <field-error-message
          :errors="errors"
          fieldName="password_confirm"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="changePassword"
      >Change Password</button>
    </div>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ChangePassword',
  data () {
    return {
      passwords: {
        current_password: '',
        password: '',
        password_confirm: ''
      },
      errors: null,
    }
  },
  computed: {
    currentUser() {
      return this.$store.state.user.data;
    },
  },
  emits: [
    'changedPassword',
  ],
  methods: {
    changePassword() {
      const response = axios.patch('users/900000001/password', {
        password: this.password,
        password_confirm: this.password_confirm
      });
      console.log(response)
      this.$emit('changedPassword')
    },
  }
}
</script>

<style scoped>
</style>
