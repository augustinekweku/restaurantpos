import { mapGetters } from  'vuex'
export default {
    data() {
        return{

        }
    },
    methods: {
    async callApi(method, url, dataObj) {
            // Send a POST request
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });               
            } catch (e) {
                return e.response
            }

        },
    
        i (desc, title= 'Hey') {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },

        success (desc, title='Great!') {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning (desc, title='Oops! ') {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error (desc, title='Oops!') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr (desc='Something went wrong Please try again', title='Hey') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },

    },
    computed: {

    }
}