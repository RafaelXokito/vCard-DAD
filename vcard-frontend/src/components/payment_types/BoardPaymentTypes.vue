<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">PaymentTypes</h1>
  </div>
  <div class="container">
    <div v-if="!paymenttypeToCreateEditShow">
      <!--<router-view @edit="editPaymentType" @delete="deletePaymentType" :paymenttypes="paymenttypes" @list="list" :showEdit="true" @showCreate="showCreate"></router-view>-->
      
      <DataTable :value="paymentTypes" :paginator="true" :rows="10"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          :rowsPerPageOptions="[10,20,50]" responsiveLayout="scroll" sortMode="multiple" removableSort
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="loading"
          editMode="row" dataKey="code" :editingRows="editingRows" @row-edit-save="onRowEditSave"
          >
          <template #header>
                  <div class="table-header">
                      Payment Type 
                      <a class="btn btn-success px-4 btn-addtask" href="#" @click.prevent="showCreate"><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Payment Type</a>
                  </div>
          </template>
          <Column field="code" header="Code" :sortable="true">
            <template #editor="{ data, field }">
                <InputText v-model="data[field]" autofocus />
            </template>
          </Column>
          <Column field="name" header="Name" :sortable="true">
            <template #editor="{ data, field }">
                <InputText v-model="data[field]" autofocus />
            </template>
          </Column>
          <Column style="width:10%; min-width:8rem" bodyStyle="text-align:center">
            <template #body="slotProps">
              <Button class="p-link p-button-text" type="button" icon="pi pi-fw pi-pencil" @click="editPaymentType(slotProps)" />
            </template>
          </Column>
          <Column style="width:10%; min-width:8rem" bodyStyle="text-align:center">
            <template #body="slotProps">
              <Button class="p-link p-button-text" type="button" icon="pi pi-fw pi-trash" @click="deletePaymentType(slotProps)" />
            </template>
          </Column>
          <template #paginatorstart>
              <Button type="button" icon="pi pi-refresh" @click="list" class="p-button-text" />
          </template>
      </DataTable>
    </div>
    <div v-else-if="paymenttypeToCreateEditShow">
        <create-edit-payment-types :paymenttype="paymenttypeToCreateEdit" @close="closePaymentTypeCreateEdit" :isCreate="isCreate"></create-edit-payment-types>
    </div>
  </div>
</div>
</template>

<script>
import PaymentTypeService from "../../services/payment-type.service";
import CreateEditPaymentTypes from './CreateEditPaymentTypes.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

export default {
  components: { 
    CreateEditPaymentTypes,
    DataTable,
    Column,
    Button,
    InputText
  },
  name: "PaymentTypes",
  data() {
    return {
      content: "",
      loading: false,
      paymentTypes: [],
      messageCreate: "",
      paymenttypeToCreateEdit: {},
      paymenttypeToCreateEditShow: false,
      isCreate: true,
      editingRows: [],
    };
  },
  computed: {
    totalPaymentTypes () {
      return this.paymenttypes.length
    }
  },
  methods: {
    editPaymentType(paymenttype){
      this.isCreate = false
      this.paymenttypeToCreateEditShow = true;
      this.paymenttypeToCreateEdit = paymenttype.data;
    },
    showCreate(){
      this.paymenttypeToCreateEditShow = true;
    },
    deletePaymentType(paymenttype){
      if(confirm(`Do you really want to delete paymenttype ${paymenttype.name}?`)){
        PaymentTypeService.deletePaymentType(paymenttype).then(
            () => {
              alert("PaymentType Deleted")
              this.list();
            },
            (error) => {
                this.messageEdit =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
              alert(this.messageEdit)

            }
        );
      }
    },
    closePaymentTypeCreateEdit(){
      this.paymenttypeToCreateEditShow = false;
    },
    list(link){
        this.loading = true;
        PaymentTypeService.getPaymentTypeBoard(link).then(
          ({data}) => {
              this.paymentTypes = data.data;
              console.log(this.paymentTypes)
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
        this.loading = false;
    },
    onCellEditComplete(event) {
      let { data, newValue, field } = event;

      switch (field) {
          case 'quantity':
          case 'price':
              if (this.isPositiveInteger(newValue))
                  data[field] = newValue;
              else
                  event.preventDefault();
          break;

          default:
              if (newValue.trim().length > 0)
                  data[field] = newValue;
              else
                  event.preventDefault();
          break;
      }
    },
    onRowEditSave(event) {
      let { newData, index } = event;

      this.paymentTypes[index] = newData;
    },
  },
  mounted() {
    this.list();
  },
};
</script>

<style scoped>
.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.editable-cells-table td.p-cell-editing {
  padding-top: 0;
  padding-bottom: 0;
}
</style>