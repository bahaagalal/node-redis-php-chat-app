<?php
// load all the files need in order to get all scripts running smoothly

// loading composer autoloader
require __DIR__ . '/vendor/autoload.php';

// loading php utilities functions
require __DIR__ . '/utils.php';

// loading redis connection instance class
require __DIR__ . '/modules/redis.php';

// loading base model class
require __DIR__ . '/modules/model.php';

// loading messages module
require __DIR__ . '/modules/messages.php';

// loading users module
require __DIR__ . '/modules/users.php';

// loading threads module
require __DIR__ . '/modules/threads.php';

/* End of file loader.php */
/* Location ./loader.php */