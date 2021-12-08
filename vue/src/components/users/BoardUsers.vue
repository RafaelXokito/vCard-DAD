<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Users</h1>
  </div>
  <div class="container">
    <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
    @list="list"
    @block="blockVcard"
    @maxDebit="maxDebitVcard"
    @delete="deleteUser"
  ></user-table>
  </div>
  <confirm-dialog></confirm-dialog>
  <Dialog :modal="true" :visible="showFormDialog" class="p-confirm-dialog" @update:visible="showFormDialog = false" :closeOnEscape="true">
    <template #header>
      <font-awesome-icon :icon="['fas', 'credit-card']" size="lg" />
      <h3 class="text-center">Update Max Debit</h3>
    </template>
    <Form @submit="handleChangeMaxDebit" :validation-schema="schema" class="form-card" id="changeMaxDebitForm" name="changeMaxDebitForm">
      <div class="row justify-content-center">
          <h5 class="p-confirm-dialog-message text-center"> {{currentUserMaxDebit.name}} </h5>
          <div class="pt-3">
              <div class="input-group text-center">
                  <label class="mx-auto col-12">Max Debit (€)</label>
                  <Field class="mx-auto card col-12" v-model="currentUserMaxDebit.max_debit" @change="setTwoDecimalPlaces" type="number" name="max_debit" placeholder="0.00" min="0.00" :value="currentUserMaxDebit.max_debit" step="0.01" title="Currency" :rules="{regex: /^[0-9]+(\.[0-9]{1,2})?$/}" /> 
                  <ErrorMessage name="max_debit" class="error-feedback" />
              </div>
          </div>
      </div>
    </Form>
    <template #footer>
        <Button label="No" icon="pi pi-times" class="p-button-text" @click="closeDialogChangeMaxDebit" autofocus/>
        <Button form="changeMaxDebitForm" :disabled="this.old_maxDebit == currentUserMaxDebit.max_debit || currentUserMaxDebit.max_debit <= 0" type="Submit" label="Yes" :icon="loadingUserMaxDebit" />
    </template>
  </Dialog>
</div>
</template>

<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";

import UserService from "../../services/user.service";
import VCardService from "../../services/vcard.service";

import ConfirmDialog from 'primevue/confirmdialog';

import Dialog from 'primevue/dialog';
import Button from 'primevue/button';

import UserTable from "./UsersTable.vue"

export default {
  name: "Users",
  components: {
    UserTable,
    ConfirmDialog,
    Dialog,
    Button,
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
        max_debit: yup.string().required("Max Debit is required!").matches(/^[0-9]+(\.[0-9]{1,2})?$/, "Maximum debit must contain 2 decimal places"),
    });
    return {
      content: "",
      users: [],
      schema,
      showFormDialog: false,
      currentUserMaxDebit: {},
      old_maxDebit: 0,
      loadingUserMaxDebit: 'pi pi-check',
      lastLinkedUsed: ""
    };
  },
  computed: {
    totalUsers () {
      return this.users.length
    }
  },
  methods: {
    editUser(user){
      console.log(user);
    },
    async blockVcard(userIndex){
      let user = this.users.data[userIndex];

      let blockedState = user.blocked;
      let message = `Do you really want to  the ${user.user_type === 'A' ? 'admin' : 'vCard'} ${user.name} [${user.username}]`

      this.$confirm.require({
          message: message,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          accept: async () => {
              this.users.data[userIndex].blocked = 'loading'
              await VCardService.blockVcard(user).then(()=>{
                this.users.data[userIndex].blocked = !blockedState
                if (this.users.data[userIndex].blocked == '1') {
                  this.$socket.emit('vcardBlocked', {to: this.users.data[userIndex].id, from: this.$store.state.user.data.id})
                }
                this.$toast.success(`VCard ${user.name} was ${blockedState ? 'unblocked' : 'block'} successful.`, {autoHideDelay: 2000, appendToast: true}) 
              },
              (error) => {
                this.content =
                  (error.response &&
                      error.response.data &&
                      error.response.data.message) ||
                  error.message ||
                  error.toString();
                this.$toast.error(`During blocking ${user.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                this.users.data[userIndex].blocked = blockedState
              })
          },
          reject: () => {
            this.users.data[userIndex].blocked = blockedState
          }
      });
    },
    async deleteUser(userIndex){
      let user = this.users.data[userIndex];

      let deletedState = user.deleted;
      let message = `Do you really want to ${deletedState ? 'RESTORE' : 'DELETE'} the ${user.user_type === 'A' ? 'admin' : 'vCard'} ${user.name} [${user.username}]`

      this.$confirm.require({
          message: message,
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          accept: async () => {
              this.users.data[userIndex].deleted = 'loading'
              if (deletedState === 0) {
                await UserService.delete(user).then(()=>{
                  this.users.data[userIndex].deleted = !deletedState
                  this.$toast.success(`VCard ${user.name} deleted successful.`, {autoHideDelay: 2000, appendToast: true}) 
                  if (this.users.data[userIndex].deleted == '1') {
                    this.$socket.emit('userDeleted', {to: this.users.data[userIndex].id, from: this.$store.state.user.data.id})
                  }
                  this.list(this.lastLinkedUsed)
                },
                (error) => {
                  this.content =
                    (error.response &&
                        error.response.data &&
                        error.response.data.message) ||
                    error.message ||
                    error.toString();
                  this.$toast.error(`During deleting ${user.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                  this.users.data[userIndex].deleted = deletedState
                })
              } else {
                await VCardService.restoreVCard(user).then(()=>{
                  this.users.data[userIndex].deleted = !deletedState
                  this.$toast.success(`VCard ${user.name} restoring successful.`, {autoHideDelay: 2000, appendToast: true}) 
                  this.list(this.lastLinkedUsed)
                },
                (error) => {
                  this.content =
                    (error.response &&
                        error.response.data &&
                        error.response.data.message) ||
                    error.message ||
                    error.toString();
                  this.$toast.error(`During restoring ${user.name} ${this.content}.`, {autoHideDelay: 2000, appendToast: true}) 
                  this.users.data[userIndex].deleted = deletedState
                })
              }
              
          },
          reject: () => {
            this.users.data[userIndex].deleted = deletedState
          }
      });
    },
    async maxDebitVcard(userIndex){
      this.showFormDialog =true;
      this.old_maxDebit = this.users.data[userIndex].max_debit
      this.currentUserMaxDebit = this.users.data[userIndex];
      //this.currentUserMaxDebit.index = userIndex
    },
    async handleChangeMaxDebit(){
      this.loadingUserMaxDebit = 'spinner-border spinner-border-sm'
      this.currentUserMaxDebit.loading = true;
      await VCardService.changeMaxDebit(this.currentUserMaxDebit).then(
        ()=>{ 
          this.$toast.success(`Max Debit for ${this.currentUserMaxDebit.name} of ${this.currentUserMaxDebit.max_debit} saved.`, {autoHideDelay: 2000, appendToast: true}) 
        }, 
        ()=>{
          this.$toast.error(`Max Debit for ${this.currentUserMaxDebit.name} of ${this.currentUserMaxDebit.max_debit} was NOT saved saved.`, {autoHideDelay: 2000, appendToast: true}) 
          this.currentUserMaxDebit.max_debit = this.old_maxDebit; 
      });
      this.currentUserMaxDebit.loading = false;
      this.currentUserMaxDebit = null;
      this.showFormDialog = false;
      this.loadingUserMaxDebit = 'pi pi-check'
    },
    setTwoDecimalPlaces(){
      if (this.currentUserMaxDebit.max_debit) {
        this.currentUserMaxDebit.max_debit = parseFloat(this.currentUserMaxDebit.max_debit).toFixed(2);
      }
    },
    closeDialogChangeMaxDebit(){
      this.loadingUserMaxDebit = 'pi pi-check'
      this.currentUserMaxDebit = null;
      this.showFormDialog = false;
    },
    list(link){
        this.lastLinkedUsed = link;
        UserService.getUserBoard(link).then(
            ({data}) => {
              this.users = data;
            },
            (error) => {
                this.content =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
            }
        );
    }
  },
  mounted() {
    this.list();
  },
};
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.card {
    border-radius: 10px !important;
}

.form-card {
    margin-left: 20px;
    margin-right: 20px
}
.error-feedback{
  color: #db3b3b;
  font-size: 12px;
}
</style>