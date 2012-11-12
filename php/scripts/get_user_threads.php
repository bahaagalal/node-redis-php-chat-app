<?php
require '../loader.php';
/**
 * get user threads
 */
$userId = input_get('user_id');

if($userId)
{
        $users = new Users();
        
        $threads = new Threads();
        
        $messages = new Messages();
        
        $user_threads = array();
        
        $user_threads_ids = $users->getUserThreads($userId);
        
        if($user_threads_ids)
        {
                for($i = 0, $count = count($user_threads_ids); $i < $count; $i++)
                {
                        $user_threads[] = array('id' => $user_threads_ids[$i]);
                        
                        $thread_users = $threads->getThreadUsers($user_threads_ids[$i]);
                        
                        $user_threads[$i]['users'] = array();
                        
                        for($j = 0, $jCount = count($thread_users); $j < $jCount; $j++)
                        {
                                    $user_threads[$i]['users'][] = $users->getUser($thread_users[$j]);
                        }
                        
                        $last_message = $messages->getMessage($threads->getLastMessageOfThread($user_threads_ids[$i]));
                        
                        $last_message['user'] = $users->getUser($last_message['sender_id']);
                        
                        unset($last_message['sender_id']);
                        
                        $user_threads[$i]['last_message'] = $last_message;
                }
        }
        
        echo output_json(TRUE, ERR_EMPTY, $user_threads);
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file get_user_threads.php */
/* Location ./scripts/get_user_threads.php */