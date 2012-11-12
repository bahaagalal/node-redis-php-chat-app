<?php
require '../loader.php';
/**
 * get thread messages
 */
$thread_id = input_get('thread_id');

if($thread_id)
{
        $users = new Users();
        
        $threads = new Threads();
        
        $messages = new Messages();
        
        $thread_messages_ids = $threads->getThreadMessages($thread_id);
        
        $thread_users_ids = $threads->getThreadUsers($thread_id);
        
        $thread_users = array();
        
        for($i = 0, $count = count($thread_users_ids); $i < $count; $i++)
        {
                    $thread_users[] = $users->getUser($thread_users_ids[$i]);
        }
        
        $thread_messages = array();
        
        for($i = 0, $count = count($thread_messages_ids); $i < $count; $i++)
        {
                $message = $messages->getMessage($thread_messages_ids[$i]);

                $message['user'] = $users->getUser($message['sender_id']);

                unset($message['sender_id']);

                $thread_messages[] = $message;
        }
        
        echo output_json(TRUE, ERR_EMPTY, array('users' => $thread_users, 'messages' => $thread_messages));
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file get_thread_messages.php */
/* Location ./scripts/get_thread_messages.php */