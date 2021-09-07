<template>
    <div class="table_page mx-3  m-2 animate__animated animate__fadeIn">
        <Button @click="addModal=true" class="add_fab shadow" type="primary" size="large" icon="md-add" shape="circle"></Button>
            <h2 class="text-center mb-3">Tables</h2>
            <div class=" mt-4 row gy-2">
                <div v-for="(table, i) in tables" :key="i"
                    class="col-sm-4 col-md-3 col-lg-2 col-xl-2 animate__animated animate__bounceIn">
                    <div>
                            <Card class="shadow">        
                                <Button v-show="table.status==='empty'" slot="extra" :size="buttonSize" type="success"  shape="circle"> {{table.status}}</Button>
                                <Button v-show="table.status==='occupied'" slot="extra" :size="buttonSize" type="error"  shape="circle"> {{table.status}}</Button>
                                <Button v-show="table.status==='unpaid'" slot="extra" :size="buttonSize" type="warning"  shape="circle"> {{table.status}}</Button>
                                <div style="text-align:center">
                                    <Icon type="md-restaurant" color="orange" size="50"/>
                                    <h5 class="fw-light">{{table.table_name}}</h5>
                                </div>
                            </Card>
                    </div>
                </div>
            </div>


                    <!-- Table adding modal -->
        <Modal
            v-model="addModal"
            title="Add Table"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="form.table_name"
                    placeholder="Table Name"
                />
            </div>
            <div slot="footer">
                <Button type="error" @click="addModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="addTable"
                    :disabled="isAdding"
                    :loading="isAdding"
                    >{{ isAdding ? "Adding..." : "Add Table" }}</Button
                >
            </div>
        </Modal>
    </div>    
</template>

<script>
export default {
    data(){
        return{
            buttonSize: 'small',
            addModal: false,
            isAdding: false,
            token: '',
            form: {
                table_name: "",
                status: 3
            },
            tables:[],

        }
    },
    methods:{
        async addTable() {
            if (this.form.table_name.trim() == "")
                return this.error("Table name is required");

            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_table",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("Table added successfully");
                //this.tables.unshift(res.data);
                this.getAllTables()
                this.addModal = false;
                this.form.table_name = "";
            } else {
                if (res.status === 422) {
                    this.isAdding = false;
                    this.$Progress.fail();
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else if (res.status === 500) {
                    this.isAdding = false;
                    //alert(res.status)
                    console.log(res.data);
                    this.$Progress.fail();
                    this.error(res.data.message);
                } else {
                    this.isAdding = false;
                    this.$Progress.fail();
                    this.swr();
                }
            }
        },
            async getAllTables() {
            const getTables = await this.callApi(
                "get",
                "app/get_all_tables"
            );
            if ((getTables.status = 200)) {
                this.tables = getTables.data;
                console.log(getTables.data);
            } else {
                this.swr();
            }
        }
    },
    created(){
        this.getAllTables()
    }
}
</script>
<style scoped>

</style>