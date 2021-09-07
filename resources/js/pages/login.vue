<template>
<div class="login-page">
    <div class="container login-block">

        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="login-card _1adminOverveiw_table_recent shadow _box_shadow _border_radious _mar_b30 _p20 col-md-4">
            <div class="login_header">
                <h2>SomeWhere</h2>
            </div>
            <div class="space">
            <Input type="email" v-model="data.email"  placeholder="Email" />                        
            </div>                    
            <div class="space">
            <Input type="password" v-model="data.password" placeholder="Password" />
            </div>
            <div class="login_footer">
                <Button type="primary" :disabled="isLogging" :loading="isLogging" @click="login">{{isLogging ? 'Logging in' : 'Login'}}</Button>
            </div>
        </div>
    </div>

</div>
</template>

<script>
export default {
    data() {
        return{
            data: {
                email: '',
                password: '',
            },
            isLogging: false,
        }
    },
    methods: {
        async login() {
			if (this.data.email.trim()=='') return this.error('Email is required');
			if (this.data.password.trim()=='') return this.error('Password is required');
            if (this.data.password.length < 6) return this.error('Incorrect Login credentials');
            this.isLogging = true
            const res = await this.callApi('post','app/login', this.data)
            if (res.status === 200) {
                this.success(res.data.msg)
                window.location = '/'
            }else{
                if (res.status === 401) {
                    this.error(res.data.msg)
                }else if (res.status === 422) {
                for(let i in res.data.errors){
						this.error(res.data.errors[i][0])
					}    
                }
                else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    }
}
</script>

<style scoped>
._1adminOverveiw_table_recent {
    margin: 0 auto;
    /* margin-top: 220px; */
    /* border: 1px solid grey; */
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.login_footer, .login_header {
    text-align: center;
}
.login_header{
    margin-bottom: 15px;
}
.login-page{
    background-image:url('/svg/meal-background.svg');
    background-repeat: repeat-y !important;
    background-size: cover;
    margin-top: -60px;
    height: 100vh;
    color: rgb(255, 255, 255);
    font-family: 'Noto Sans JP', sans-serif;
}
.login-block{
    padding-top: 240px;
}
.login-card{
    background: rgb(251, 251, 251);
    color: #000;
}
</style>