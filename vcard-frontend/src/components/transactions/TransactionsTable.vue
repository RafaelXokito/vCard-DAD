<template>
<div v-if="isTableVisible">
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
        <td class="align-middle">{{ transaction.payment_type }}</td>
        <td class="align-middle">{{ transaction.type == 'C' ? 'Credit' : 'Debit' }}</td>
        <td
          class="text-end align-middle"
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
</template>

<script>
import Pagination from '../global/Pagination.vue'

export default {

  name: "TransactionsTable",
  components: {
      //JwPagination
      Pagination
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
  emits: [
    'edit',
    'list'
  ],
  data() {
      return {
          isTableVisible: false,
      }
  },
  mounted() {
    this.list()
  },
  methods: {
    editClick (transaction) {
      this.$emit('edit', transaction)
    },
    async list(link){
        await this.$emit('list', link)
    },
  },
  watch:{
    transactions(newVal){
      if (newVal) {
        this.isTableVisible = true;
      }
    }
  }
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
</style>
