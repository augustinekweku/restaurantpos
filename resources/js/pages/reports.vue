<template>
    <div class="reports-page animate__animated animate__fadeIn mb-5">
            <h2 class="text-center mb-3">Reports</h2>
            <div>
                <div class="searchDateRange text-center">
                    <div>
                        Between:
                        <input class="date-input" v-model="fromDate" type="date" style="width:95px" /> and
                        : <input class="date-input" v-model="toDate" type="date" placeholder="Select date" style="width:120px" />
                        <Button size="small" class="m-2" @click="filterDate" type="primary">Filter</Button>
                    </div>
                </div>

            </div>
            <div class="reports-table p-3 " id="print-block">
                <div v-if="showResults" class="p-2 date-desc text-center animate__animated animate__fadeIn">
                    Showing results from {{fromDate}} to {{toDate}}
                </div>
                    <vue-good-table
                            
                    :columns="columns1"
                    :rows="reports"
                    :line-numbers="true"
                    :search-options="{ enabled: true }"
                    :pagination-options="{
                            enabled: true
                        }"
                    
                    >
                    <div slot="table-actions">
                    <Button class="mx-2" @click="printTable"  > Print <Icon type="ios-print-outline" size="15" /> </Button>
                    </div>
                </vue-good-table>
                <div v-if="showResults" class="mb-5 p-2 text-end"><h4>Total Amount: {{dateRangeSum}}</h4></div>
            </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            fromDate: "",
            toDate: "",
            reports: [],
            dateRangeSum: null,
            showResults: false,
                columns1: [
                {
                    label: "Type",
                    field: "order_type"
                },
                {
                    label: "Invoice",
                    field: "invoice_number"
                },
                {
                    label: "Table",
                    field: "table_name"
                },
                {
                    label: "Order #",
                    field: "order_number"
                },
                {
                    label: "Amount",
                    field: "order_total"
                },
                {
                    label: "Paid",
                    field: "paid"
                },
                {
                    label: "Balance",
                    field: "balance"
                },
            ]
        }
    },
    methods: {
        async filterDate(){
            if (this.fromDate.trim() == "")
                return this.error("Beginning date is required");
            if (this.toDate.trim() == "")
                return this.error("End date name is required");
            const getResults = await this.callApi("get", `app/get_date_range/${this.fromDate}/${this.toDate}`);
            
            if (getResults.status == 200) {
                    this.reports = getResults.data.getDateData
                    this.dateRangeSum = getResults.data.total
                    this.showResults = true
            }

        },
        printTable() {
            printJS({ printable:'print-block',css:'/css/reports-print.css',  type:'html'})
        },
        async getClearedOrders(){
            const getClearedOrders = await this.callApi("get", "app/get_cleared_orders");
        if ((getClearedOrders.status = 201)) {
            this.reports = getClearedOrders.data;
            //console.log(getClearedOrders.data);
        } else {
            this.swr();
        }
        }
    },
    created() {
        this.getClearedOrders()
        console.log('object')
    }
}
</script>

<style scoped>
.date-input{
    padding: 5px;
    border: 1px solid #696767;
    border-radius: 4px;
}
</style>