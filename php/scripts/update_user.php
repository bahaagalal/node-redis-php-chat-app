<?php
require '../loader.php';
/**
 * update user script
 */
$userId = input_post('user_id');

$name = input_post('name');

$avatar = input_post('avatar');

if(($userId && $name) || ($userId && $avatar))
{
        // check user existance
        $users = new Users();
        
        $isExists = $users->isUserExists($userId);
        
        if($isExists)
        {
                if($name)
                {
                        $user = $users->updateUserName($userId, $name);
                }
                
                if($avatar)
                {
                        $user = $users->updateUserAvatar($userId, $avatar);
                }
                    
                if($user)
                {
                        echo output_json(TRUE, ERR_EMPTY, $user);
                }
                else
                {
                        echo output_json(FALSE, ERR_SERVER_ERROR);
                }
        }
        else
        {
                echo output_json(FALSE, ERR_NOT_FOUND);
        }
}
else
{
        echo output_json(FALSE, ERR_MISSING_DATA);
}

/* End of file update_user.php */
/* Location ./scripts/update_user.php */