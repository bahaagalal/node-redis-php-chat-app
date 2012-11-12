<?php 

/**
 * Base Redis Model
 */
Class BaseModel {
    
        /**
         *
         * @var Predis/Client redis client instance connected to our server 
         */
        protected $redisClient;
        
        function __construct() 
        {
                // getting reference to redis client instance
                $this->redisClient = RedisInstance::get_instance();
        }
}

/* End of file model.php */
/* Location ./modules/model.php */