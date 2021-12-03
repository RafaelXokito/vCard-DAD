<template>
<div>
  <div v-if="!transactionToEditShow">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Transactions</h1>
      <h4>Total: {{totalTransactions}}</h4>
    </div>
    <transactions-table
      :transactions="transactions"
      :showId="false"
      :showEdit="currentUser.user_type === 'V'"
      @edit="editTransaction"
      @list="list"
    ></transactions-table>
  </div>
  <edit-transaction v-else-if="transactionToEditShow" :transaction="transactionToEdit" @close="closeTransactionEdit"></edit-transaction>
</div>
</template>

<script>
import TransactionService from "../../services/transaction.service"
import TransactionsTable from "./TransactionsTable.vue"
import EditTransaction from './EditTransaction.vue'

export default {
  name: "Transactions",
  components: {
    TransactionsTable,
    EditTransaction,
  },
  data() {
    return {
      content: "",
      transactions: [],
      transactionToEdit: {},
      transactionToEditShow: false,
      filterByPaymentType: -1,
      filterByType: -1
    };
  },
  computed: {
    currentUser (){
      return this.$store.state.user.data ?? {}
    },
    totalTransactions () {
      return this.transactions.meta && this.transactions.meta.total ? this.transactions.meta.total : 0
    }
  },
  methods: {
    editTransaction(transaction){
      this.transactionToEditShow = true;
      this.transactionToEdit = transaction;
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
    },
    closeTransactionEdit(){
      this.transactionToEditShow = false;
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