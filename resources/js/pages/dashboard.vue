<template>
    <div class="dashboard-page container-fluid ">
                <div class="row gy-2 p-5" style="">
                    <div class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <Card to="/pos-page" class="shadow animate__animated animate__bounceIn">        
                                <div style="text-align:center">
                                    <Icon type="md-cart" color="orange" size="50"/>
                                    <h5 class="fw-light">POS</h5>
                                </div>
                            </Card>
                    </div>
                    
                    <div class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <Card to="/ready-orders" class="shadow animate__animated animate__bounceIn">        
                                <div style="text-align:center">
                                    <Icon type="md-clock" color="orange" size="50"/>
                                    <h5 class="fw-light">Ready Orders</h5>
                                </div>
                            </Card>
                    </div>
                    
                    <div class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <Card to="/orders" class="shadow animate__animated animate__bounceIn">        
                                <div style="text-align:center">
                                    <Icon type="ios-clock" color="orange" size="50"/>
                                    <h5 class="fw-light">Orders</h5>
                                </div>
                            </Card>
                    </div>


                    <div class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <Card to="/users" class="shadow animate__animated animate__bounceIn">        
                                <div style="text-align:center">
                                    <Icon type="md-people" color="orange" size="50"/>
                                    <h5 class="fw-light">Users</h5>
                                </div>
                            </Card>
                    </div>

                    <div class="mb-4 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <Card to="/reports" class="shadow animate__animated animate__bounceIn">        
                                <div style="text-align:center">
                                    <Icon type="md-analytics" color="orange" size="50"/>
                                    <h5 class="fw-light">Reports</h5>
                                </div>
                            </Card>
                    </div>
                    
                </div>
    </div>
</template>
<script>
export default {
    props: [],
    data(){
        return{
            userSession: []
        }
    },
        created() {
        console.log("state",this.$store.state.user)
        this.userSession = this.$store.state.user
        console.log(this.$route)


    }
}
</script>
<style scoped>
.dashboard-page{
    background-image:url('/svg/meal-background.svg');
    background-repeat: repeat-y !important;
    background-size: cover;
    height: 100vh;
    color: rgb(255, 255, 255);
    font-family: 'Noto Sans JP', sans-serif;
}
.container-fluid{
    margin: 0 !important;
}
.hero-text {
    padding: 10px;
    color: black;
}
.company-title{
    text-shadow: black;
}
a{
    text-decoration: none !important;
}
</style>