/** get $_GET[$field] **/
function getURLParameter(name) 
{
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
        );
}

/** format unix time stamp **/
function formatTime(time)
{
    var date = new Date(time * 1000);
    
    var hours = date.getHours();
    
    var minutes = date.getMinutes();
    
    var seconds = date.getSeconds();
    
    var formattedTime = '';
    
    if(hours >= 48)
    {
        formattedTime = parseInt(hours / 24) + ' days ago';
    }
    else if(hours >= 24)
    {
        formattedTime = '1 day ago';       
    }
    else if(hours > 1)
    {
        formattedTime = hours + ' hours ago';
    }
    else if(hours == 1)
    {
        formattedTime = '1 hour ago';   
    }
    else if(minutes > 1)
    {
        formattedTime = minutes + ' minutes ago';
    }
    else
    {
        formattedTime = 'few seconds ago';
    }
    
    return formattedTime;
}

$(document).ready(function(){
    
    var username = getURLParameter('username');
    
    if(username)
    {
        // threads template
        var threads_template_source   = $("#thread-template").html();
        var threads_template = Handlebars.compile(threads_template_source);
        
        // thread users template
        var users_template_source   = $("#thread-users-template").html();
        var users_template = Handlebars.compile(users_template_source);
        
        // messages template 
        var messages_template_source   = $("#message-template").html();
        var messages_template = Handlebars.compile(messages_template_source);
        
        // get user threads
        $('.ajax-loader-1').show();
        
        $.get('http://localhost/node-redis-php-chat/php/scripts/get_user_threads?user_id=' + username, function(response){
            
            $('.ajax-loader-1').hide();
            
            if(response.status == true)
            {
                var threads = [];
                
                for(var i = 0, count = response.data.length; i < count; i++)
                {
                    var no_of_users = '';
                    
                    if(response.data[i].users.length > 2)
                    {
                        no_of_users = ' and ' + response.data[i].users.length + ' more';
                    }
                    
                    var message = '';
                    
                    if(response.data[i].last_message.body.length > 20)
                    {
                        message = (response.data[i].last_message.body).substr(0, 20) + '...';
                    }
                    else
                    {
                        message = response.data[i].last_message.body;
                    }
                    
                    threads[i] = {
                        id: response.data[i].id,
                        avatar: response.data[i].last_message.user.avatar,
                        users: response.data[i].last_message.user.name + no_of_users,
                        time: formatTime(response.data[i].last_message.time),
                        message: message
                    };   
                }
                
                var threads_html    = threads_template({
                    threads: threads
                });
                
                $('#threads-list').html(threads_html);
                
            }
            else
            {
                console.log(response.errorMessage);
            }
            
        }, 'json');
        
        $('#threads-list a').live('click', function(){
            
            $('#threads-list li').removeClass('active');
            
            $(this).parent('li').addClass('active');
            
            var threadId = $(this).attr('thread_id');
            
            $('.ajax-loader-2').show();
            
            $.get('http://localhost/node-redis-php-chat/php/scripts/get_thread_messages?thread_id=' + threadId, function(response){
            
                $('.ajax-loader-2').hide();
            
                if(response.status == true)
                {
                    var messages = [];
                
                    for(var i = 0, count = response.data.messages.length; i < count; i++)
                    {
                        messages[i] = {
                            avatar: response.data.messages[i].user.avatar,
                            name: response.data.messages[i].user.name,
                            time: formatTime(response.data.messages[i].time),
                            message: response.data.messages[i].body
                        };   
                    }
                
                    var messages_html    = messages_template({
                        messages: messages
                    });
                    
                    var users_html = users_template({
                        name1: response.data.users[0].name,
                        name2: response.data.users[1].name,
                        users_count: response.data.users.length,
                        more_users: (response.data.users.length > 2)? true : false
                    });
                
                    $('#messages-list').html(messages_html);
                    
                    $('#thread-users').html(users_html);
                    
                    $('.send-message-form').show();
                }
                else
                {
                    console.log(response.errorMessage);
                }
            
            }, 'json');
        })
        
        $('.btn-send').click(function(){
            
            $('.btn-send').hide();
            
            var message = $('textarea').val();
            var thread_id = $('#threads-list li.active a').attr('thread_id');
            
            var data = {
                thread_id: thread_id,
                message: message,
                sender_id: username
            };
            
            $.post('http://localhost/node-redis-php-chat/php/scripts/send_message', data, function(response){
                if(response.status == true)
                {
                    var messages = [];
                    
                    messages[0] = {
                        avatar: response.data.user.avatar,
                        name: response.data.user.name,
                        time: formatTime(response.data.time),
                        message: response.data.body
                    };  

                    var messages_html    = messages_template({
                        messages: messages
                    });
                
                    $('#messages-list').append(messages_html);
                    
                    $('#messages-list').animate({
                        scrollTop: $('#messages-list')[0].scrollHeight
                    }, "slow");
                    
                    $('.btn-send').show();
                    
                    $('textarea').val('');
                }
                else
                {
                    console.log(response.errorMessage);
                }
            }, 'json');
        });
        
        
        // connect on socket
        var socket = io.connect('localhost', {
            port: 8000
        });
    
        // send presence online message
        socket.on('connect', function(){
            socket.emit('presence online', username);
        });
    
        // what happens when I receive a new message on my socket
        socket.on('message', function(message){
            
            console.log(message);
            
            var messages = [];
                    
            messages[0] = {
                avatar: message.senderAvatar,
                name: message.senderName,
                time: formatTime(message.time),
                message: message.body
            };  

            var messages_html  = messages_template({
                messages: messages
            });
                
            $('#messages-list').append(messages_html);
                    
            $('#messages-list').animate({
                scrollTop: $('#messages-list')[0].scrollHeight
            }, "slow");
        });
    }
});