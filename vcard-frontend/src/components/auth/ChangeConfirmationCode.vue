<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="changeCode"
  >
    <h3 class="mt-5 mb-3">Change Confirmation Code</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputCurrentCode"
          class="form-label"
        >Current Confirmation Code</label>
        <input
          type="password"
          class="form-control"
          id="inputCurrentCode"
          required
          v-model="codes.current_code"
        >
        <field-error-message
          :errors="errors"
          fieldName="current_code"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputCode"
          class="form-label"
        >New Confirmation Code</label>
        <input
          type="password"
          class="form-control"
          id="inputCode"
          required
          v-model="codes.code"
        >
        <field-error-message
          :errors="errors"
          fieldName="code"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputCodeConfirm"
          class="form-label"
        >Confirmation Code Confirmation</label>
        <input
          type="password"
          class="form-control"
          id="inputCodeConfirm"
          required
          v-model="codes.code_confirm"
        >
        <field-error-message
          :errors="errors"
          fieldName="code_confirm"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="changeCode"
      >Change Confirmation Code</button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'ChangeConfirmationCode',
  data () {
    return {
      codes: {
        current_code: '',
        code: '',
        code_confirm: ''
      },
      errors: null,
    }
  },
  emits: [
    'changedCode',
  ],
  methods: {
    changeCode () {
      // FALTA FAZER O LOGIN
      this.$emit('changedCode')
    },
  }
}
</script>

<style scoped>
</style>
