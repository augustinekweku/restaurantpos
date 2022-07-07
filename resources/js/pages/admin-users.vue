<template>
    <div class="admin-users animate__animated animate__fadeIn">
        <div class="d-flex justify-content-end ">
            <div class="add_button">
                <Button class="m-1 shadow" type="warning" @click="addModal = true">Add</Button>
            </div>
        </div>

        <div >
            <div class="users_table">
                <vue-good-table
                :columns="columns1"
                @on-row-dblclick="onRowDoubleClick"
                    :rows="users"
                    :line-numbers="true"
                    :search-options="{ enabled: true }"
                    :pagination-options="{
                            enabled: true
                        }"
                    >
                </vue-good-table>
            </div>
        </div>
        <!-- Staff adding modal -->
        <Modal
            v-model="addModal"
            title="Add User"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="form.firstName"
                    placeholder="First Name"
                />
            </div>
            <div class="space">
                <Input
                    type="text"
                    v-model="form.lastName"
                    placeholder="Last Name"
                />
            </div>
            <div class="space">
                <Input type="email" v-model="form.email" placeholder="Email" />
            </div>
            <div class="space">
                <vue-tel-input
                    validCharactersOnly
                    mode="international"
                    v-model="form.phone"
                ></vue-tel-input>
            </div>
            <div class="space">
                <Input
                    type="password"
                    v-model="form.password"
                    placeholder="Password"
                />
            </div>
            <div class="space">
                <Input
                    type="password"
                    v-model="form.password2"
                    placeholder="Confirm Password"
                />
            </div>
            <div class="space">
                <Select
                    v-model="form.userType"
                    placeholder="Select User type"
                    style="width:200px"
                >
                    <Option value="admin">Admin</Option>
                    <Option value="waiter">Waiter</Option>
                    <Option value="cook">Cook</Option>
                </Select>
            </div>
            <div class="space"></div>

            <div slot="footer">
                <Button type="error" @click="addModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="addUser"
                    :disabled="isAdding"
                    :loading="isAdding"
                    >{{ isAdding ? "Adding..." : "Add User" }}</Button
                >
            </div>
        </Modal>
        <!-- END OF ADDING  MODAL -->

        <!-- Staff editing modal -->
        <Modal
            v-model="editModal"
            title="Edit User"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.firstName"
                    placeholder="First Name"
                />
            </div>
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.lastName"
                    placeholder="Last Name"
                />
            </div>
            <div class="space">
                <Input
                    type="email"
                    v-model="editData.email"
                    placeholder="Email"
                />
            </div>
            <div class="space">
                <vue-tel-input
                    validCharactersOnly
                    mode="international"
                    v-model="editData.phone"
                ></vue-tel-input>
            </div>
            <div class="space">
                <Input
                    type="password"
                    v-model="editData.password"
                    placeholder="Password"
                />
            </div>
            <div class="space">
                <Input
                    type="password"
                    v-model="editData.password2"
                    placeholder="Confirm Password"
                />
            </div>
            <div class="space">
                <Select
                    v-model="editData.userType"
                    placeholder="Select User type"
                    style="width:200px"
                >
                    <Option value="admin">Admin</Option>
                    <Option value="waiter">Waiter</Option>
                    <Option value="cook">Cook</Option>
                </Select>
            </div>
            <div class="space"></div>

            <div slot="footer">
                <Button type="error" @click="editModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="editUser"
                    :disabled="isEditing"
                    :loading="isEditing"
                    >{{ isEditing ? "Editing..." : "Edit User" }}</Button
                >
                <div class="text-center more_actions my-2">
<Collapse v-model="value1">
        <Panel name="1">
            More Actions
            <p slot="content">
                <Button type="error" @click="showDeletingModal">Delete User</Button>
            </p>
        </Panel>
    </Collapse>
    </div>
            </div>
        </Modal>

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
            editModal: false,
            form: {
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                password2: "",
                userType: "",
                phone: "editData"
            },
            editData: {
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                password2: "",
                userType: "",
                phone: ""
            },
            columns1: [
                {
                    label: "First Name",
                    field: "firstName"
                },
                {
                    label: "Last Name",
                    field: "lastName"
                },
                {
                    label: "Email",
                    field: "email"
                },
                {
                    label: "User Type",
                    field: "userType"
                },
                {
                    label: "Phone",
                    field: "phone"
                }
            ],
            users: [],
            contextLine: 0,
            selectedRow: [],
            selectedIndex: 0,
            editModal: false,
            isEditing: false
        };
    },
    methods: {
                    onRowDoubleClick(params) {
            // params.row - row object
            // params.pageIndex - index of this row on the current page.
            // params.selected - if selection is enabled this argument
            // indicates selected or not
            // params.event - click event
            let obj = {
        				id : params.row.id,
                        firstName: params.row.firstName,
                        lastName: params.row.lastName,
                        email: params.row.email,
                        userType: params.row.userType,
                        phone: params.row.phone,
                        role_id: params.row.role_id,
                        password: params.row.password
                    }
        			this.editData = obj
                    this.editModal = true
                    this.selectedIndex = params.row.pageIndex
                    this.selectedRow = params.row

          },

        showDeletingModal() {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_user",
                data: this.selectedRow,
                isDeleted: false,
                msg: "Are you sure you want to delete this user?",
                successMsg: "User deleted successfully"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            //console.log('delete method called ')
        },
        async editUser() {
            if (this.editData.firstName.trim() == "")
                return this.error("First name is required");
            if (this.editData.lastName.trim() == "")
                return this.error("Last name is required");
            if (this.editData.email.trim() == "")
                return this.error("Email is required");
            if (this.editData.userType.trim() == "")
                return this.error("User type is required");
            if (this.editData.phone.trim() == "")
                return this.error("Phone is required");
            if (this.editData.password !== this.editData.password2) {
                return this.error("Passwords do not match");
            }
            this.isEditing = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/edit_user",
                this.editData
            );
            if (res.status === 200) {
                console.log(this.editData);
                this.getUsers();
                this.isEditing = false;
                this.$Progress.finish();
                this.success("User edited successfully");
                //console.log(this.editData)
                this.editModal = false;
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
        async addUser() {
            if (this.form.firstName.trim() == "")
                return this.error("First name is required");
            if (this.form.lastName.trim() == "")
                return this.error("Last name is required");
            if (this.form.email.trim() == "")
                return this.error("Email is required");
            if (this.form.password.trim() == "")
                return this.error("Password is required");
            if (this.form.userType === "")
                return this.error("User type is required");
            if (this.form.phone.trim() == "")
                return this.error("Phone is required");
            if (this.form.password !== this.form.password2) {
                return this.error("Passwords do not match");
            }
            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_user",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("User added successfully");
                this.users.unshift(res.data);
                this.addModal = false;
                this.form.firstName = "";
                this.form.lastName = "";
                this.form.email = "";
                this.form.password = "";
                this.form.password2 = "";
                this.form.userType = "";
                this.form.phone = "";
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
        async getUsers() {
            const getUsers = await this.callApi("get", "app/get_users");
            if ((getUsers.status = 201)) {
                this.users = getUsers.data;
                //console.log(getUsers.data);
            } else {
                this.swr();
            }
        }
    },
    async created() {
        const getUsers = await this.callApi("get", "app/get_users");
        if ((getUsers.status = 201)) {
            this.users = getUsers.data;
            //console.log(getUsers.data);
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
                this.getUsers();
                this.editModal = false
            }
        }
    }
};
</script>

<style scoped>
.admin-users {
    margin: 0;
    padding: 15px;
}
.add_button {
}
.space {
    margin: 10px 0px;
}

</style>
