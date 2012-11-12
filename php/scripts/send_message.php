<?php
require '../loader.php';
/**
 * send message
 */
$thread_id = input_post('thread_id');

$message_body = input_post('message');

$sender_id = input_post('sender_id');

if($thread_id && $message_body && $sender_id)
{       
        $users = new Users();
        
        $threads = new Threads();
        
        $messages = new Messages();
        
        $push = new PushService();
        
        $message_id = $messages->createMessage($thread_id, $sender_id, $message_body);
        
        $threads->addMessageToThread($thread_id, $message_id); 
        
        $message = $messages->getMessage($message_id);

        $message['user'] = $users->getUser($message['sender_id']);

        unset($message['sender_id']);
        
        $push->pushMessage($message_id);

        echo output_json(TRUE, ERR_EMPTY, $message);
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file send_message.php */
/* Location ./scripts/send_message.php */