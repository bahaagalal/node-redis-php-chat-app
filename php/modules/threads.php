<?php
/**
 * Threads Class to maniupluate threads, creating, adding, updating ... etc
 */
Class Threads Extends BaseModel {
        
        /**
         * return thread messages key name
         * @param int $threadId thread id
         * @return string thread messages key name
         */
        private function threadMessages($threadId)
        {
                return 'thread:' . $threadId . ':messages';
        }
        
        /**
         * return thread user key name
         * @param int $threadId thread id
         * @return string thread users key name
         */
        private function threadUsers($threadId)
        {
                return 'thread:' . $threadId . ':users';
        }
        
        /**
         * gets a new id for a fresh thread
         * @return int new thread id
         */
        private function incrementThreadsCounter()
        {
                return $this->redisClient->incr('threads:counter');
        }
        
        /**
         * 
         */
        function __construct() 
        {
                parent::__construct();
        }
        
        /**
         * create a new thread
         * @return int thread id
         */
        public function createThread()
        {
                $threadId = $this->incrementThreadsCounter();
                
                return $threadId;
        }
        
        /**
         * add message to thread
         * @param int $threadId
         * @param int $messageId
         * @return array message threads
         */
        public function addMessageToThread($threadId, $messageId)
        {
                $this->redisClient->rpush($this->threadMessages($threadId), $messageId);
                
                return $this->getThreadMessages($threadId);
        }
        
        /**
         * return last message id in the thread
         * @param int $threadId thread id
         * @return int message id
         */
        public function getLastMessageOfThread($threadId)
        {
                $lastMessageId = $this->redisClient->lrange($this->threadMessages($threadId), -1, -1);
                
                return reset($lastMessageId);
        }
        
        /**
         * return thread messages ids
         * @param int $threadId thread id
         * @return array messages id
         */
        public function getThreadMessages($threadId)
        {
                $messages = $this->redisClient->lrange($this->threadMessages($threadId), 0, -1);
                
                return $messages;
        }
        
        /**
         * add user to thread
         * @param int $threadId
         * @param string $userId
         * @return array thread users ids
         */
        public function addUserToThread($threadId, $userId)
        {
                $this->redisClient->sadd($this->threadUsers($threadId), $userId);
                
                return $this->getThreadUsers($threadId);
        }
        
        /**
         * return thread users ids
         * @param int $threadId thread id
         * @return array users id
         */
        public function getThreadUsers($threadId)
        {
                $users = $this->redisClient->smembers($this->threadUsers($threadId));
                
                return $users;
        }
        
}

/* End of file threads.php */
/* Location ./modules/threads.php */