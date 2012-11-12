<?php
require '../loader.php';
/**
 * create thread
 */
$thread_users = input_post('thread_users');

$message_body = input_post('message');

$sender_id = input_post('sender_id');

if($thread_users && $message_body && $sender_id)
{
        $users = new Users();
        
        $threads = new Threads();
        
        $messages = new Messages();
        
        $push = new PushService();
        
        $thread_id = $threads->createThread();
        
        $thread_users_ids = explode(';', $thread_users);
        
        for($i = 0, $count = count($thread_users_ids); $i < $count; $i++)
        {
                $threads->addUserToThread($thread_id, $thread_users_ids[$i]);
                
                $users->addThreadToUser($thread_users_ids[$i], $thread_id);
        }
        
        $message_id = $messages->createMessage($thread_id, $sender_id, $message_body);
        
        $threads->addMessageToThread($thread_id, $message_id); 
        
        $push->pushMessage($message_id);

        echo output_json(TRUE, ERR_EMPTY, $thread_id);
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file create_thread.php */
/* Location ./scripts/create_thread.php */