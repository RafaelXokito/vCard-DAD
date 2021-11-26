<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Transactions</h1>
    <h4>Total: {{totalTransactions}}</h4>
  </div>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectPaymentType"
        class="form-label"
      >Filter by payment type:</label>
      <select
        class="form-select"
        id="selectPaymentType"
        v-model="filterByPaymentType"
      >
        <option value="-1">Any</option>
        <option value="0">IBAN</option>
        <option value="1">MB WAY</option>
        <option value="2">VCARD</option>
        <option value="3">...</option>
      </select>
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectType"
        class="form-label"
      >Filter by type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
      >
        <option value="-1">Any</option>
        <option value="0">Credit</option>
        <option value="1">Debit</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <router-link
        class="btn btn-success px-4 btn-addtask"
        to="/transactions/create"
      ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Transaction</router-link>
    </div>
  </div>
  <transactions-table
    :transactions="transactions"
    :showId="false"
    @edit="editTransaction"
    @list="list"
  ></transactions-table>
</div>
</template>

<script>
import TransactionService from "../../services/transaction.service"
import TransactionsTable from "./TransactionsTable.vue"

export default {
  name: "Transactions",
  components: {
    TransactionsTable
  },
  data() {
    return {
      content: "",
      transactions: [],
      filterByPaymentType: -1,
      filterByType: -1
    };
  },
  computed: {
    totalTransactions () {
      return this.transactions.length
    }
  },
  methods: {
    editTransaction(transaction){
      console.log(transaction);
    },
    list(link){
        TransactionService.getTransactionBoard(link).then(
            ({data}) => {
                this.transactions = data;
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
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addtask {
  margin-top: 1.85rem;
}
</style>