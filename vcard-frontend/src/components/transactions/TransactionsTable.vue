<template>
<div>
  <div v-if="isTableVisible">
    <div class="mb-3 d-flex justify-content-between flex-wrap">
      <div class="mx-2 mt-2 filter-div flex-grow-1">
        <label
          for="selectPaymentType"
          class="form-label"
        >Filter by payment type:</label>
        <select
          class="form-select"
          id="selectPaymentType"
          v-model.lazy="filterByPaymentType"
        >
          <option value="">Any</option>
          <option :value="paymentType.code" v-for="paymentType in paymentTypes" :key="paymentType.code">{{paymentType.name}}</option>
        </select>
      </div>
      <div class="mx-2 mt-2 filter-div">
        <label
          for="selectType"
          class="form-label"
        >Filter by type:</label>
        <select
          class="form-select"
          id="selectType"
          v-model.lazy="filterByType"
        >
          <option value="">Any</option>
          <option value="C">Credit</option>
          <option value="D">Debit</option>
        </select>
      </div>
      <div class="mx-2 mt-2 filter-div">
        <label
          class="form-label"
        >Filter by date:</label>
        <div class="row">
          <div class="col-5" style="padding-right: 0px;"><Calendar :showButtonBar="true" :maxDate="filterByEndStateDate" v-model.lazy="filterByStartDate" selectionMode="single" :touchUI="true" dateFormat="yy-M-dd"></Calendar></div>
          <div class="col-2 text-center mt-1"><span class="align-middle">-</span></div>
          <div class="col-5" style="padding-left: 0px;"><Calendar :showButtonBar="true" :minDate="filterByStartStateDate" :maxDate="tomorrow" v-model.lazy="filterByEndDate" selectionMode="single" :touchUI="true" dateFormat="yy-M-dd"></Calendar></div>
        </div>
      </div>
      <div class="mx-2 mt-2">
        <router-link
          class="btn btn-success px-4 btn-addtask"
          to="/transactions/create"
        ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Transaction</router-link>
      </div>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th
            v-if="showId"
            class="align-middle"
          >#</th>
          <th class="align-middle">Old Balance</th>
          <th class="align-middle">New Balance</th>
          <th class="align-middle">Payment Type</th>
          <th class="align-middle">Type</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="transaction in transactions.data"
          :key="transaction.id"
          :class="transaction.transaction_type == 'A' ? 'table-success' : ''"
        >
          <td
            v-if="showId"
            class="align-middle"
          >{{ transaction.id }}</td>
          <td class="align-middle">{{ transaction.old_balance }}</td>
          <td class="align-middle">{{ transaction.new_balance }}</td>
          <td class="align-middle" v-html="highlightPaymentTypeMatches(transaction.payment_type)"></td>
          <td class="align-middle" v-html="highlightTypeMatches(transaction.type == 'C' ? 'Credit' : 'Debit')"></td>
          <td
            class="text-end align-middle" v-if="showEdit"
          >
            <div class="d-flex justify-content-end">
              <button class="btn btn-xs btn-warning" @click="editClick(transaction)">
                <font-awesome-icon :icon="['fas', 'pen']" size="xs" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <pagination class="mx-auto" align="center" :data="transactions" @pagination-change-page="list"></pagination>
    </div>
    
  </div>
  <div class="d-flex justify-content-center p-5" v-else>
      <div class="spinner-grow text-info" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
      </div>
  </div>
</div>
</template>

<script>
import Pagination from '../global/Pagination.vue'
import Calendar from 'primevue/calendar';

import PaymentTypeService from '../../services/payment-type.service'

export default {
  name: "TransactionsTable",
  components: {
      //JwPagination
      Pagination,
      Calendar
  },
  props: {
    transactions: {
      type: Object,
      default: () => {},
    },
    showId: {
      type: Boolean,
      default: true,
    },
    showEmail: {
      type: Boolean,
      default: true,
    },
    showEdit: {
      type: Boolean,
      default: false,
    },
    showAdmin: {
      type: Boolean,
      default: true,
    },
    showGender: {
      type: Boolean,
      default: false,
    },
    showPhoto: {
      type: Boolean,
      default: true,
    },
    showEditButton: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    filterByEndStateDate(){
      return this.filterByEndDate != "" ? this.filterByEndDate : this.tomorrow;
    },
    filterByStartStateDate(){
      return this.filterByStartDate != "" ? this.filterByStartDate : new Date('1900-01-01');
    }
  },
  emits: [
    'edit',
    'list'
  ],
  data() {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate()+1)
      return {
          isTableVisible: false,
          filterByPaymentType: "",
          filterByType: "",
          filterByStartDate: "",
          filterByEndDate: "",
          paymentTypes: [],
          tomorrow,
          optionsFilter: "",
      }
  },
  async mounted() {
    await PaymentTypeService.getPaymentType().then(
        ({data}) => {
            this.paymentTypes = data.data;
        },
        (error) => {
            this.messageCreate =
            (error.response &&
                error.response.data &&
                error.response.data.message) ||
            error.message ||
            error.toString();
        }
    );
    this.list()
  },
  methods: {
    editClick (transaction) {
      this.$emit('edit', transaction)
    },
    async list(link){
        await this.$emit('list', this.optionsfilter ? link+this.optionsfilter : link)
    },
    highlightPaymentTypeMatches(text) {
      const matchExists = text
        .toLowerCase()
        .includes(this.filterByPaymentType.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(this.filterByPaymentType, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    },
    highlightTypeMatches(text) {
      let filter = this.filterByType != "" ? (this.filterByType == 'C' ? 'Credit' : 'Debit') : ""
      const matchExists = text
        .toLowerCase()
        .includes(filter.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(filter, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    }
  },
  watch:{
    transactions(newVal){
      if (newVal) {
        this.isTableVisible = true;
      }
    },
    async filterByType(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (this.filterByPaymentType) {
        this.optionsfilter += '&payment_type='+this.filterByPaymentType
      }
      if (this.filterByEndDate) {
        this.optionsfilter += '&end_date='+(new Date(this.filterByEndDate)).toISOString().split('T')[0]
      }
      if (this.filterByStartDate) {
        this.optionsfilter += '&start_date='+(new Date(this.filterByStartDate)).toISOString().split('T')[0]
      }
      if (newVal) {
        this.optionsfilter += '&type='+newVal
      }
      await this.$emit('list', this.transactions.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    async filterByPaymentType(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (newVal) {
        this.optionsfilter += '&payment_type='+newVal
      }
      if (this.filterByEndDate) {
        this.optionsfilter += '&end_date='+(new Date(this.filterByEndDate)).toISOString().split('T')[0]
      }
      if (this.filterByStartDate) {
        this.optionsfilter += '&start_date='+(new Date(this.filterByStartDate)).toISOString().split('T')[0]
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      await this.$emit('list', this.transactions.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    async filterByStartDate(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (this.filterByEndDate) {
        this.optionsfilter += '&end_date='+(new Date(this.filterByEndDate)).toISOString().split('T')[0]
      }
      if (newVal) {
        this.optionsfilter += '&start_date='+(new Date(newVal)).toISOString().split('T')[0]
      }
      if (this.filterByPaymentType) {
        this.optionsfilter += '&payment_type='+this.filterByPaymentType
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      await this.$emit('list', this.transactions.links.first + this.optionsfilter)
      this.isTableVisible = true
    },

    async filterByEndDate(newVal){
      this.isTableVisible = false
      this.optionsfilter = ""
      if (newVal) {
        this.optionsfilter += '&end_date='+(new Date(newVal)).toISOString().split('T')[0]
      }
      if (this.filterByStartDate) {
        this.optionsfilter += '&start_date='+(new Date(this.filterByStartDate)).toISOString().split('T')[0]
      }
      if (this.filterByPaymentType) {
        this.optionsfilter += '&payment_type='+this.filterByPaymentType
      }
      if (this.filterByType) {
        this.optionsfilter += '&type='+this.filterByType
      }
      await this.$emit('list', this.transactions.links.first + this.optionsfilter)
      this.isTableVisible = true
    },
    sockets: {
      newTransaction () {
        this.$emit('list', this.transactions.links.first + this.optionsfilter)
      }
    },
  },
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addtask {
  margin-top: 1.85rem;
}
</style>
