<template>
    <div class="container">
        <h2 class="text-center">Charts</h2>
        <div class="row">
            <Panel class="col-lg-6 col-md-12" header="Financial Information" :toggleable="basicData !== 'loading'" :collapsed="graphicFinancialCollapsed">
                <template #icons>
                    <button class="p-panel-header-icon p-link p-mr-2" @click="toggle">
                        <span class="pi pi-cog"></span>
                    </button>
                    <Menu id="config_menu" ref="menu" :model="items" :popup="true" >
                        <template #item="{item}">
                            <div class="row justify-content-center px-3" :class="item.number != null ? 'numberDiv' : ''">
                                <span>{{item.label}}</span>
                                <Dropdown v-if="item.list" v-model="itemsFinantialTemporalSelected" :options="item.list.size" optionLabel="name" :placeholder="item.label"/>
                                <InputNumber id="numberDiv" v-else-if="item.number" v-model.lazy="itemsFinantialSizeSelected" :min="1" :max="100" showButtons style="padding-left: 0px;" />
                            </div>
                        </template>
                    </Menu>
                </template>
                <Chart type="line" :data="basicData" :options="basicOptions" v-if="basicData !== 'loading'"/>
            </Panel>
        </div>
    </div>
</template>
<script>
import Chart from 'primevue/chart';
import Panel from 'primevue/panel';
import Menu from 'primevue/menu';
import Dropdown from 'primevue/dropdown'

import StatisticsService from '../../services/statistics.service'
import InputNumber from 'primevue/inputnumber';

export default {
    name: "Statistics",
    components: {
        Chart,
        Panel,
        Menu,
        Dropdown,
        InputNumber,
    },
    computed: {
        currentUser() {
            return this.$store.state.user.data;
        },
    },
    data() {
		return {
			basicData: 'loading',
            basicOptions: {
                plugins: {
                    legend: {
                        labels: {
                            color: '#495057'
                        }
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
            itemsFinantialSizeSelected: 6,
            itemsFinantialTemporalSelected: {name: 'Month', code: 'month'},
            items: [
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
			]
		}
	},
    mounted() {
        this.list()
    },
    methods: {
        toggle(event) {
            this.$refs.menu.toggle(event);
        },
        list(options = ''){
            StatisticsService.getFinancial(options).then(
                (data) => {
                    this.basicData = data.data;
                },
                (error) => {
                    this.errors = error.response.data.errors;
                    this.message =
                        (error.response &&
                        error.response.data &&
                        error.response.data) ||
                        error.message ||
                        error.toString();
                    this.$toast.error(`Profile was not updated.`, {autoHideDelay: 2000, appendToast: true}) 
            })
        }
    },  
    watch: {
        itemsFinantialSizeSelected(){
            let optionsfilter = '?'
            if (this.itemsFinantialTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsFinantialTemporalSelected.code
            }
            if (this.itemsFinantialSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsFinantialSizeSelected
            }
            this.list(optionsfilter)
            this.graphicFinancialCollapsed = false
        },
        itemsFinantialTemporalSelected(){
            let optionsfilter = '?'
            if (this.itemsFinantialTemporalSelected) {
                optionsfilter += '&labelCategory='+this.itemsFinantialTemporalSelected.code
                this.graphicFinancialCollapsed = false
            }
            if (this.itemsFinantialSizeSelected) {
                optionsfilter += '&labelSize='+this.itemsFinantialSizeSelected
            }
            this.list(optionsfilter)
            this.graphicFinancialCollapsed = false
        },
    }
}
</script>

<style>
#numberDiv {
    margin-right: -39px;
}
</style>