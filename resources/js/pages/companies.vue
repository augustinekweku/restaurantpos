<template>
    <div class=" container categories animate__animated animate__fadeIn">
        <h2 class="text-center">Companies</h2>
        <div class="d-flex justify-content-end ">
            <div class="add_button">
                <Button @click="addModal = true">Add</Button>
            </div>
        </div>

        <div class>
            <div class="companies_table">
                <vue-good-table :columns="columns1" :line-numbers="true" :rows="companies" @on-row-dblclick="onRowDoubleClick"></vue-good-table>
            </div>
        </div>
        <!-- company adding modal -->
        <Modal :closable="false" :mask-closable="false" title="Add Company" v-model="addModal">
            <div class="space">
                <Input placeholder="Company Name" type="text" v-model="form.company_name"/>
            </div>
            <div class="space">
                <Input placeholder="About" type="textarea" v-model="form.about"/>
            </div>
            <div slot="footer">
                <Button @click="addModal = false" type="error">Close</Button>
                <Button :disabled="isAdding" :loading="isAdding" @click="addCompany" type="primary">{{ isAdding ? "Adding..." : "Add Company" }}</Button>
            </div>
        </Modal>

        <!--  Company EDITING MODAL -->
        <Modal :closable="false" :mask-closable="false" title="Edit Company" v-model="editModal">
            <div class="space">
                <Input placeholder="Company Name" type="text" v-model="editData.company_name"/>
            </div>
            <div class="space">
                <Input placeholder="" type="textarea" v-model="editData.about"/>
            </div>


            <div slot="footer">
                <Button @click="closeEditModal" type="error">Close</Button>
                <Button :disabled="isEditing" :loading="isEditing" @click="editRole" type="primary">{{ isEditing ? "Editing..." : "Edit Company" }}</Button>
                <div class="text-center more_actions my-2">
                    <Collapse v-model="value1">
                        <Panel name="1">
                            More Actions
                            <p slot="content">
                                <Button @click="showDeletingModal" type="error">Delete Company</Button>
                            </p>
                        </Panel>
                    </Collapse>
                </div>
            </div>
        </Modal>
        <!-- END OF CATEGORY EDITING MODAL -->


        <deleteModal></deleteModal>
    </div>
</template>
<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        deleteModal
    },
    data() {
        return {
            value1: '',
            addModal: false,
            isAdding: false,
            form: {
                company_name: "",
                about: ""
            },
            editData: {
                company_name: "",
                about: "",
            },
            columns1: [
                {
                    label: "Company Name",
                    field: "company_name"
                },
                {
                    label: "About",
                    field: "about"
                },
                {
                    label: "Created At",
                    field: "created_at"
                }
            ],
            companies: [],
            selectedRow: [],
            selectedIndex: 0,
            editModal: false,
            isEditing: false,
            isEditingItem: false
        };
    },
    methods: {
        closeEditModal(){
            this.editModal = false
            this.isEditingItem = false
            this.isEditing =false
        },
        onRowDoubleClick(params) {
            // params.row - row object
            // params.pageIndex - index of this row on the current page.
            // params.selected - if selection is enabled this argument
            // indicates selected or not
            // params.event - click event
            let obj = {
        				id : params.row.id,
                        company_name: params.row.company_name,
                        about: params.row.about

                    }
        			this.editData = obj
                    this.editModal = true
                    this.selectedIndex = params.row.pageIndex
                    this.selectedRow = params.row
                    this.isEditingItem = true

          },
        showDeletingModal() {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_company",
                data: this.selectedRow,
                isDeleted: false,
                msg: "Are you sure you want to delete this Company?",
                successMsg: "Company deleted successfully"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            //console.log('delete method called ')
        },
        async editRole() {
            if (this.editData.company_name.trim() == "")
                return this.error("Category name is required");
            if (this.editData.about.trim() == "")
                return this.error("About is required");
            this.isEditing = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/edit_company",
                this.editData
            );
            if (res.status === 200) {
                console.log(this.editData);
                this.getCompanies();
                this.isEditing = false;
                this.$Progress.finish();
                this.success("Company edited successfully");
                //console.log(this.editData)
                this.editModal = false;
                this.isEditingItem = false
            } else {
                if (res.status === 422) {
                    this.isEditing = false;
                    this.$Progress.fail();
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.isEditing = false;
                    this.$Progress.fail();
                    this.swr();
                }
            }
        },
        async addCompany() {
            if (this.form.company_name.trim() == ""){
                return this.error("Company name is required"); }
            if (this.form.about.trim() == ""){
                return this.error("About is required"); }

            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_company",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("Company added successfully");
                this.companies.unshift(res.data);
                this.addModal = false;
                this.form.company_name = "";
                this.form.about = "";
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
            async getCompanies() {
            const getCompanies = await this.callApi("get", "app/get_companies");
            if ((getCompanies.status = 200)) {
                this.companies = getCompanies.data;
                //console.log(getCompanies.data);
            } else {
                this.swr();
            }
        }

    },
    async created() {
        this.getCompanies()
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if (obj.isDeleted) {
                console.log(obj);
                this.getCompanies();
                this.editModal = false
            }
        }
    }
};
</script>

<style scoped></style>
