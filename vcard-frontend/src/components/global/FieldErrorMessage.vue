<template>
  <div
    v-if="errors ? errors[fieldName] : false"
    class="validation-error"
  >
    {{ errors[fieldName][0] || '' }}
  </div>
</template>

<script>
export default {
  name: "FieldErrorMessage",
  props: {
    errors: {
      type: Object,
      default: null,
    },
    fieldName: {
      type: String,
      default: "",
    },
  },
};
</script>

<style scoped>
.validation-error {
  color: red;
  font-size: 0.85rem;
}
</style>