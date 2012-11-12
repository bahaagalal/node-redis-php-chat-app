<?php
/**
 * Threads Class to maniupluate users, creating, adding, updating ... etc
 */
Class Users Extends BaseModel {
        
        /**
         * user name field name
         */
        const USER_NAME = 'name';
        
        /**
         * user name avatar field
         */
        const USER_AVATAR = 'avatar';
        
        /**
         * 
         */
        function __construct() 
        {
                parent::__construct();
        }
        
        /**
         * return user key name
         * @param int $userId user id
         * @return string user key name
         */
        private function user($userId)
        {
                return 'user:' . $userId;
        }
        
        /**
         * return user threads key name
         * @param int $userId
         * @return string user threads key name 
         */
        private function userThreads($userId)
        {
                return 'user:' . $userId . ':threads';
        }
        
        /**
         * create a new user
         * @param string $userId user id
         * @param string $name user name
         * @param string $avatar user avatar
         * @return array user data
         */
        public function createUser($userId, $name, $avatar)
        {
                $this->redisClient->hset($this->user($userId) , Users::USER_NAME , $name);
                
                $this->redisClient->hset($this->user($userId) , Users::USER_AVATAR , $avatar);
                                
                return $this->getUser($userId);
        }
        
        /**
         * update given user name
         * @param string $userId user id
         * @param string $name user name
         * @return array user data
         */
        public function updateUserName($userId, $name)
        {
                $this->redisClient->hset($this->user($userId) , Users::USER_NAME , $name);
                
                return $this->getUser($userId);
        }
        
        /**
         * update given user avatar
         * @param string $userId user id
         * @param string $avatar avatar
         * @return array user data
         */
        public function updateUserAvatar($userId, $avatar)
        {
                $this->redisClient->hset($this->user($userId) , Users::USER_AVATAR , $avatar);
                
                return $this->getUser($userId);
        }
        
        /**
         * check if given user exists into the system
         * @param string $userId
         * @return boolean
         */
        public function isUserExists($userId)
        {
                $exists = $this->redisClient->exists($this->user($userId));
                
                return $exists;
        }
        
        /**
         * get user data by username
         * @param string $userId user id
         * @return array user data
         */
        public function getUser($userId)
        {
                $user = array(
                    
                    Users::USER_NAME => $this->redisClient->hget($this->user($userId), Users::USER_NAME),
                    
                    Users::USER_AVATAR => $this->redisClient->hget($this->user($userId), Users::USER_AVATAR)
                    
                );
                
                return $user;
        }
        
        /**
         * add thread to user
         * @param string $userId user id 
         * @param int $threadId thread id
         * @return array user threads ids
         */
        public function addThreadToUser($userId, $threadId)
        {
                $this->redisClient->rpush($this->userThreads($userId), $threadId);
                
                return $this->getUserThreads($userId);
        }
        
        /**
         * return user threads ids
         * @param string $userId user id
         * @return array user threads
         */
        public function getUserThreads($userId)
        {
                $threads = $this->redisClient->lrange($this->userThreads($userId), 0 , -1);
                
                return $threads;
        }
}

/* End of file users.php */
/* Location ./modules/users.php */