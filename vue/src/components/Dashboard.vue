<template>
<div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>
  <div class="container" >
    <Card v-if="currentUser && currentUser.user_type === 'V'" class="card l-bg-cyan">
      <template #title>
        <p class="text-center">Available Balance</p>
      </template>
      <template #content>
          <p class="text-center">{{currentUser.balance}} €</p>
          <Card v-if="showlastTransation && currentUser.count_transactions > 0" class="card">
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
  <div class="pt-3" v-if="currentUser.count_transactions > 0">
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
    if (this.currentUser.count_transactions > 0) {
      await TransactionService.getLastTransaction().then((result)=>{this.showlastTransation = true; this.lastTransation = result.data.data;});
    }
  },
  methods: {
    getDate : function (date) {
      return moment(date).utc().fromNow();
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

<style scoped>
.card {
    background-color: #fff;
    border-radius: 10px;
    border: none;
    position: relative;
    margin-bottom: 30px;
    box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
}
.l-bg-cherry {
    background: linear-gradient(to right, #493240, #f09) !important;
    color: #fff;
}

.l-bg-blue-dark {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
    color: #fff;
}

.l-bg-green-dark {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
    color: #fff;
}

.l-bg-orange-dark {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
    color: #fff;
}

.l-bg-white-dark {
  background: linear-gradient(to right, #493240, #fff) !important;
  color: #fff;
}

.l-bg-blue-light {
    background: linear-gradient(to right, #a86008, #4286f4) !important;
    color: #fff;
}

.card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
    font-size: 110px;
}

.card .card-statistic-3 .card-icon {
    text-align: center;
    line-height: 50px;
    margin-left: 15px;
    color: #000;
    position: absolute;
    right: -5px;
    top: 20px;
    opacity: 0.1;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}

.l-bg-green {
    background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
    color: #fff;
}

.l-bg-orange {
    background: linear-gradient(to right, #f9900e, #ffba56) !important;
    color: #fff;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}
</style>