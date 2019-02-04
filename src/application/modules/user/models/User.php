<?php class User_Model_User{

    public function updateUser($userId, $data){
        $userTable = new User_Model_DbTable_Users();
        return $userTable->updateUser($userId,$data);
    }

    public function addUser($data)
    {
        $userTable = new User_Model_DbTable_Users();

        if (!$userTable->getIdByEmail($data['email'])) {
            return $userTable->insert($data);
        } else {
            return false;
        }

    }
}