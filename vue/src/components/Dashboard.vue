<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>
  <div class="container" >
    <Card v-if="currentUser && currentUser.user_type === 'V'" style="background-color: gainsboro;">
      <template #title>
        <p class="text-center">Available Balance</p>
      </template>
      <template #content>
          <p class="text-center">{{currentUser.balance}} €</p>
          <Card v-if="showlastTransation" style="background-color: aliceblue;">
            <template #title>
              <p class="text-center">Last Transaction</p>
            </template>
            <template #content>
                <div class="row">
                  <div class="col">
                    <p>{{getDate(lastTransation.datetime)}}</p>
                  </div>
                  <div class="col">
                    <p v-if="lastTransation.payment_type_name" style="text-align: right;">{{lastTransation.payment_type_name}}</p>
                  </div>
                </div>
                <div class="row text-center">
                    <span class="text-center">{{lastTransation.new_balance > lastTransation.old_balance ? 'From ' : 'To '}}{{lastTransation.payment_reference}}</span>
                    <span class="text-center">{{lastTransation.new_balance > lastTransation.old_balance ? '+ ' : '- '}}{{lastTransation.value}} €</span>
                </div>
                <h6 v-if="lastTransation.category_name != null">Category</h6>
                <p class="text-left" v-if="lastTransation.category_name != null">{{lastTransation.category_name.charAt(0).toUpperCase() + lastTransation.category_name.slice(1)}}</p>
                <h6 v-if="lastTransation.description != null">Description</h6>
                <p class="text-left" v-if="lastTransation.description != null">{{lastTransation.description}}</p>
            </template>
          </Card>
      </template>
    </Card>
  </div>
  <div class="pt-3">
    <statistics></statistics>
  </div>
</div>
</template>

<script>
import statistics from './statistics/Statistics.vue';
import Card from 'primevue/card'
import TransactionService from '../services/transaction.service';

import moment from "moment";

export default {
  components: { statistics,Card },
  name: "Dashboard",
  data() {
    return {
      content: '',
      lastTransation: {},
      showlastTransation: false,
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.user.data;
    },
  },
  async mounted() {
    await TransactionService.getLastTransaction().then((result)=>{this.showlastTransation = true; this.lastTransation = result.data.data;});
  },
  methods: {
    getDate : function (date) {
      return moment(date).fromNow();
    }
  },
  sockets: {
    newTransaction () {
      if (this.currentUser.user_type === 'V') {
        TransactionService.getLastTransaction().then((result)=>{this.lastTransation = result.data.data;});
      }
    },
  }
};
</script>