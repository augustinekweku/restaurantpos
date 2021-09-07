<template>
    <div class=" my-3 container categories animate__animated animate__fadeIn">
        <h2 class="text-center">Roles</h2>
        <div class="d-flex justify-content-end ">
            <div class="add_button">
                <Button @click="addModal = true">Add</Button>
            </div>
        </div>

        <div class>
            <div class="roles_table">
                <vue-good-table
                :columns="columns1"
                @on-row-dblclick="onRowDoubleClick"
                    :rows="roles"
                    :line-numbers="true"
                    >
                </vue-good-table>
            </div>
        </div>
        <!-- CATEGORY adding modal -->
        <Modal
            v-model="addModal"
            title="Add Role"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="form.role_name"
                    placeholder="Role Name"
                />
            </div>
            <div slot="footer">
                <Button type="error" @click="addModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="addRole"
                    :disabled="isAdding"
                    :loading="isAdding"
                    >{{ isAdding ? "Adding..." : "Add Role" }}</Button
                >
            </div>
        </Modal>

    <!--  CATEGORY EDITING MODAL -->
        <Modal
            v-model="editModal"
            title="Edit Category"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.role_name"
                    placeholder="Role Name"
                />
            </div>


            <div slot="footer">
                <Button type="error" @click="closeEditModal">Close</Button>
                <Button
                    type="primary"
                    @click="editRole"
                    :disabled="isEditing"
                    :loading="isEditing"
                    >{{ isEditing ? "Editing..." : "Edit Role" }}</Button
                >
                                <div class="text-center more_actions my-2">
<Collapse v-model="value1">
        <Panel name="1">
            More Actions
            <p slot="content">
                <Button type="error" @click="showDeletingModal">Delete Role</Button>
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
            token: '',
            form: {
                role_name: "",
            },
            editData: {
                role_name: "",
            },
            columns1: [
                {
                    label: "Role Name",
                    field: "role_name"
                },
                {
                    label: "Created At",
                    field: "created_at"
                }
            ],
            roles: [],
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
                        role_name: params.row.role_name

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
                deleteUrl: "app/delete_category",
                data: this.selectedRow,
                isDeleted: false,
                msg: "Are you sure you want to delete this category?",
                successMsg: "Category deleted successfully"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            //console.log('delete method called ')
        },
        async editRole() {
            if (this.editData.category_name.trim() == "")
                return this.error("Category name is required");
            if (this.editData.desc.trim() == "")
                return this.error("Description is required");
            if (this.editData.image.trim() == "")
                return this.error("Imgae is required");

            this.isEditing = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/edit_category",
                this.editData
            );
            if (res.status === 200) {
                console.log(this.editData);
                this.getCategories();
                this.isEditing = false;
                this.$Progress.finish();
                this.success("Category edited successfully");
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
        async addRole() {
            if (this.form.role_name.trim() == "")
                return this.error("Category name is required");

            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_role",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("Category added successfully");
                this.categories.unshift(res.data);
                this.addModal = false;
                this.form.role_name = "";
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

    },
    async created() {
        const getRoles = await this.callApi("get", "app/get_roles");
        if ((getRoles.status = 201)) {
            this.roles = getRoles.data;
            console.log(getRoles.data);
        } else {
            this.swr();
        }
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if (obj.isDeleted) {
                console.log(obj);
                this.getCategories();
                this.editModal = false
            }
        }
    }
};
</script>

<style scoped>



</style>
