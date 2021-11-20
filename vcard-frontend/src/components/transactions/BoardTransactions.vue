<template>
  <div class="container">
    <transactions-table
    :transactions="transactions"
    :showId="false"
    @edit="editTransaction"
    @list="list"
  ></transactions-table>
  </div>
</template>

<script>
import TransactionService from "../../services/transaction.service";

import TransactionsTable from "./TransactionsTable.vue"

export default {
  name: "Transactions",
  components: {
    TransactionsTable
  },
  data() {
    return {
      content: "",
      transactions: []
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