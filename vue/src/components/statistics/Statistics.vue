<template>
    <div class="container">
        <Divider align="left">
            <div class="p-d-inline-flex p-ai-center">
                <i class="pi pi-chart-bar p-mr-2"></i>
                <b> Statistics</b>
            </div>
        </Divider>
        <div class="row">
            <Panel class="col-lg-6 col-md-12 my-3" header="Number Of Transaction Per Time" :toggleable="basicDataFinancial !== 'loading'" :collapsed="graphicFinancialCollapsed">
                <template #icons>
                    <button class="p-panel-header-icon p-link p-mr-2" @click="toggleFinancial">
                        <span class="pi pi-cog"></span>
                    </button>
                    <Menu id="config_menu" ref="menuFinancial" :model="itemsFinancial" :popup="true" >
                        <template #item="{item}">
                            <div class="row justify-content-center px-3" :class="item.number != null ? 'numberDiv' : ''">
                                <span>{{item.label}}</span>
                                <Dropdown v-if="item.list" v-model="itemsFinancialTemporalSelected" :options="item.list.size" optionLabel="name" :placeholder="item.label"/>
                                <InputNumber id="numberDiv" v-else-if="item.number" v-model.lazy="itemsFinancialSizeSelected" :min="1" :max="100" showButtons style="padding-left: 0px;" />
                            </div>
                        </template>
                    </Menu>
                </template>
                <Chart type="line" :data="basicDataFinancial" :options="basicOptions" v-if="basicDataFinancial !== 'loading'"/>
            </Panel>
            <Panel class="col-lg-6 col-md-12 my-3" header="Balance Per Time" :toggleable="basicDataFinancial !== 'loading'" :collapsed="graphicBalancePerTimeCollapsed">
                <template #icons>
                    <button class="p-panel-header-icon p-link p-mr-2" @click="toggleBalancePerTime">
                        <span class="pi pi-cog"></span>
                    </button>
                    <Menu id="config_menu" ref="menuBalancePerTime" :model="itemsBalancePerTime" :popup="true" >
                        <template #item="{item}">
                            <div class="row justify-content-center px-3" :class="item.number != null ? 'numberDiv' : ''">
                                <span>{{item.label}}</span>
                                <Dropdown v-if="item.list" v-model="itemsBalancePerTimeTemporalSelected" :options="item.list.size" optionLabel="name" :placeholder="item.label"/>
                                <InputNumber id="numberDiv" v-else-if="item.number" v-model.lazy="itemsBalancePerTimeSizeSelected" :min="1" :max="100" showButtons style="padding-left: 0px;" />
                            </div>
                        </template>
                    </Menu>
                </template>
                <Chart type="line" :data="basicDataBalancePerTime" :options="basicOptions" v-if="basicDataBalancePerTime !== 'loading'"/>
            </Panel>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 py-md-3 py-sm-3">
                <div class="card l-bg-cherry">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon card-icon-large"><font-awesome-icon :icon="['fas', 'money-bill-wave']" style="fontSize: 100px" /></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Total Transactions</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0" v-if="currentUser.count_transactions">
                                    {{currentUser.count_transactions}}
                                </h2>
                                <i v-else class="pi pi-spin pi-spinner" style="fontSize: 2rem"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 py-md-3 py-sm-3">
                <div class="card l-bg-blue-dark">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon card-icon-large"><i class="pi pi-dollar" style="fontSize: 100px"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Total Spent</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0" v-if="totalSpent">
                                    {{totalSpent}} €
                                </h2>
                                <i v-else class="pi pi-spin pi-spinner" style="fontSize: 2rem"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 py-md-3 py-sm-3">
                <div class="card l-bg-green-dark">
                    <div class="card-statistic-3 p-4">
                        <div class="card-icon card-icon-large"><i class="pi pi-dollar" style="fontSize: 100px"></i></div>
                        <div class="mb-4">
                            <h5 class="card-title mb-0">Total Recieved</h5>
                        </div>
                        <div class="row align-items-center mb-2 d-flex">
                            <div class="col-8">
                                <h2 class="d-flex align-items-center mb-0" v-if="totalRecieved">
                                    {{totalRecieved}} €
                                </h2>
                                <i v-else class="pi pi-spin pi-spinner" style="fontSize: 2rem"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Chart from 'primevue/chart';
import Panel from 'primevue/panel';
import Menu from 'primevue/menu';
import Dropdown from 'primevue/dropdown'
import Divider from 'primevue/divider'
import InputNumber from 'primevue/inputnumber';

import StatisticsService from '../../services/statistics.service'

export default {
    name: "Statistics",
    components: {
        Chart,
        Panel,
        Menu,
        Dropdown,
        InputNumber,
        Divider,
    },
    computed: {
        currentUser() {
            return this.$store.state.user.data;
        },
    },
    data() {
		return {
			basicDataFinancial: 'loading',
			basicDataBalancePerTime: 'loading',
            basicOptions: {
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    }
                }
            },
            graphicFinancialCollapsed: true,
            itemsFinancialSizeSelected: 6,
            itemsFinancialTemporalSelected: {name: 'Month', code: 'month'},

            graphicBalancePerTimeCollapsed: true,
            itemsBalancePerTimeSizeSelected: 6,
            itemsBalancePerTimeTemporalSelected: {name: 'Month', code: 'month'},

            itemsFinancial: [
				{
					label: 'Select a temporal',
					icon: 'pi pi-refresh',
                    list: {
                        size: [
                            {name: 'Day', code: 'day'},
                            {name: 'Week', code: 'week'},
                            {name: 'Month', code: 'month'},
                            {name: 'Year', code: 'year'},
                        ]},
				},
				{
					label: 'Select a size',
					icon: 'pi pi-times',
                    number: {
                        selectedNumber: 3,
                    },
				},
			],

            itemsBalancePerTime: [
				{
					label: 'Select a temporal',
					icon: 'pi pi-refresh',
                    list: {
                        size: [
                            {name: 'Day', code: 'day'},
                            {name: 'Week', code: 'week'},
                            {name: 'Month', code: 'month'},
                            {name: 'Year', code: 'year'},
                        ]},
				},
				{
					label: 'Select a size',
					icon: 'pi pi-times',
                    number: {
                        selectedNumber: 3,
                    },
				},
			],

            totalSpent: null,
            totalRecieved: null
		}
	},
    mounted() {
        this.listFinancial()
        this.listBalancePerTime()
        StatisticsService.getTotalSpent().then((result)=>{this.totalSpent = result.data.totalspent});
        StatisticsService.getTotalRecieved().then((result)=>{this.totalRecieved = result.data.totalrecieved});
    },
    methods: {
        toggleBalancePerTime(event) {
            this.$refs.menuBalancePerTime.toggle(event);
        },
        toggleFinancial(event){
            this.$refs.menuFinancial.toggle(event);
        },
        listFinancial(options = ''){
            StatisticsService.getFinancial(options).then(
                (data) => {
                    this.basicDataFinancial = data.data;
                },
                (error) => {
                    this.errors = error.response.data.errors;
                    this.message =
                        (error.response &&
                        error.response.data &&
                        error.response.data) ||
                        error.message ||
                        error.toString();
                    this.$toast.error(`Statistics error.`, {autoHideDelay: 2000, appendToast: true}) 
            })
        },
        listBalancePerTime(options = ''){
            StatisticsService.getBalancePerTime(options).then(
                (data) => {
                    this.basicDataBalancePerTime = data.data;
                },
                (error) => {
                    this.errors = error.response.data.errors;
                    this.message =
                        (error.response &&
                        error.response.data &&
                        error.response.data) ||
                        error.message ||
                        error.toString();
                    this.$toast.error(`Statistics error.`, {autoHideDelay: 2000, appendToast: true}) 
            })
        }
    },  
    watch: {
        itemsFinancialSizeSelected(){
            let optionsfilter = '?'
            if (this.itemsFinancialTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsFinancialTemporalSelected.code
            }
            if (this.itemsFinancialSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsFinancialSizeSelected
            }
            this.graphicFinancialCollapsed = false
            this.listFinancial(optionsfilter)
        },
        itemsFinancialTemporalSelected(){
            let optionsfilter = '?'
            if (this.itemsFinancialTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsFinancialTemporalSelected.code
            }
            if (this.itemsFinancialSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsFinancialSizeSelected
            }
            this.graphicFinancialCollapsed = false
            this.listFinancial(optionsfilter)
        },

        itemsBalancePerTimeSizeSelected(){
            let optionsfilter = '?'
            if (this.itemsBalancePerTimeTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsBalancePerTimeTemporalSelected.code
            }
            if (this.itemsBalancePerTimeSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsBalancePerTimeSizeSelected
            }
            this.graphicBalancePerTimeCollapsed = false
            this.listBalancePerTime(optionsfilter)
        },
        itemsBalancePerTimeTemporalSelected(){
            let optionsfilter = '?'
            if (this.itemsBalancePerTimeTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsBalancePerTimeTemporalSelected.code
            }
            if (this.itemsBalancePerTimeSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsBalancePerTimeSizeSelected
            }
            this.graphicBalancePerTimeCollapsed = false
            this.listBalancePerTime(optionsfilter)
        },
    }
}
</script>
<style>
#numberDiv {
    margin-right: -39px;
}
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