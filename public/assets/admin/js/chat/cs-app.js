const WS_URL = $('meta[name=ws_url]').attr("content");
const USER_ID = Number($('meta[name=user_id]').attr("content"));
const USER_IMAGE_URL = $('meta[name=user_image_url]').attr("content");
const CONVERSATION_ID = Number($('meta[name=conversation_id]').attr("content"));
var socket = io(WS_URL, { query: "id= "+USER_ID });

var csApp = new Vue({
    el: '#cleanSmart',
    data: {
        user_id: USER_ID,
        conversation_id: CONVERSATION_ID,
        ws_url: WS_URL,
        msgCount: 0,
    },
    methods: {
        updateMsgCount: function(count){
            this.msgCount = count;
            //socket.emit('getMessages', {conversation_id: this.receiverDetails.conversation_id});
        }
    },
    mounted(){
        socket.on('updateMsgCount', this.updateMsgCount);
    },
    destroyed: function() {
        
    },
    computed: {
    }
});

socket.on('connect', function(data){
    // csApp.socketConnected.status = true;
    //socket.emit('chatList',app.user_id,app.conversation_id);
});
socket.on('connect_error', function(){
    // csApp.socketConnected.status = false;
    // csApp.socketConnected.msg = 'Could not connect to server';
});