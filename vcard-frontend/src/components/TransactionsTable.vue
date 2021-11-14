<template>
  <table class="table">
    <thead>
      <tr>
        <th
          v-if="showId"
          class="align-middle"
        >#</th>
        <th class="align-middle">oldBalance</th>
        <th class="align-middle">newBalance</th>
        <th class="align-middle">payment_type</th>
        <th class="align-middle">type</th>
        <th>

        </th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="transaction in transactions"
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
        <td class="align-middle">{{ transaction.type }}</td>
        <td
          class="text-end align-middle"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(transaction)"
            > <i class="bi bi-xs bi-pencil"></i>e
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {

  name: "TransactionsTable",
  props: {
    transactions: {
      type: Array,
      default: () => [],
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
  ],
  methods: {
    editClick (transaction) {
      this.$emit('edit', transaction)
    },
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
