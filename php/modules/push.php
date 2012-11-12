<?php 
/**
 * Push Service to push messages on the chat channel
 */
Class PushService extends BaseModel{
        /**
         * 
         */
        public function __construct() 
        {
                parent::__construct();
        }
        
        // push message id on chat channel
        public function pushMessage($messageId)
        {
                $this->redisClient->publish('chat', $messageId);
        }
}

/* End of file push.php */
/* Location ./modules/push.php */