<?php
// load all the files need in order to get all scripts running smoothly

// loading composer autoloader
require './vendor/autoload.php';

// loading redis connection instance class
require './modules/redis.php';

// loading base model class
require './modules/model.php';

// loading messages module
require './modules/messages.php';

// loading users module
require './modules/users.php';

// loading threads module
require './modules/threads.php';

/* End of file loader.php */
/* Location ./loader.php */