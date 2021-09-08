<template>
    <div class="pos-page  my-3 animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <div class="one-third active-table-column mb-4">
                    <div class="header py-1 px-2  mb-2 d-flex justify-content-between align-items-center">
                        <div><h5 class="fw-light">Active Table</h5></div>
                        <div>
                         <Select placeholder="Select Order type" v-model="order_type" width="40px">
                            <Option value="table">Table</Option>
                            <Option value="takeaway">Take Away</Option>
                        </Select>
                        </div>
                    </div>
                    <Card shadow class="column-container p-2" style="background:white;">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="left-column"><h5 class="fw-light">Active : {{activeTableName}}</h5></div>
                            <div class="right-column"><span class="fw-light">Invoice: </span><Tag color="success">{{invoice}}</Tag></div>
                        </div>
                            <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Amount</th>
                                <th scope="col"><Icon type="md-cog" /></th>
                                </tr>
                            </thead>
                            <tbody v-if="orderDetails.length">
                                <tr v-for="(orderDetail, i) in orderDetails" :key="i" class="animate__animated animate__fadeIn">
                                <th scope="row">{{i+1}}</th>
                                <td>{{orderDetail.item_name}}</td>
                                <td>{{orderDetail.price}}</td>
                                <td><input type="number" @keyup="calcAmount(orderDetail, i)" :max="orderDetail.qty_left" @change="calcAmount(orderDetail, i)" v-model="orderDetail.quantity" style="width:100px; border:solid 1px grey; padding:2px;"></td>
                                <td>{{orderDetail.amount}}</td>
                                <td><Icon @click="removeItem(orderDetail, i)" style="cursor:pointer" type="md-close" color="red" /></td>
                                </tr>
                                <tr>
                                    <th colspan="3"></th>
                                    <th >Total</th>
                                    <th colspan="2">GH₵ {{order_total}}</th>
                                </tr>

                                <tr v-if="order_type=== 'takeaway'">
                                    <th colspan="1"></th>
                                    <th colspan="4"> <div class="d-flex align-items-center justify-content-end">
                                        <div>Paid: &nbsp; </div> <div> GH₵ </div>
                                        &nbsp; <div><InputNumber @on-change="calcBalance(orderDetails, i)"  :min="1" :step="0.5" v-model="takeAwayOrder.paid">
                                                </InputNumber> 
                                                </div> 
                                                </div> 
                                    </th>
                                </tr>
                                <tr v-if="order_type=== 'takeaway'">
                                    <th colspan="1"></th>
                                    <th colspan="4"> <div class="d-flex align-items-center justify-content-end">
                                        <div>Balance: &nbsp; </div> <div> GH₵ </div>
                                        &nbsp; <div><InputNumber v-model="takeAwayOrder.balance" readonly></InputNumber>
                                        </div> 
                                        </div>
                                    </th>
                                </tr>

                            </tbody>
                            <tbody v-else>
                            <tr  class="text-center" ><th colspan="6"><h3 class="fw-light">Empty Cart</h3></th> </tr>
                            </tbody>
                            </table>
                            <div class="d-flex justify-content-between">
                                <div class="first pe-2" v-if="order_type=== 'takeaway'"><Button @click="showPrintModal(orderDetails)"  type="success">Check out</Button></div>
                                <div class="first pe-2" v-if="order_type=== 'table'"><Button @click="processOrder(orderDetails)" type="primary">Process</Button></div>
                                <div class="first pe-2"><Button @click="cancelOrder" type="error">Cancel</Button></div>
                            </div>
                    </Card>


                    <div class="d-none header p-2 text-center mt-4 mb-2"><h5 class="fw-light">Latest Orders</h5></div>
                        <Card shadow class=" d-none column-container p-2" style="background:white;">
                            <div v-if="latestOrder" class=" row gy-2">
                                    <div  class="">
                                    <div 
                                        class="mb-4 col-sm-12 animate__animated animate__bounceIn">
                                                <div class>
                                                    
                                                        <!-- start of item card  -->
                                                    <Card class="animate__animated animate__bounceIn">
                                                        <Collapse>
                                                            <Panel class="text-center" name="1">
                                                            <span class="fw-bolder"> Order # {{latestOrder.order_number}} </span> for <span class="fw-bold" style="color:orangered"> {{latestOrder.table_name}}</span>
                                                                <div slot="content" class="px-4">
                                                                    <div class="d-flex justify-content-between">

                                                                    </div>
                                                                </div>
                                                            </Panel>
                                                        </Collapse>
                                                        <ul class="p-2">
                                                            <li v-for="(orderDetail, i) in latestOrder.order_details" :key="i">
                                                                <p class="fs-6 pb-1">
                                                                    {{orderDetail.item_name}}
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </Card>
                                                            <!-- end item-card-->
                                                        </div>
                                                        <!-- end wrapper -->
                                    </div>
                                    </div>
                            <div v-if="latestOrder == []" class=" mt-4 row gy-2">
                                <h1 class="text-center mt-5">No available orders yet</h1>
                            </div>
                            </div>
                        </Card>
                </div>
            </div>
            
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <div class="one-third items-column mb-4">
                    <div class="header p-2 text-center mb-2"><h5 class="fw-light">Items</h5></div>
                <Card shadow class="column-container p-2" style="background:white;">
                <div class="d-flex justify-content-around align-items-center p-2">
                    <div class="div">Sort By:</div>
                    <div class="div">
                    <Select not-found-text="No data found" placeholder="Category">
                    <Option
                        v-for="(c, i) in categories"
                        :value="c.id"
                        :key="i"
                        >{{ c.category_name }}</Option
                    >
                </Select>
                    </div>
                </div>
                <vue-good-table
                    :columns="columns1"
                    :rows="items"
                    @on-row-dblclick="onRowDoubleClick"
                    :search-options="{
                            enabled: true
                        }"
                    :line-numbers="true"
                    >
                </vue-good-table>
                </Card>
                </div>
            </div>

            <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                <div class="one-third tables-column mb-4">
                    <div class="header p-2 text-center mb-2"><h5 class="fw-light">Tables</h5></div>
                    <Card shadow class="column-container p-2" style="background:white;">
                        
                            <div v-if="tables.length" class=" row gx-2 gy-2 active-tables">
                            <Card  v-for="(table, i) in tables" :key="table.id" style="" class=" table_card my-2 text-center col-sm-12 col-md-12 col-lg-12">
                                <p @click="changeTableStatus(table, i)" style="cursor:pointer" slot="title">
                                    <Icon type="md-restaurant"></Icon>
                                    {{table.table_name}}
                                </p>
                                <Button v-if="table.status==='empty'"   type="success" size="small"  shape="circle"> {{table.status}}</Button>
                                <Button v-if="table.status==='occupied'"   type="error" size="small" shape="circle"> {{table.status}}</Button>
                                <Button v-if="table.status==='unpaid'"   type="warning"  size="small" shape="circle"> {{table.status}}</Button>                            
                                </Card>
                                </div>
                                <div v-else>
                                    <h3 class="p-2 fw-light text-center">No Tables available</h3>
                                </div>
                        
                            </Card>
                </div>
            </div>

        </div>
<!-- START OF MODAL FOR PRINTING -->
    <!-- MODAL FOR PRINTING  -->
        <Modal
        v-model="printModal"
        :title="printModalTitle"
        :closable="false"
        footer-hide>
        <div class="print-block" id="print-block">
        <div class="print-content">
        <div class="header fs-16" style="text-align:center">
        <span><b> SomeWhere Restaurant </b></span> <br>
        <span> <b>Beach Resort Avenue Strt 123 </b></span><br>
        <span><b> Sogakope</b></span>
        </div>
        <div class="right-info" style="text-align:right">
           <b> Invoice: </b> {{invoice}} <br>
           <b> Date:</b> {{takeAwayOrder.orderReceiptDate}} <br>
           <b> Order:</b> #{{takeAwayOrder.receiptOrderNumber}} <br>

        </div>
        <div class="body" style="margin-top:10px">
                            <table class="table" id="printMe"  style="padding:0px !important">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                                <th scope="col" style="width:100px">Qty</th>
                                <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(orderDetails, i) in printOrderData" :key="i" class="animate__animated animate__fadeIn">
                                <th scope="row">{{i+1}}</th>
                                <td>{{orderDetails.item_name}}</td>
                                <td>{{  orderDetails.price }}</td>
                                <td>{{orderDetails.quantity}}</td>
                                <td>{{orderDetails.amount}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end">Total </th>
                                    <th  style="text-align:right" colspan="2">GH₵ {{order_total}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end">Paid </th>

                                    <th style="text-align:right"  colspan="2"> GH₵ {{takeAwayOrder.paid}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end ps-2">Balance </th>
                                    <th style="text-align:right" colspan="2"> GH₵ {{takeAwayOrder.balance}}</th>
                                </tr>
                            </tbody>
                            </table>
        </div>
        </div>
        </div>

        <div class="button-footer d-flex justify-content-around">
            <div class="print-checkout-button">
                <Button type="success" @click="checkout">Checkout and Print</Button>
            </div>
            <div class="cancel-button">
                <Button type="error" @click="closeModal">Close</Button>
            </div>
        </div>
    </Modal>

<!-- END OF MODAL FOR PRINTING -->


    </div>
</template>

<script>
export default {
    data(){
        return{
            takeAwayOrder:{
                balance: null,
                paid: null,
                orderReceiptDate: null,
                invoice: null,
                receiptOrderNumber: null,
            },
                confirmModal: false,
                orderModal: false,
                printModal: false,
                printModalTitle: '',
            printOrderData: {},
            order_type: "",
            tables:[],
            items:[],
            order:[],
            latestOrder: [],
            invoice: null,
            order_total: null,
            activeTableName: '',
            activeTableID: null,
            selectedTableIndex: -1,
            orderDetails:[],
            categories: [],
                        columns1: [
                {
                    label: "Item Name",
                    field: "item_name"
                },
                {
                    label: "Price",
                    field: "price"
                },
                {
                    label: "Qty left",
                    field: this.qtyLeftFn,
                    html: true
                }
            ],
        }
    },
    methods:{
        qtyLeftFn(RowObj){
            if (RowObj.qty_left < 5) {
                return `<span style="color:red;">${RowObj.qty_left}</spam>`
            }else{
                return RowObj.qty_left
            }
        },
        async checkout(){	
            if (!this.takeAwayOrder.paid) {
                return this.error("Paid field is empty")
            }
            if (this.takeAwayOrder.paid < this.printOrderData.order_total) {
                return this.error("Paid amount is less than Order total")
            }
            let obj = {
                status: 1,
                balance: this.takeAwayOrder.balance,
                paid: this.takeAwayOrder.paid,
                order_type: this.order_type,
                total: this.order_total,
                order_type: this.order_type,
                order_details: this.orderDetails,
                order_number: this.takeAwayOrder.receiptOrderNumber,
                invoice: this.invoice,
                ready: 1,
            }
            //return console.log(obj)
            const res = await this.callApi(
                "post",
                "app/checkout_take_away_order",
                obj
            );
            console.log(res.data)
            if (res.status == 200) {
                this.success("Order succesfully checked out")
                printJS({ printable:'print-block',css:'/css/print.css',  type:'html'})
                this.takeAwayOrder.balance = null
                this.takeAwayOrder.paid= null
                this.takeAwayOrder.randomNumber= null
                this.generateInvoice()
            }
        },

            calcBalance(order, i){
               console.log(' testing calc')
                this.takeAwayOrder.balance =  parseFloat(this.takeAwayOrder.paid) - parseFloat(this.order_total)
            },
            showPrintModal(orderDetails){
                let randomNumber = Math.floor((Math.random() * 1000) + 1)
                this.takeAwayOrder.receiptOrderNumber = randomNumber
                this.takeAwayOrder.invoice = this.invoice
                if (!this.takeAwayOrder.paid) {
                    return this.error("Paid field is empty")
                }
                if (this.takeAwayOrder.paid < this.order_total) {
                    return this.error("Paid amount is less than Order total")
                }
                let today = new Date();
                let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                let dateTime = date+' '+time;
                this.printOrderData = orderDetails
                this.takeAwayOrder.orderReceiptDate = dateTime
                this.printModal = true
                this.printModalTitle = 'Order #' +  this.takeAwayOrder.receiptOrderNumber 
            },
        closeModal(){
            this.printModal = false
            this.orderDetails = []
            this.order_total= null
            this.activeTableName = ""
            this.activeTableID = null
        },



        removeItem(orderDetail, i){
            this.orderDetails.splice(i,1)
            this.calcOrderTotal()
        },
        async processOrder(orderDetail, i){
            let randomNumber = Math.floor((Math.random() * 1000) + 1)
            if (!this.activeTableID) {
                return this.error("No Table selected")
            }
            if (!this.orderDetails.length) {
                return this.error("Cart is empty")
            }
                let orderForm = {
                table_id: this.activeTableID,
                order_number: randomNumber,
                table_name: this.activeTableName,
                invoice_number: this.invoice,
                order_total: this.order_total,
                order_details: this.orderDetails,
                order_type: this.order_type
            }
            console.log(orderForm)
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_order_details",
                orderForm
            );
            if (res.status == 200) {
                this.success("Order succesfully processed")
                this.tables.splice(this.selectedTableIndex, 1)
                this.generateInvoice()
                this.getLatestRequestedOrder()
                this.orderDetails = []
                this.order_total= null
                this.activeTableName = ""
                this.activeTableID = null,
                this.order_type = ""
            }
        },
        cancelOrder(){
            this.orderDetails = []
            this.order_total= null
            this.activeTableName = ""
            this.activeTableID = null
        },
        calcOrderTotal(){           
            let total = this.orderDetails.reduce((total, obj) => parseFloat(obj.amount) + total,0)
            console.log(total)
            this.order_total = total;

        },
        calcAmount(orderDetail, i){
            if (this.orderDetails[i].quantity > orderDetail.qty_left) {
                this.orderDetails[i].quantity = 0
                this.error("Quantity entered is more than quantity left")
                this.orderDetails[i].amount = this.orderDetails[i].price * this.orderDetails[i].quantity
                return this.calcOrderTotal()
            }
            this.orderDetails[i].amount = this.orderDetails[i].price * this.orderDetails[i].quantity
            this.calcOrderTotal()
        },
            onRowDoubleClick(params) {
            // params.row - row object
            // params.pageIndex - index of this row on the current page.
            // params.selected - if selection is enabled this argument
            // indicates selected or not
            // params.event - click event

            let obj = {
        				item_id : params.row.id,
                        item_name: params.row.item_name,
                        price: parseFloat(params.row.price),
                        amount: parseFloat(params.row.price),
                        quantity: 1,
                        order_id: null,
                        qty_left:params.row.qty_left
                    }
                    const isItemClicked = this.orderDetails.find((item) => item.item_id === params.row.id)
                    if (isItemClicked) {
                        return this.warning("Item already in the cart. Please increase quanity in the cart if you want to")
                    }
            this.orderDetails.push(obj)
            this.calcOrderTotal()
          },
        changeTableStatus(table, i){
            if (this.tables[i].status === "unpaid") {
                return this.error("This table has not been cleared yet!")
            }
            //check if another table is already selected
            if (this.activeTableID && this.activeTableID !== this.tables[i].id) {
                return this.error("Another table already selected")
            }
            if (this.tables[i].status === "occupied" && this.activeTableID == this.tables[i].id) {
                this.tables[i].status = "empty" 
                this.activeTableName = ''
                return this.activeTableID = null
            }
            this.tables[i].status === "empty" ? this.tables[i].status = "occupied" : this.tables[i].status = "empty" 
            this.activeTableID = this.tables[i].id
            this.activeTableName = this.tables[i].table_name
            this.selectedTableIndex = i
        },
            async getEmptyAndUnpaidTables() {
            const getTables = await this.callApi(
                "get",
                "app/get_empty_and_unpaid_tables"
            );
            if ((getTables.status = 200)) {
                this.tables = getTables.data;
                console.log(getTables.data);
            } else {
                this.swr();
            }
        },
                async getItemsForPos() {
            const getItems = await this.callApi(
                "get",
                "app/get_items_for_pos"
            );
            if ((getItems.status = 200)) {
                this.items = getItems.data;
                console.log(getItems.data);
            } else {
                this.swr();
            }
        },
                async getCategories() {
            const getCategories = await this.callApi(
                "get",
                "app/get_categories"
            );
            if ((getCategories.status = 201)) {
                this.categories = getCategories.data;
                console.log(getCategories.data);
            } else {
                this.swr();
            }
        },
        generateInvoice(){
            const d = new Date();
            let randomNumber = Math.floor((Math.random() * 10000) + 1)
            let month = d.getMonth() + 1;
            let day = d.getDate();
            let year = d.getFullYear();
            month = ('0' + month).slice(-2)
            day = ('0' + day).slice(-2)
            let invoice = year + '' +  month + '' + day + '-' + randomNumber
            console.log(invoice)
            this.invoice = invoice
            
        },
        async getLatestRequestedOrder(){
            const getLatestRequestedOrder = await this.callApi("get", "app/get_latest_requested_order");
            if (getLatestRequestedOrder.status == 200) {
                this.latestOrder = getLatestRequestedOrder.data;
            } else {
                this.swr();
            }
        }

    },
    created() {
        this.getEmptyAndUnpaidTables()
        this.getLatestRequestedOrder()
        this.getItemsForPos()
        this.getCategories()
        this.generateInvoice()
    }
}
</script>

<style scoped>
 .header{
    border-bottom: 2px solid orange;
    background: white;
}
.pos-page{
    margin: 10px 20px;
}

</style>