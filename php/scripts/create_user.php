<?php
require '../loader.php';
/**
 * create user script
 */
$userId = input_post('user_id');

$name = input_post('name');

$avatar = input_post('avatar');

if($userId && $name && $avatar)
{
        // initiate users class
        $users = new Users();
        
        // check for user existanse 
        $isExists = $users->isUserExists($userId);
        
        if($isExists)
        {
                echo output_json(FALSE, ERR_DUPLICATE_DATA);
        }
        else
        {
                // create a new user object
                $user = $users->createUser($userId, $name, $avatar);
                
                if($user)
                {
                        echo output_json(TRUE, ERR_EMPTY, $user);
                }
                else
                {
                        echo output_json(FALSE, ERR_SERVER_ERROR);
                }
        }
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file create_user.php */
/* Location ./scripts/create_user.php */