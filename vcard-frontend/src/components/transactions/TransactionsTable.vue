<template>
<div>
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
        <option value="">Any</option>
        <option value="IBAN">IBAN</option>
        <option value="MB WAY">MB WAY</option>
        <option value="VCARD">VCARD</option>
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
        <option value="">Any</option>
        <option value="Credit">Credit</option>
        <option value="Debit">Debit</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <router-link
        class="btn btn-success px-4 btn-addtask"
        to="/transactions/create"
      ><font-awesome-icon :icon="['fas', 'plus-circle']" size="lg" />&nbsp; Create Transaction</router-link>
    </div>
  </div>
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
          v-for="transaction in filteredRows"
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
</div>
</template>

<script>
import Pagination from '../global/Pagination.vue'

export default {

  name: "TransactionsTable",
  components: {
      //JwPagination
      Pagination,
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
  emits: [
    'edit',
    'list'
  ],
  data() {
      return {
          isTableVisible: false,
          filterByPaymentType: "",
          filterByType: ""
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
    highlightPaymentTypeMatches(text) {
      const matchExists = text
        .toLowerCase()
        .includes(this.filterByPaymentType.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(this.filterByPaymentType, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    },
    highlightTypeMatches(text) {
      const matchExists = text
        .toLowerCase()
        .includes(this.filterByType.toLowerCase());
      if (!matchExists) return text;

      const re = new RegExp(this.filterByType, "ig");
      return text.replace(re, matchedText => `<strong>${matchedText}</strong>`);
    }
  },
  computed: {
    filteredRows() {
      return this.transactions.data.filter(row => {
        const payment_type = row.payment_type.toString().toLowerCase();
        const type = row.type.toLowerCase();
        const searchTerm = this.filterByPaymentType.toLowerCase();
        const searchTerm2 = this.filterByType.toLowerCase();

        return (
          type.includes(searchTerm2) || payment_type.includes(searchTerm)
        );
      });
    }
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
