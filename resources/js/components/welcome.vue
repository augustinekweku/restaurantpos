<template>
    <div class="welcome-page container-fluid">
                <div class="row" style="">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="hero-text p-5">
                                <h1 class="company-title display-2 animate__animated animate__slideInLeft">Somewhere Restaurant</h1>
                                <h4 class=" animate__animated animate__slideInUp">We serve nothing but the best</h4>
                                <h4>Welcome, {{userSession.firstName}}</h4>
                            </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 ">
                        <div class="right-img d-flex align-items-center pt-5">
                            <div class="animate__animated animate__slideInRight">
                            <img src="/svg/hero-svg.svg" alt="" height="400" srcset="">
                            </div>
                        </div>
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
@font-face {
    font-family: 'small';
    src: url("https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&display=swap");
}
.welcome-page{
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
    margin-top: 150px !important;
    padding: 10px;
    color: black;
}
.company-title{
    text-shadow: black;
}
</style>