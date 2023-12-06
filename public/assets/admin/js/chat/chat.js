const WS_URL = $('meta[name=ws_url]').attr("content");
const USER_ID = Number($('meta[name=user_id]').attr("content"));
const USER_IMAGE_URL = $('meta[name=user_image_url]').attr("content");
const CONVERSATION_ID = Number($('meta[name=conversation_id]').attr("content"));
var socket = io(WS_URL, { query: "id= "+USER_ID });

Vue.filter('str_limit', function (value, size) {
    if (!value) return '';
    value = value.toString();
  
    if (value.length <= size) {
      return value;
    }
    return value.substr(0, size) + '...';
});

var app = new Vue({
    el: '#chatApp',
    data: {
        user_id: USER_ID,
        conversation_id: CONVERSATION_ID,
        ws_url: WS_URL,
        keyword: '',
        authUser: [],
        chatLists: [],
        receiverDetails: "",
        msgCount: 0,
        socketConnected : {
            status: false,
            msg: 'Connecting Please Wait...'
        },
        messages: [],
        messagesMap: [],
        suggestUsersList: [],
        showMsgBox: false,
        message: '',
        typing: '',
        loading: false,
        timeout: '',
        bArr: {},
        loadFiles: []
    },
    methods: {
        chat: function(chat,socketId=null){
            this.receiverDetails =   chat;
            if(chat.type == "group"){
                socket.emit('getGroupMembers', {conversation_id: this.receiverDetails.conversation_id,socketId: socketId});
                /* var roomParam = {'name':chat.full_name, 'room':chat.full_name};
                socket.emit('join', roomParam); */
                //socket.join('some room');
            }

            this.showMsgBox = true;
            chat.msgCount = 0;
            this.messages   =   [];
            socket.emit('getMessages', {conversation_id: this.receiverDetails.conversation_id, sender_id:this.user_id});
        },
        loadMoreChat: function(nextPage){
            socket.emit('getMessages', {conversation_id: this.receiverDetails.conversation_id, sender_id:this.user_id,page:nextPage})
        },
        sendMessage: function(event, type=""){
            if((event.shiftKey === false && event.keyCode === 13) || type=="submit"){
                if (this.message.length > 0) {
                    let messagePacket = this.createMsgObj('text', '', this.message);
                    socket.emit('addMessage', messagePacket);
                    socket.emit('getUnreadMsgCount', {user_id: this.user_id});
                    this.messages.messages.push(messagePacket);
                    if(this.messagesMap && (this.messagesMap.length>0)){
                        let lastPosition = this.messagesMap.length-1;
                        this.messagesMap[lastPosition].push(messagePacket);
                    }else{
                        var msgArray = [];
                        msgArray.push(messagePacket);
                        this.messagesMap.push(msgArray);
                    }
                    this.message = "";
                    this.scrollToBottom();
                    app.chatLists.findIndex(function(el) {
                        if(el.conversation_id == messagePacket.conversationId){
                            el.msgCount = 0;
                        }
                    });
                    socket.emit('chatList',app.user_id,app.conversation_id);
                    this.resetMessageText();
                }else{
                    alert("Please Enter Your Message.");
                }
            }else{
                if((event.keyCode != 116) && (event.keyCode != 82 && !event.ctrlKey)){
                    socket.emit('typing', {typing:'typing...',socket_id:this.receiverDetails.socket_id});
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(this.timeoutFunction, 500);
                }
            }
        },
        resetMessageText : function() {
            this.message = "";
            this.message = null;
        },
        timeoutFunction: function(){
            socket.emit("typing", {typing:false,socket_id:this.receiverDetails.socket_id});
        },
        scrollToBottom: function(){
            //$("#chatboxscroll-"+this.receiverDetails.id).stop().animate({ scrollTop: $("#chatboxscroll-"+this.receiverDetails.id)[0].scrollHeight}, 1);
            if($(".chat_container")[0]){
                $(".chat_container").stop().animate({ scrollTop: $(".chat_container")[0].scrollHeight}, 1);
            }
        },
        createMsgObj : function(type, file_format, message){
            return {
                type: type,
                file_format: file_format,
                file_path: '',
                sender_id: this.user_id,
                sender_name: this.authUser.full_name,
                sender_avtar: this.authUser.avtar,
                receiver_id: this.receiverDetails.id,
                conversationId: this.receiverDetails.conversation_id,
                toSocketId: this.receiverDetails.socket_id,
                message: message,
                receiver_mode: this.receiverDetails.type,
                time: new moment().utcOffset('+1100').format("hh:mm A"),
                date: new moment().utcOffset('+1100').format("Y-MM-D")
            }
        },
        addMessageResponse: function(data){
            socket.emit('getUnreadMsgCount', {user_id: this.user_id});
            if (data){
                if(this.receiverDetails.type == "group" && data.sender_id == this.receiverDetails.id) {
                    this.messages.messages.push(data);
                }else if(data.conversationId == this.receiverDetails.conversation_id){
                    this.messages.messages.push(data);
                    if(this.messagesMap && (this.messagesMap.length>0)){
                        let lastPosition = this.messagesMap.length-1;
                        this.messagesMap[lastPosition].push(data);
                    }
                }else{
                    this.messages.messages.push(data);
                    if(this.messagesMap && (this.messagesMap.length>0)){
                        let lastPosition = this.messagesMap.length-1;
                        this.messagesMap[lastPosition].push(data);
                    }
                }
                this.$nextTick(function () {
                    this.scrollToBottom();
                });
                socket.emit('chatList',app.user_id,app.conversation_id);
            }
        },
        getUnreadMsgCountResponse: function(data){
            if(data.result.unread_count && data.result.unread_count > 0){
                var msgCount = data.result.unread_count;
            }else{
                var msgCount = 0;
            }
            app.msgCount = msgCount;
            $(".totalMsgCount").text(msgCount);
        },
        getGroupMembersResponse: function(data){
            this.receiverDetails.members = [];
            if(data.result){
                this.receiverDetails.members = data.result;
            }else{
                this.receiverDetails.members = [];
            }
            //this.chat(this.receiverDetails);
        },
        typingListener: function(data){
            if (data.typing && data.to_socket_id == this.receiverDetails.socket_id) {
                this.typing = "is "+data.typing;
            } else {
                this.typing = "";
            }
        },
        getMessagesResponse: function(data){
            if (data.conversation_id == this.receiverDetails.conversation_id) {
                socket.emit('getUnreadMsgCount', {user_id: this.user_id});
                var listElm;
                if(this.messages && this.messages.messages && this.messages.messages.length > 0){
					if(data.result && data.result.messages){
						let newMessagesList =   this.onlyUnique(data.result.messages);
						this.messagesMap	=	newMessagesList.concat(this.messagesMap);
					}else{
                        this.messagesMap = [];
                    }
					this.messages.messages = data.result.messages.concat(this.messages.messages);
                    this.messages.pagination    =   data.result.pagination;
                    if(this.messages.lastScrollPosition){
                        $(".chat_container").css('overflow','auto');
                        listElm = document.querySelector('.chat_container');
                        $(".chat_container").stop().animate({ scrollTop: (listElm.scrollHeight - this.messages.lastScrollPosition)}, 1);
                    }
                }else{
                    if(data.result && data.result.messages){
                        this.messages = data.result;
						this.messagesMap = this.onlyUnique(data.result.messages);
					}else{
                        this.messages.messages = [];
                        this.messagesMap = [];
                    }
                }
                this.loading = false;
                this.$nextTick(function () {
					let lastHeight = this.messages.lastScrollPosition;
					var newHeight
					listElm = document.querySelector('.chat_container');
					if(listElm){
						newHeight = listElm.scrollHeight;
						this.messages.lastScrollPosition = (newHeight);
					}
                    if(this.messages.pagination && this.messages.pagination.currentPage == 0){
                        this.scrollToBottom();
                    }else{
						if(newHeight){
							$(".chat_container").stop().animate({ scrollTop: (newHeight - lastHeight)}, 1);
						}
					}
					
                    // Detect when scrolled to bottom.
                    /* if(this.messages.pagination && this.messages.pagination.nextPage >0 && this.loading == false){
                        //console.log(this.messages.pagination.nextPage);
                        var nextPage = this.messages.pagination.nextPage;
                        listElm = document.querySelector('.chat_container');
                        if(listElm){
                            //console.log(listElm.scrollHeight);
                            listElm.addEventListener('scroll', () => {
                                if(listElm.scrollTop == 0 && this.loading == false) {
                                    this.loading = true;
                                    $(".chat_container").stop().animate({ scrollTop: 20}, 1);
                                    $(".chat_container").css('overflow','hidden');

                                    if(this.messages.lastScrollPosition){
                                        this.messages.lastScrollPosition = (listElm.scrollHeight-this.messages.lastScrollPosition);
                                    }else{
                                        this.messages.lastScrollPosition = (listElm.scrollHeight);
                                    }
                                    console.log("lastScrollPosition:"+this.messages.lastScrollPosition);
                                    console.log("clientHeight:"+listElm.clientHeight);
                                    console.log("scrollHeight:"+listElm.scrollHeight);
                                    console.log(nextPage);
                                    setTimeout(() => socket.emit('getMessages', {conversation_id: this.receiverDetails.conversation_id, sender_id:this.user_id,page:nextPage}), 3000);
                                }
                            });
                        }
                    } */
                });
            }
        },
        deleteGroup: function(conversationId) {
            Swal.fire({
                title: 'You want to delete this group ?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Yes! Delete It',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        success: (data) => {
                            if(data.success == true){
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                })
                                this.receiverDetails    =   "";
                                this.showMsgBox         =   false;
                                this.messages           =   [];
                                socket.emit('chatList',this.user_id,this.conversation_id);
                            }else{
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                })
                            }
                        },
                        error: (error) => {
                            console.log(error);
                            Toast.fire({
                                icon: 'error',
                                html: error
                            })
                        },
                        complete: () => {
                            //console.log("complete me aaya");
                        },
                        url: "/chat/"+conversationId+"/delete-group",
                    });
                }
            })
        },
        suggestUsers: function(groupData = null) {
            if(groupData){
                $("#updateGroupMemberForm")[0].reset();
                $(".select2").select2();
                $("#groupId").val(groupData.conversation_id);
            }
            $.ajax({
                type: "POST",
                data: {'groupData': groupData},
                success: (data) => {
                    if(data.success == true){
                        this.suggestUsersList    =   data.data;
                    }else{
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        })
                    }
                },
                error: (error) => {
                    console.log(error);
                    Toast.fire({
                        icon: 'error',
                        html: error
                    })
                },
                complete: () => {
                    //console.log("complete me aaya");
                },
                url: "/chat/group/suggest-users",
            });
        },
        updateGroupMember: function() {
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: new FormData($('#updateGroupMemberForm')[0]),
                processData: false,
                contentType: false,
                success: (data) => {
                    if(data.success == true){
                        this.suggestUsersList    =   data.data;
                        $("#updateGroupMemberForm")[0].reset();
                        $("#group-member-modal").modal('hide');
                        if(data.socketIds){
                            $.each(data.socketIds, function(userId,socketId) {
                                if(socketId){
                                    socket.emit('chatList', userId,null,socketId);
                                }
                            });
                        }
                        this.chat(this.receiverDetails);
                    }else if(data.errors){
                        $.each(data.errors, function(field_name,error) {
                            if(field_name == 'members'){
                                $(".members_error").text(error).parent('.form-group').addClass('error');
                            }else{
                               $('[name='+field_name+']').parent('.form-group').addClass('error');
                               $('[name='+field_name+']').next('.error-message').text(error);
                            }
                        });
                    }else{
                        Toast.fire({
                            icon: 'error',
                            html: data.message
                        })
                    }
                },
                error: (error) => {
                    console.log(error);
                    Toast.fire({
                        icon: 'error',
                        html: error
                    })
                },
                complete: () => {
                    //console.log("complete me aaya");
                },
                url: "/chat/group/update-members",
            });
        },
        removeGroupMember: function(groupData,memberData) {
            Swal.fire({
                title: 'Remove '+memberData.full_name+' from this group ?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Remove',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: {'type':'remove','group_id':groupData.conversation_id,'members[]':memberData.user_id},
                        success: (data) => {
                            if(data.success == true){
                                this.suggestUsersList    =   data.data;
                                $("#updateGroupMemberForm")[0].reset();
                                $("#group-member-modal").modal('hide');
                                if(data.socketIds){
                                    $.each(data.socketIds, function(userId,socketId) {
                                        if(socketId){
                                            socket.emit('chatList', userId,null,socketId);
                                        }
                                    });
                                }
                                this.chat(this.receiverDetails);
                            }else if(data.errors){
                                $.each(data.errors, function(field_name,error) {
                                    if(field_name == 'members'){
                                        $(".members_error").text(error).parent('.form-group').addClass('error');
                                    }else{
                                    $('[name='+field_name+']').parent('.form-group').addClass('error');
                                    $('[name='+field_name+']').next('.error-message').text(error);
                                    }
                                });
                            }else{
                                Toast.fire({
                                    icon: 'error',
                                    html: data.message
                                })
                            }
                        },
                        error: (error) => {
                            console.log(error);
                            Toast.fire({
                                icon: 'error',
                                html: error
                            })
                        },
                        complete: () => {
                            //console.log("complete me aaya");
                        },
                        url: "/chat/group/update-members",
                    });
                }
            })
        },
		onlyUnique: function(messagesArray) { 
			let uniq_dates  = Array.from(new Set( messagesArray.map(obj => obj.date)));
			let temp_itens = []
			let date_filtered;
			uniq_dates.filter(data=>{
                let key = data;
				date_filtered = messagesArray.filter( (item,index)=>{
                    //console.log(item.message);
                    //item.message = await this.urlify(item.message);
                    
                    //temp_itens = [];
					if(key==item.date){
                        return item;
					}  
				})
				temp_itens.push(date_filtered);
				//this.messagesMap.push(date_filtered);
			})
			return temp_itens;
		},
        urlify: function(text) {
            //var urlRegex = /(https?:\/\/[^\s]+)/g;
            //var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            var urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
            return text.replace(urlRegex, function(url,b,c) {
                var url2 = (c == 'www.') ?  'http://' +url : url;
                return '<a title="' + url + '" target="_blank" href="' + url2 + '">' + url + '</a>';
            })
            // or alternatively
            // return text.replace(urlRegex, '<a href="$1">$1</a>')
        },
        imageuploaded: function(data){
            if (data && data.receiver_id == this.receiverDetails.id) {
                $(".overlay").parent().parent().remove();
                this.messages.messages.push(data);
                if(this.messagesMap && (this.messagesMap.length>0)){
                    let lastPosition = this.messagesMap.length-1;
                    this.messagesMap[lastPosition].push(data);
                }
                this.$nextTick(function () {
                    this.scrollToBottom();
                });
            }
        },
        removeSelectedFile: function(file,index){
            if(file){
                this.loadFiles.splice(index, 1);
            }
        },
        file: function(event){
            if(event.target.files){
                $.each(event.target.files, function(fileIndex, fileObject){
                    var file = fileObject; //event.target.files[0];
                    if (app.validateSize(file)) {
                        let file_format = file.type.split('/')[0];
                        if(file.type.split('/')[1]){
                            if(file_format != "image" && file_format != "video"){
                                file_format =   file.type.split('/')[1];
                            }
                        }
                        let reader  = new FileReader();
                        reader.onload = function () {
                            let src = URL.createObjectURL(new Blob([reader.result]));
                            let messagePacket = app.createMsgObj('file', file_format, reader.result);
                            console.log(messagePacket);
                            let filesArray = {'msg_obj':messagePacket,'file_name':file.name,'file_src':src};
                            app.loadFiles.push(filesArray);
                            //this.loadFile = `<div class="file_'+file.name+'"><img height="100px;" width="100px;" src="`+src+`"><div class="overlay" @click="removeSelectedFile('`+file.name+`')"><i class="mdi mdi-close"></i></div></div>`;
                            /* 
                            messagePacket['fileName'] = file.name;
                            socket.emit('upload-image', messagePacket);
                            socket.emit('getUnreadMsgCount', {user_id: this.user_id});
                            messagePacket.type = "text";
                            if(file_format != 'image'){
                                messagePacket.message = '<span class="info-box-icon bg-primary" style="color: white;background:none;"><i class="fa fa-paperclip"></i></span><div class="overlay"><i style="color:#fff" class="fa fa-refresh fa-spin"></i></div>';
                            }else {
                                let src = URL.createObjectURL(new Blob([reader.result]));
                                messagePacket.message = '<img height="100px;" width="100px;" src="'+src+'"><div class="overlay"><i style="color:#fff" class="fa fa-refresh fa-spin"></i></div>';
                            }
                            //this.messages.push(messagePacket);
                            this.scrollToBottom(); */
                        }.bind(this);
                        reader.readAsArrayBuffer(file);
                    }else {
                        event.target.value = "";
                        //this.loadFiles.splice(index, 1);
                        alert('File "'+file.name+'" size exceeds 10 MB');
                    }
                });
            }
            
        },
        sendFiles: function() {
            if(this.loadFiles.length > 0){
                $.each(this.loadFiles, function(fileIndex,fileData){
                    if(fileData.msg_obj){
                        let messagePacket = fileData.msg_obj;
                        messagePacket['fileName'] = fileData.file_name;
                        socket.emit('upload-image', messagePacket);
                        socket.emit('getUnreadMsgCount', {user_id: this.user_id});
                    }
                })
            }
            this.loadFiles = [];
        },
        validateSize: function(file) {
            var fileSize = file.size / 1024 / 1024; // in MB
            if (fileSize > 10) {
                return false;
            }
            return true;
        }
    },
	/* created(){
		this.onlyUnique();
	}, */
	
	filters: {
		moment: function (date) {
		  return moment(date).format('DD-MMM-YYYY');
		}
	},
    mounted(){
        socket.emit('getUnreadMsgCount', {user_id: this.user_id});
        socket.emit('getMessages', {conversation_id: this.receiverDetails.conversation_id, sender_id:this.user_id, receiver_id:this.receiverDetails.id});
        socket.on('getUnreadMsgCountResponse', this.getUnreadMsgCountResponse);
        socket.on('getGroupMembersResponse', this.getGroupMembersResponse);
        socket.on('getMessagesResponse', this.getMessagesResponse);
        socket.on('addMessageResponse', this.addMessageResponse);
        socket.on('typing', this.typingListener);
        socket.on('image-uploaded', this.imageuploaded);
        $(".select2").select2();
    },
    destroyed: function() {
        socket.removeListener('getUnreadMsgCountResponse', this.getUnreadMsgCountResponse);
        socket.removeListener('getMessagesResponse', this.getMessagesResponse);
        socket.removeListener('addMessageResponse', this.addMessageResponse);
        socket.removeListener('typing', this.typingListener);
    },
    computed: {
      filteredUserList() {
        return this.chatLists.filter((userItem) => {
            if(!this.receiverDetails && this.conversation_id){
                if(userItem.conversation_id == this.conversation_id){
                    this.chat(userItem);
                }
            }
            userItem.shortName = userItem.full_name.split(' ').map(x => x[0].toUpperCase()).join('').substring(0,2);
            return userItem.full_name.toLowerCase().includes(this.keyword.toLowerCase());
        });
      },
      messages_length: function () {
        return this.messages.length;
      }
    }
});

socket.on('connect', function(data){
    app.socketConnected.status = true;
    socket.emit('getUnreadMsgCount', {user_id: app.user_id});
    socket.emit('chatList',app.user_id,app.conversation_id);
});
socket.on('connect_error', function(){
    app.socketConnected.status = false;
    app.socketConnected.msg = 'Could not connect to server';
    app.chatLists = [];
});
socket.on('chatListRes', function(data){
    if (data.userDisconnected) {
        app.chatLists.findIndex(function(el) {
            if(el.socket_id == data.socket_id){
                el.online = 'N';
                el.socket_id = '';
            }
        });
    }else if (data.userConnected) {
        app.chatLists.findIndex(function(el) {
            if(el.id == data.userId){
                el.online = 'Y';
                el.socket_id = data.socket_id;
            }
        });
    }else {
        /* data.chatList.findIndex(function(el) {
            el.msgCount = 0;
        }); */
        app.chatLists = data.chatList;
        app.authUser = data.authUser;

        if(app.receiverDetails && app.receiverDetails.type == "group"){
            //socket.emit('getGroupMembers', {conversation_id: app.receiverDetails.conversation_id});
            var socketId = (data.socketId) ?? null;
            app.chat(app.receiverDetails,socketId);
        }
    }
});
// user chat box not open, count incomming  messages
socket.on('addMessageResponse', function(data){
    //if(!app.chatBox.includes(data.sender_id)){
        app.chatLists.findIndex(function(el) {
            if(el.id == data.sender_id){
                el.msgCount += 1;
            }
        });
    //}
});
