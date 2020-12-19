new Vue({
    el: '#chat',
    data: {
        text: '',
        room: '',
        roomId:'',
    },
    methods: {
        getMessages() {
            axios.get(chat.show).then(res => {
                // propsで渡されたmessagesをarrayに入れている
                　
                this.array = res.data
            })
        },

        send() {
            let obj = {
                text: this.text
            }
            
　　　　　　
            axios.post(`chat/{${this.roomId}}/store`, obj).then(res => {
                // this.getMessages()
            }).catch(function(error) {
                console.log(error);
            })
        },


    }
});
