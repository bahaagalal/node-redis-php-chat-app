<?php // redis wrapper for creating, inserting and updateing messages and threads
/**
 * Redis Client Library based on singleton library
 * Make use of predis to initiate a connection to redis server
 */
Class RedisInstance {
    
        /**
         *
         * @var Predis/Client redis client instance
         */
        private static $redisInstance;
    
        
        /**
         * public constructor that initiates a connection to redis server
         */
        function __construct() 
        {
                self::$redisInstance = new Predis\Client('tcp://127.0.0.1:6379');
        }
        
        /**
         * make sure that only one connection instance is available through the whole application
         * @return Predis\Client instance
         */
        public static function &get_instance()
        {
                if (!self::$redisInstance)
                {
                        new RedisInstance();
                }

                return self::$redisInstance;
        }
}

/* End of file redis.php */
/* Location ./modules/redis.php */