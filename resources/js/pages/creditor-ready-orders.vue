<template>
    <div class="my-3 ready-orders_page container  animate__animated animate__fadeIn">
        <div class="row gx-5 gy-2">
            <h2 class="text-center mb-3">Orders pending Payment </h2>
        </div>
        <div v-if="Orders.data" >
                <div v-if="Orders.data.length" class="mt-4 row gy-2">
                <div v-for="(order, i) in Orders.data" :key="i"
                    class="mb-4 col-sm-12 col-md-6 col-lg-4 col-xl-4 animate__animated animate__bounceIn">
                            <div class>
                                
                                    <!-- start of item card  -->
                                <Card ref="cart" class="animate__animated animate__bounceIn">
                                    <Collapse>
                                        <Panel  name="1">
                                           <span class="fw-bolder"> Order #{{order.order_number}} for {{order.company.company_name}}</span>
                                            <div slot="content" >
                                                
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
                                <tr v-for="(orderDetails, i) in order.order_details" :key="i" class="animate__animated animate__fadeIn">
                                <th scope="row">{{i+1}}</th>
                                <td>{{orderDetails.item_name}}</td>
                                <td>{{  orderDetails.price }}</td>
                                <td>{{orderDetails.quantity}}</td>
                                <td>{{orderDetails.amount}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end">Total: </th>
                                    <th  style="text-align:right" colspan="2">GH₵ {{order.order_total}}</th>
                                </tr>
                                <tr>
                                    <th colspan="1"></th>
                                    <th colspan="4"> <div class="d-flex align-items-center justify-content-end"><div>Paid: &nbsp; </div> <div> GH₵ </div> &nbsp; <div><InputNumber @on-change="calcBalance(order, i)"  :min="1" :step="0.5" v-model="order.paid"></InputNumber> </div> </div> </th>
                                </tr>
                                <tr>
                                    <!-- <th colspan="1"></th> -->
                                    <th colspan="5"> <div class="d-flex align-items-center justify-content-end">
                                        <div>Payment type: &nbsp; </div>
                                        
                                        <div>
                                            <Select v-model="payment_type" placeholder="type">
                                                <Option value="cheque">Cheque</Option>
                                                <Option value="cash">Cash</Option>
                                            </Select>
                                        </div> 
                                        </div> 
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="1"></th>
                                    <th colspan="4"> <div class="d-flex align-items-center justify-content-end"><div>Balance: &nbsp; </div> <div> GH₵ </div> &nbsp; <div><InputNumber v-model="order.balance" readonly></InputNumber></div> </div> </th>
                                </tr>
                            </tbody>
                            </table>
                                                <div class=" p-2 d-flex justify-content-between">
                                                    <div class="first pe-2"><Button @click="showPrintModal(order, i)"  type="success">Checkout</Button></div>
                                                    <div class="first pe-2"><Button  type="error">Cancel</Button></div>
                                                </div>
                                            </div>
                                        </Panel>
                                    </Collapse>
                                </Card>
                                        <!-- end item-card-->
                                    </div>
                                    <!-- end wrapper -->
                </div>
                
                <pagination class="mb-2 mt-5" align="center" :data="Orders" @pagination-change-page="list"></pagination>
                </div>
        <div v-else class=" mt-4 row gy-2">
            <h1 class="text-center mt-5">No available orders to Checkout</h1>
        </div>
        </div>

    <!-- MODAL FOR PRINTING  -->
        <Modal
        v-model="printModal"
        :title="printModalTitle"
        footer-hide>
        <div class="print-block" id="print-block">
        <div class="print-content">
        <div class="header fs-16" style="text-align:center">
        <span><b> SomeWhere Restaurant </b></span> <br>
        <span> <b>Beach Resort Avenue Strt 123 </b></span><br>
        <span><b> Sogakope</b></span>
        </div>
        <div class="right-info" style="text-align:right">
           <b> Invoice: </b> {{orderReceiptInvoice}} <br>
           <b> Date:</b> {{orderReceiptDate}} <br>
           <b> Order:</b> {{receiptOrderNumber}} <br>
           <b> Payment type:</b> {{payment_type}} <br>

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
                                <tr v-for="(orderDetails, i) in printOrderData.order_details" :key="i" class="animate__animated animate__fadeIn">
                                <th scope="row">{{i+1}}</th>
                                <td>{{orderDetails.item_name}}</td>
                                <td>{{  orderDetails.price }}</td>
                                <td>{{orderDetails.quantity}}</td>
                                <td>{{orderDetails.amount}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end">Total </th>
                                    <th  style="text-align:right" colspan="2">GH₵ {{printOrderData.order_total}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end">Paid </th>

                                    <th style="text-align:right"  colspan="2"> GH₵ {{paid}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-end ps-2">Balance </th>
                                    <th style="text-align:right" colspan="2"> GH₵ {{balance}}</th>
                                </tr>
                            </tbody>
                            </table>
        </div>
        </div>
        </div>

        <div class="button-footer d-flex justify-content-center">
            <div class="print-checkout-button">
                <Button type="success" @click="checkout">Checkout and Print</Button>
            </div>
        </div>
    </Modal>



    </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'

export default {
    filters: {
        convertToInteger :function (value){
            return parseInt(value)
        },        
    },
    components:{
        pagination
    },
    data(){
        return{
            payment_type: "",
            selectedOrderIndex: null,
            paid: null,
            balance: null,
            orderReceiptDate: null,
            orderReceiptInvoice: null,
            receiptOrderNumber: null,
            printModalTitle: '',
            printModal: false,
            printOrderData: {},
            confirmModal: false,
            Orders:{},
            orderModal: false,

        }
    },
    mounted(){
        this.list()
    },
    methods:{
        async checkout(){	
            if (!this.paid) {
                return this.error("Paid field is empty")
            }
            if (this.paid < this.printOrderData.order_total) {
                return this.error("Paid amount is less than Order total")
            }
            let obj = {
                status: 1,
                balance: this.balance,
                paid: this.paid,
                id: this.printOrderData.id,
                payment_type: this.payment_type
            }
            const res = await this.callApi(
                "post",
                "app/checkout_creditor_order",
                obj
            );
            if (res.data == 1) {
                this.success("Order succesfully checked out")
                printJS({ printable:'print-block',css:'/css/print.css',  type:'html'})
                this.getCreditorReadyOrders()
                this.balance = null
                this.paid= null
                this.payment_type= ""
            }
        },

            calcBalance(order, i){
                console.log(' testing calc')
                this.Orders.data[i].balance =  parseFloat(this.Orders.data[i].paid) - parseFloat(this.Orders.data[i].order_total)
                this.balance = this.Orders.data[i].balance 
                this.paid = parseFloat(this.Orders.data[i].paid)
            },
            showPrintModal(order, i){
                if (this.payment_type.trim() == "")
                return this.error("Payment type is required");
                if (!this.paid) {
                    return this.error("Paid field is empty")
                }
                if (this.paid < order.order_total) {
                    return this.error("Paid amount is less than Order total")
                }
                this.selectedOrderIndex = i
                let today = new Date();
                let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                let dateTime = date+' '+time;
                this.orderReceiptDate = dateTime
                this.orderReceiptInvoice = order.invoice_number
                this.receiptOrderNumber = order.order_number
                this.printModal = true
                this.printOrderData = order
                this.paid = order.paid
                this.balance = order.balance
                this.printOrderData = order
                this.printModalTitle = 'Order #' +  order.order_number
                
            },
        closeModal(){
            this.orderModal = false
        },
            async list(page=1){
            await axios.get(`/app/get_creditor_ready_orders?page=${page}`).then(({data})=>{
                this.Orders = data
            }).catch(({ response })=>{
                console.error(response)
            })
        },
        async getCreditorReadyOrders(){
            const getCreditorReadyOrders = await this.callApi("get", "app/get_creditor_ready_orders");
            if (getCreditorReadyOrders.status == 200) {
                this.Orders = getCreditorReadyOrders.data;
            } else {
                this.swr();
            }
        }
    },
    created(){
        this.getCreditorReadyOrders()
    }
}
</script>

<style scoped>
.money_cell{
    padding: 5px 0 !important;
}
</style>