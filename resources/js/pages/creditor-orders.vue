<template>
    <div class="my-3 orders_page container mx-3 animate__animated animate__fadeIn">
        <div class="row gx-5 gy-2">
            <h2 class="text-center mb-3">Orders</h2>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Select placeholder="Select Category" width="40px">
                    <Option
                        value="1">1</Option
                    >
                </Select>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Button type="warning"
                    >Filter <Icon type="md-options" />
                </Button>
                
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Button type="warning"
                    >Show All <Icon type="md-medical" />
                </Button>
            </div>
        </div>
        <div v-if="Orders.data" class=" mt-4 gy-2">
                <div v-if="Orders.data.length" class="row">
                <div v-for="(order, i) in Orders.data" :key="i"
                    class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3 animate__animated animate__bounceIn">
                            <div class>
                                
                                    <!-- start of item card  -->
                                <Card class="animate__animated animate__bounceIn">
                                    <Collapse>
                                        <Panel class="text-center" name="1">
                                           <span class="fw-bold">  Due: <span style="color:red;">{{order.due_date}}</span> </span>
                                            <div slot="content" class="px-4">
                                                <div class="d-flex justify-content-between">
                                                    <div> Order # {{order.order_number}}</div>
                                                    <div>
                                                    <Button size="small" type="primary" @click="changeStatus(order, i)">Serve</Button>
                                                    </div>
                                                </div>
                                                <div class="my-1">
                                                    <p>By {{order.company.company_name}}</p>
                                                    <p>{{order.company.about}}</p>
                                                </div>
                                            </div>
                                        </Panel>
                                    </Collapse>
                                    <ul class="p-2">
                                        <li v-for="(orderDetail, i) in order.creditor_order_details" :key="i">
                                            <p class="fs-6 pb-1">
                                                {{orderDetail.item_name}} X {{orderDetail.quantity}}
                                            </p>
                                        </li>
                                    </ul>
                                </Card>
                                        <!-- end item-card-->
                                    </div>
                                    <!-- end wrapper -->
                </div>
                
                <pagination class="mb-2 mt-5" align="center" :data="Orders" @pagination-change-page="list"></pagination>
                </div>
        <div v-else class=" mt-4 row gy-2">
            <h1 class="text-center mt-5">No available orders to serve</h1>
        </div>
        </div>





    </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'

export default {
    components:{
        pagination
    },
    data(){
        return{
            confirmModal: false,
            Orders:{},
            orderModal: false,

        }
    },
    mounted(){
        this.list()
    },
    methods:{
        async changeStatus(order, i) {
            console.log(order.ready)
            let init = order.ready
            let order_id = order.id
            this.$swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Meal is ready!'
            }).then((result) => {
            if (result.isConfirmed) {
            axios.post(`/app/creditor_order_confirmed_by_cook/${order_id}`).then(({data})=>{
                //console.log(data)
                    this.Orders.data.splice(i, 1)
                    this.$swal.fire(
                    'Done!',
                    'Order Successfully served',
                    'success'
                    )
            }).catch(({ response })=>{
                console.error(response)
            })
            }else{
                return order.ready = !order.ready
            }
            })
            },
        closeModal(){
            this.orderModal = false
        },
            async list(page=1){
            await axios.get(`/app/get_requested_creditor_orders?page=${page}`).then(({data})=>{
                this.Orders = data
            }).catch(({ response })=>{
                console.error(response)
            })
        },
        async getRequestedCreditorOrders(){
            const getRequestedCreditorOrders = await this.callApi("get", "app/get_requested_creditor_orders");
            if (getRequestedCreditorOrders.status == 200) {
                this.Orders = getRequestedCreditorOrders.data;
            } else {
                this.swr();
            }
        }
    },
    created(){
        this.getRequestedCreditorOrders()
    }
}
</script>

<style scoped>

</style>