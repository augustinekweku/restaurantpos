<template>
    <div class="-page animate__animated animate__fadeIn mb-5">
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
                <div  class="mb-5 p-2 text-end"><h4>Total Amount: {{total_amount}}</h4></div>
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
            total_amount: null,
            showResults: false,
                columns1: [
                {
                    label: "Invoice",
                    field: "invoice_number"
                },
                {
                    label: "Items",
                    field: this.itemsFn,
                    html: true

                },
                {
                    label: "Issued by",
                    field: this.user,
                    html: true

                },
                {
                    label: "Ordered by",
                    field: this.company,
                    html: true

                },
                {
                    label: "Paid",
                    field: "paid"
                },
                {
                    label: "Balance",
                    field: "balance"
                },
                {
                    label: "Amount",
                    field: "order_total"
                },
            ]
        }
    },
    methods: {
        user(RowObj){
            return RowObj.user.firstName
        },
        company(RowObj){
            return RowObj.company.company_name
        },
        itemsFn(RowObj){
            //return RowObj.order_details.length
            let items = []
            let reducedItems = []
            if (RowObj.order_details.length > 2) {
                for (let j = 0; j < 2; j++) {
                    reducedItems.push(RowObj.order_details[j].item_name + ' X ' + RowObj.order_details[j].quantity)                 
                }
                return `<span style="font-size:13px !important">${reducedItems} ...</span>`
            }else{
                for (let i = 0; i < RowObj.order_details.length; i++) {
                items.push(RowObj.order_details[i].item_name + ' X ' + RowObj.order_details[i].quantity)
                return `<span style="font-size:13px !important">${items}</span>`
                }
                
            }
        },
        async filterDate(){
            if (this.fromDate.trim() == "")
                return this.error("Beginning date is required");
            if (this.toDate.trim() == "")
                return this.error("End date name is required");
            const getResults = await this.callApi("get", `app/get_cleared_creditor_date_range/${this.fromDate}/${this.toDate}`);
            
            if (getResults.status == 200) {
                    this.reports = getResults.data.getDateData
                    this.total_amount = getResults.data.total
                    this.showResults = true
            }

        },
        printTable() {
            printJS({ printable:'print-block',css:'/css/reports-print.css',  type:'html'})
        },
        async getClearedCreditorOrders(){
            const getClearedCreditorOrders = await this.callApi("get", "app/get_cleared_creditor_orders");
        if ((getClearedCreditorOrders.status = 201)) {
            this.reports = getClearedCreditorOrders.data;
            let total = 0
            for (let i = 0; i < this.reports.length; i++) {
                total += parseFloat(this.reports[i].order_total)
            }
            this.total_amount = total.toFixed(2)
            //console.log(getClearedCreditorOrders.data);
        } else {
            this.swr();
        }
        }
    },
    created() {
        this.getClearedCreditorOrders()
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