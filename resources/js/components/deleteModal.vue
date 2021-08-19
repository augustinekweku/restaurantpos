<template>
    <div>
                <!-- delete alert modal -->
        <Modal
        :mask-closable="false"
		:closable="false"
        :value="getDeleteModalObj.showDeleteModal" 
        width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>{{getDeleteModalObj.msg}}</p>
            </div>
            <div slot="footer">
                <Button
                    type="default"
                    size="large"
                    @click="closeModal"
                    >Cancel</Button
                >                
                <Button
                    type="error"
                    size="large"
                    :loading="isDeleting"
                    :disabled="isDeleting"
                    @click="deleteRow"
                    >Delete</Button
                >
            </div>
        </Modal>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    data() {
        return{
            isDeleting: false,
        }
    },
    methods: {
        async deleteRow() {

            this.isDeleting = true;
            const res = await this.callApi(
                "post",
                this.getDeleteModalObj.deleteUrl,
                this.getDeleteModalObj.data
            );
            if (res.status === 200) {
                this.success(this.getDeleteModalObj.successMsg);
                this.$store.commit('setDeleteModal', true)

            } else {
                this.swr();
                this.$store.commit('setDeleteModal', false)
                
            }
            this.isDeleting = false;
        },
        closeModal() {
            this.$store.commit('setDeleteModal', false)
        }
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    }
}
</script>