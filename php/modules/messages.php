<?php
/**
 * Messages Class to maniupluate messages, creating, adding ... etc
 */
Class Messages Extends BaseModel {
        
        /**
         * message sender id field name
         */
        const MESSAGE_SENDER_ID = 'sender_id';
        
        /**
         * message body field name
         */
        const MESSAGE_BODY = 'body';
        
        /**
         * message thread id field name
         */
        const MESSAGE_THREAD_ID = 'thread_id';
        
        /**
         * message time field name
         */
        const MESSAGE_TIME = 'time';
        
        /**
         * 
         */
        function __construct() 
        {
            parent::__construct();
        }
        
        /**
         * return message key name
         * @param int $messageId
         * @return string message key name
         */
        private function message($messageId)
        {
                return 'message:' . $messageId;
        }
        
        /**
         * get a new id for a fresh message
         * @return int new message id
         */
        private function incrementMessagesCounter()
        {
                return $this->redisClient->incr('messages:counter');
        }
        
        /**
         * create a new message
         * @param int $threadId thread id
         * @param string $messageSenderId message user id
         * @param string $messageBody message body
         * @return array message data
         */
        public function createMessage($threadId, $messageUserId, $messageBody)
        {
                $messageId = $this->incrementMessagesCounter();
                
                $this->redisClient->hset($this->message($messageId) , Messages::MESSAGE_SENDER_ID , $messageUserId);
                
                $this->redisClient->hset($this->message($messageId) , Messages::MESSAGE_BODY , $messageBody);
                
                $this->redisClient->hset($this->message($messageId) , Messages::MESSAGE_THREAD_ID , $threadId);
                
                $this->redisClient->hset($this->message($messageId), Messages::MESSAGE_TIME , time());
                
                return $messageId;
        }
        
        /**
         * get message by its id
         * @param int $messageId message id
         * @return array message data
         */
        public function getMessage($messageId)
        {
                $message =  array(
                    
                    Messages::MESSAGE_SENDER_ID => $this->redisClient->hget($this->message($messageId), Messages::MESSAGE_SENDER_ID),
                    
                    Messages::MESSAGE_BODY => $this->redisClient->hget($this->message($messageId), Messages::MESSAGE_BODY),
                    
                    Messages::MESSAGE_THREAD_ID => $this->redisClient->hget($this->message($messageId), Messages::MESSAGE_THREAD_ID),
                    
                    Messages::MESSAGE_TIME => $this->redisClient->hget($this->message($messageId), Messages::MESSAGE_TIME)
                    
                );
                
                return $message;
        }
}

/* End of file messages.php */
/* Location ./modules/messages.php */