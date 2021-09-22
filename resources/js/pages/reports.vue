<template>
    <div class=" my-3 reports-page animate__animated animate__fadeIn mb-5">
        <div class="row">
            <div class="col-sm-3">
                <div class="mx-2 mt-3">
                    <div class="searchDateRange text-center">
                        <div class="my-1">
                           <h6 class="py-2 fw-light">Filter By </h6> 
                            <Select
                                @on-change="sortOptionChange"
                                v-model="sortBy"
                                class="py-2"
                            >
                                <Option value="all">All</Option>
                                <Option value="items">Items</Option>
                            </Select>
                        </div>
                    </div>
                </div>
                <div v-if="allRecordsTable" class="all_records_functions">
                <div class="text-center">
                    
                    <input
                        class="date-input"
                        v-model="fromDate"
                        type="date"
                        style="width:95px"
                    />
                    :
                    <input
                        class="date-input"
                        v-model="toDate"
                        type="date"
                        placeholder="Select date"
                        style="width:120px"
                    />
                    <Button
                        class="m-2"
                        @click="filterDate"
                        type="primary"
                        >Filter</Button
                    >
                    <Button
                        class="m-2"
                        @click="getClearedOrders"
                        type="primary"
                        >Show all</Button
                    >
                </div>

                </div>

                <div v-if="itemRecordsTable" class="all_items_functions">
                <div class="text-center mx-2">
                <div class="hide-on-print"> 
                <Select @on-change="fetchItem" not-found-text="No items available" class="py-2" v-model="item_id" placeholder="Select Item" width="40px">
                    <Option
                        v-for="(item, i) in items"
                        :value="item.id"
                        :key="i"
                        >{{ item.item_name }}</Option
                    >
                </Select>
                    <input
                        class="date-input"
                        v-model="fromDate"
                        type="date"
                        style="width:95px"
                    />
                    :
                    <input
                        class="date-input"
                        v-model="toDate"
                        type="date"
                        placeholder="Select date"
                        style="width:120px"
                    />
                    </div>
                    <Button
                        class="m-2"
                        @click="filterDateForItem"
                        type="primary"
                        >Filter Item</Button
                    >
                    <Button
                        class="m-2"
                        @click="getClearedOrderItems"
                        type="primary"
                        >Show all <Icon type="md-medical" /></Button
                    >
                </div>

                </div>
            </div>
            <div class="col-sm-9">
        <!-- SECTION FOR ALL RECORDS  -->
        <div v-if="allRecordsTable" class="reports-table px-2 m-1 mb-5 pb-2" id="print-block">
            <div
                
                class="p-2 date-desc text-center animate__animated animate__fadeIn"
            >
                Showing All Records <span v-if="showRangeResults"> between {{ fromDate }} and {{ toDate }} </span>
            </div>
            <vue-good-table
                class="animate__animated animate__fadeIn"
                :columns="columns1"
                :rows="reports"
                :line-numbers="true"
                :pagination-options="{
                    enabled: true
                }"
                @on-row-click="onRowDoubleClick"
            >
                <div slot="table-actions">
                    <Button class="mx-2" @click="printItemsTable">
                        Print <Icon type="ios-print-outline" size="15" />
                    </Button>
                </div>
            </vue-good-table>
            <div  class="mb-5 p-2 text-end">
                <h4>Total Amount: {{ reportsTotal }}</h4>
            </div>
        </div>
        <!-- END OF SECTION FOR ALL RECORDS -->
        
        <!-- SECTION FOR ITEMS SORTING -->
        <div
            v-if="itemRecordsTable"
            class="items-table p-3 m-2 mb-4"
            id="print-block"
        >
            <div
                
                class="p-2 date-desc text-center animate__animated animate__fadeIn"
            >
                Showing all  Results <span v-if="showItemResults">For {{item_name}}</span>  <span v-if="showRangeResults"> between {{ fromDate }} and {{ toDate }} </span>
            </div>
            <vue-good-table
                class="animate__animated animate__fadeIn"
                :columns="items_columns"
                :rows="item_reports"
                :line-numbers="true"
                :pagination-options="{
                    enabled: true
                }"
            >
                <div slot="table-actions">
                    <Button class="mx-2" @click="printTable">
                        Print <Icon type="ios-print-outline" size="15" />
                    </Button>
                </div>
            </vue-good-table>
            <div class="mb-5 p-2 text-end">
                <div>
                <h4>Total Quantity: {{ total_quantity }}</h4>
                </div>
                <div>
                <h4>Total Amount: {{ total_amount }}</h4>
                </div>
            </div>
        </div>

        <!--  END OF SECTION FOR ITEMS SORTING -->


            </div>
        </div>


        <!-- MODAL FOR VIEWING INDIVIDUAL RECORD IN REPORTS -->
        <Modal
            v-model="reportModal"
            title="Record Details"
            okText="Ok"
            cancelText="Close"
        >
            <div class="d-flex justify-content-between align-items-center p-2">
                <div class="left-column">
                    <h5 class="fw-light">
                        Order# : {{ recordData.order_number }}
                    </h5>
                </div>
                <div class="right-column">
                    <span class="fw-light">Invoice: </span
                    ><Tag color="success">{{ recordData.invoice }}</Tag>
                </div>
            </div>
            <div>
                <div class="left-column">
                    <h5 class="fw-light">
                        Issued By : {{ recordData.firstName }}
                    </h5>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody v-if="recordData.items">
                    <tr
                        v-for="(item, i) in recordData.items"
                        :key="i"
                        class="animate__animated animate__fadeIn"
                    >
                        <th scope="row">{{ i + 1 }}</th>
                        <td>{{ item.item_name }}</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.amount }}</td>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th>Total</th>
                        <th colspan="2">GH₵ {{ recordData.total }}</th>
                    </tr>
                </tbody>
            </table>
        </Modal>
        <!-- END OF MODAL FOR VIEWING INDIVIDUAL RECORDS -->

    </div>
</template>

<script>
export default {
    data() {
        return {
            item_name: "",
            item_id: null,
            showItemResults: false,
            items:[],
            total_quantity: null,
            total_amount: null,
            showRangeResults: false,
            showAllResults: true,
            reportsTotal: null,
            sortBy: "all",
            itemRecordsTable: false,
            allRecordsTable: true,
            fromDate: "",
            toDate: "",
            reports: [],
            item_reports: [],
            items_columns: [
                {
                    label: "Item",
                    field: this.itemFn
                },
                {
                    label: "Unit Price",
                    field: "price",
                    sortable: false,
                },
                {
                    label: "Quantity",
                    field: "quantity",
                    sortable: false,
                },
                {
                    label: "Amount",
                    field: "amount",
                    sortable: false,
                },
                {
                    label: "Date",
                    field: this.itemDateFn,
                    html:true
                }
            ],
            recordData: {},
            reportModal: false,
            dateRangeSum: null,
            columns1: [
                {
                    label: "Date",
                    field: this.orderDateFn,
                    html:true
                },
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
                    label: "Paid",
                    field: "paid",
                    sortable: false,
                },
                {
                    label: "Balance",
                    field: "balance",
                    sortable: false,
                },
                {
                    label: "Amount",
                    field: "order_total",
                    sortable: false,
                }
            ]
        };
    },
    methods: {
        async fetchItem(){
            console.log(this.item_id)
            const getResults = await this.callApi(
                "get",
                `app/fetch_item/${this.item_id}`
            );

            if (getResults.status == 200) {
                this.item_reports = getResults.data.data;
                this.total_amount = getResults.data.total_amount;
                this.total_quantity = getResults.data.total_quantity;
                this.item_name = getResults.data.data[0].item_name;
                this.showItemResults = true
                this.showRangeResults = false
                this.fromDate = ""
                this.toDate = ""
            }
        },
        async filterDateForItem(){
            if (this.fromDate.trim() == "")
                return this.error("Beginning date is required");
            if (this.toDate.trim() == "")
                return this.error("End date name is required");
            const getResults = await this.callApi(
                "get",
                `app/get_date_range_for_item/${this.fromDate}/${this.toDate}/${this.item_id}`
            );

            if (getResults.status == 200) {
                this.item_reports = getResults.data.getDateData;
                this.total_amount = getResults.data.total_amount;
                this.total_quantity = getResults.data.total_quantity;
                this.showRangeResults = true
            }        
        },
        itemDateFn(RowObj){
            //return RowObj.order
            let d = new Date( RowObj.order.created_at );
            let date = d.toDateString();
            return `<span style="font-size:13px;">${date}</span>` 
        },
        orderDateFn(RowObj){
            //return RowObj.order
            let d = new Date( RowObj.created_at );
            let date = d.toDateString();
            return `<span style="font-size:13px;">${date}</span>`
        },
        itemFn(RowObj){
            return RowObj.item.item_name
        },
        sortOptionChange() {
            if (this.sortBy == "all") {

                this.itemRecordsTable = false;
                this.allRecordsTable = true;
            }
            if (this.sortBy == "items") {
                this.allRecordsTable = false;
                this.itemRecordsTable = true;
            }
        },
        onRowDoubleClick(params) {
            // params.row - row object
            // params.pageIndex - index of this row on the current page.
            // params.selected - if selection is enabled this argument
            // indicates selected or not
            // params.event - click event
            let obj = {
                invoice: params.row.invoice_number,
                order_number: params.row.order_number,
                items: params.row.order_details,
                firstName: params.row.user.firstName,
                paid: params.row.paid,
                balance: params.row.balance,
                total: params.row.order_total
            };
            this.recordData = obj;
            this.reportModal = true;
        },
        user(RowObj) {
            return RowObj.user.firstName;
        },
        itemsFn(RowObj) {
            //return RowObj.order_details.length
            let items = [];
            let reducedItems = [];
            if (RowObj.order_details.length > 2) {
                for (let j = 0; j < 2; j++) {
                    reducedItems.push(
                        " " +
                            RowObj.order_details[j].item_name +
                            " X " +
                            RowObj.order_details[j].quantity
                    );
                }
                return `<span style="font-size:13px !important">${reducedItems} ...</span>`;
            }
            if (RowObj.order_details.length < 3) {
                for (let i = 0; i < RowObj.order_details.length; i++) {
                    items.push(
                        " " +
                            RowObj.order_details[i].item_name +
                            " X " +
                            RowObj.order_details[i].quantity
                    );
                }
                return `<span style="font-size:13px !important">${items} </span>`;
            }
        },
        async filterDate() {
            if (this.fromDate.trim() == "")
                return this.error("Beginning date is required");
            if (this.toDate.trim() == "")
                return this.error("End date name is required");
            const getResults = await this.callApi(
                "get",
                `app/get_date_range/${this.fromDate}/${this.toDate}`
            );

            if (getResults.status == 200) {
                this.reports = getResults.data.getDateData;
                this.reportsTotal = getResults.data.total;
                this.showRangeResults = true
            }
        },
        async getClearedOrderItems() {
            const getClearedOrderItems = await this.callApi(
                "get",
                "app/get_cleared_order_items"
            );
            if ((getClearedOrderItems.status = 201)) {
                this.item_reports = getClearedOrderItems.data.data;
                this.total_amount = getClearedOrderItems.data.total_amount;
                this.total_quantity = getClearedOrderItems.data.total_quantity;
                this.showRangeResults = false
                this.showItemResults = false
                //console.log(getClearedOrderItems.data);
            } else {
                this.swr();
            }
        },
        printTable() {
            printJS({
                printable: "print-block",
                css: "/css/reports-print.css",
                type: "html"
            });
        },
        printItemsTable() {
            printJS({
                printable: "print-block",
                css: "/css/reports-print.css",
                type: "html"
            });
        },
        async getClearedOrders() {
            const getClearedOrders = await this.callApi(
                "get",
                "app/get_cleared_orders"
            );
            if ((getClearedOrders.status = 201)) {
                this.reports = getClearedOrders.data.reports;
                this.reportsTotal = getClearedOrders.data.total;
                this.showAllResults = true
                this.showRangeResults = false
                this.fromDate = ""
                this.toDate = ""
                //console.log(getClearedOrders.data);
            } else {
                this.swr();
            }
        },
        async getItemsForReport(){
            const getItemsForReport = await this.callApi(
                "get",
                "app/get_items_for_report"
            );
            if ((getItemsForReport.status = 200)) {
                this.item_reports = getItemsForReport.data.data;
                console.log(getItemsForReport.data.data);
            } else {
                this.swr();
            }
        },
        async getAllItems(){
            const getItems = await this.callApi(
                "get",
                "app/get_all_items"
            );
            if ((getItems.status = 200)) {
                this.items = getItems.data;
                //console.log(getItems.data);
            } else {
                this.swr();
            }
        },

    },
    created() {
        this.getClearedOrders();
        this.getClearedOrderItems();
        //this.getItemsForReport();
        this.getAllItems();

        console.log("object");
    }
};
</script>

<style scoped>
.date-input {
    padding: 5px;
    border: 1px solid #696767;
    border-radius: 4px;
}
</style>
