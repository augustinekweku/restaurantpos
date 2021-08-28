<template>
    <div class="pos-page   animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <div class="one-third active-table-column mb-4">
                    <div class="header p-2 text-center mb-2"><h5 class="fw-light">Active Table</h5></div>
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
                                <tr v-for="(orderDetails, i) in orderDetails" :key="i" class="animate__animated animate__fadeIn">
                                <th scope="row">{{i+1}}</th>
                                <td>{{orderDetails.item_name}}</td>
                                <td>{{orderDetails.price}}</td>
                                <td><input type="number" @change="calcAmount(orderDetails, i)" v-model="orderDetails.quantity" style="width:100px; border:solid 1px grey; padding:2px;"></td>
                                <td>{{orderDetails.amount}}</td>
                                <td><Icon @click="removeItem(orderDetails, i)" style="cursor:pointer" type="md-close" color="red" /></td>
                                </tr>
                                <tr>
                                    <th colspan="3"></th>
                                    <th >Total</th>
                                    <th >GHS {{order_total}}</th>
                                </tr>
                            </tbody>
                            <tbody v-else>
                            <tr  class="text-center" ><th colspan="5"><h3 class="fw-light">Empty Cart</h3></th> </tr>
                            </tbody>
                            </table>
                            <div class="d-flex">
                                <div class="first pe-2"><Button type="success">Checkout</Button></div>
                                <div class="first pe-2"><Button @click="processOrder(orderDetails, i)" type="primary">Process</Button></div>
                                <div class="first pe-2"><Button @click="cancelOrder" type="error">Cancel</Button></div>
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
                    <Select placeholder="Category">
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
                            <Card  v-for="(table, i) in tables" :key="table.id" style="" class="text-center col-sm-12 col-md-12 col-lg-12">
                                <p @click="changeTableStatus(table, i)" style="cursor:pointer" slot="title">
                                    <Icon type="md-restaurant"></Icon>
                                   {{table.table_name}}
                                </p>
                                <Button v-if="table.status==='empty'"   type="success"  shape="circle"> {{table.status}}</Button>
                                <Button v-if="table.status==='occupied'"   type="error"  shape="circle"> {{table.status}}</Button>
                                <Button v-if="table.status==='unpaid'"   type="warning"  shape="circle"> {{table.status}}</Button>                            
                                </Card>
                                </div>
                                <div v-else>
                                    <h3 class="p-2 fw-light text-center">No Tables available</h3>
                                </div>
                        
                    </Card>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            tables:[],
            items:[],
            order:[],
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
                }
            ],
        }
    },
    methods:{
        removeItem(orderDetails, i){
            this.orderDetails.splice(i,1)
            this.calcOrderTotal()
        },
        async processOrder(orderDetails, i){
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
                order_details: this.orderDetails
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
                this.orderDetails = []
                this.order_total= null
                this.activeTableName = ""
                this.activeTableID = null
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
        calcAmount(orderDetails, i){
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
                        order_id: null
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
            
        }
    },
    created() {
        this.getEmptyAndUnpaidTables()
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