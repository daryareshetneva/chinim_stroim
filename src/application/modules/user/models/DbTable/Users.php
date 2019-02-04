<?php

class db_Users {

    // table
    const TABLE = 'Users';
    // fields
    const _ID = 'id';
    const _TYPE = 'type';
    const _PASS = 'password';
    const _EMAIL = 'email';
    const _FIRST_NAME = 'firstName';
    const _LAST_NAME = 'lastName';
    const _PATRONYMIC = 'patronymic';
    const _PHONE = 'phone';
    const _REG_DATE = 'registrationDate';
    const _LAST_DATE = 'lastvisitDate';
    const _DISCOUNT = 'discount';

    // enums
    const TYPE_USER = 'user';
    const TYPE_MANAG = 'manager';
    const TYPE_ADM = 'administrator';

}

class User_Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = db_Users::TABLE;
    protected $_primary = db_Users::_ID;

    /**
     * Count all users
     * @return int
     */
    public function countUsers() {
        $select = $this->select()
                ->from($this->_name, array('COUNT(*)'));
        return $this->getAdapter()->fetchOne($select);
    }

    /**
     * Get users list for admin 
     * @return array
     */
    public function getUsers() {
        $select = $this->select()
                ->from($this->_name, [db_Users::_ID, db_Users::_TYPE, db_Users::_EMAIL, db_Users::_FIRST_NAME,
                    db_Users::_LAST_NAME, db_Users::_PATRONYMIC, db_Users::_PHONE, db_Users::_REG_DATE,
                    db_Users::_LAST_DATE, db_Users::_DISCOUNT])
                ->Where(db_Users::_TYPE . ' = ?', db_Users::TYPE_USER)
                ->order(db_Users::_EMAIL);
        return $this->getAdapter()->fetchAll($select);
    }

    public function getAdminEmail() {
        $select = $this->select()
            ->from($this->_name, array('email'))
            ->where('id = ?', 1);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getUserPairs() {
        $fio = $this->_getFioField();
        $select = $this->select()
                ->from($this->_name, array(db_Users::_ID, $fio))
                ->order($fio);
        return $this->getAdapter()->fetchPairs($select);
    }

    public function getIdByEmail($email) {
        $select = $this->select()
                ->from($this->_name, array(db_Users::_ID))
                ->where(db_Users::_EMAIL . ' = ?', $email);
        return $this->getAdapter()->fetchOne($select);
    }

    public function getByEmail($email) {
        $select = $this->select()
            ->from($this->_name, [db_Users::_ID])
            ->where(db_Users::_EMAIL . ' = ?', $email);
        $userId = $this->getAdapter()->fetchOne($select);
        return $this->find($userId)->current();
    }


    public function getUserNameList() {
        $fio = $this->_getFioField();
        $select = $this->select()
                ->from($this->_name, array($fio))
                ->where('`' . db_Users::_TYPE . '` = ?', 'user');
        return $this->getAdapter()->fetchCol($select);
    }

    public function getManagerUsers() {
        $select = $this->select()
                ->from($this->_name, [db_Users::_ID, db_Users::_TYPE, db_Users::_EMAIL, db_Users::_FIRST_NAME,
                    db_Users::_LAST_NAME, db_Users::_PATRONYMIC, db_Users::_PHONE, db_Users::_REG_DATE,
                    db_Users::_LAST_DATE, db_Users::_DISCOUNT])
                ->orWhere(db_Users::_TYPE . ' = ?', db_Users::TYPE_ADM)
                ->orWhere(db_Users::_TYPE . ' = ?', db_Users::TYPE_MANAG);

        return $this->getAdapter()->fetchAll($select);
    }

    public function getManagersEmails() {
        $select = $this->select()
            ->from( $this->_name, array('email') )
            ->orWhere( '`type` = \'manager\'' )
            ->orWhere( '`type` = \'administrator\'' );

        return $this->getAdapter()->fetchAll( $select );
    }

    public function search($query) {
        $queryWords = preg_split("#([ !.?:;,])#", $query);

        $select = $this->select()
                ->from($this->_name, [db_Users::_ID, db_Users::_TYPE, db_Users::_EMAIL, db_Users::_FIRST_NAME,
                    db_Users::_LAST_NAME, db_Users::_PATRONYMIC, db_Users::_PHONE, db_Users::_REG_DATE,
                    db_Users::_LAST_DATE, db_Users::_DISCOUNT]);

        $where = "";
        $seperator = " OR ";
        foreach ($queryWords as $key => $word) {
            if ($key >= 1) {
                $where .= $seperator;
            }
            $where .= $this->getAdapter()->quoteInto('`' . db_Users::_FIRST_NAME . '` LIKE ?', "%{$word}%");
            $where .= $seperator;
            $where .= $this->getAdapter()->quoteInto('`' . db_Users::_LAST_NAME . '` LIKE ?', "%{$word}%");
            $where .= $seperator;
            $where .= $this->getAdapter()->quoteInto('`' . db_Users::_PATRONYMIC . '` LIKE ?', "%{$word}%");
            $where .= $seperator;
            $where .= $this->getAdapter()->quoteInto('`' . db_Users::_PHONE . '` LIKE ?', "%{$word}%");
            $where .= $seperator;
            $where .= $this->getAdapter()->quoteInto('`' . db_Users::_EMAIL . '` LIKE ?', "%{$word}%");
        }
        $select->where($where);

        return $this->getAdapter()->fetchAll($select);
    }

    public function getQuoteInto($text, $value) {
        return $this->_db->quoteInto($text, $value);
    }


    public function getAll() {
        $select = $this->select()
                ->from($this->_name, [db_Users::_ID, db_Users::_TYPE, db_Users::_EMAIL, db_Users::_FIRST_NAME,
                    db_Users::_LAST_NAME, db_Users::_PATRONYMIC, db_Users::_PHONE, db_Users::_REG_DATE,
                    db_Users::_LAST_DATE, db_Users::_DISCOUNT])
                ->order(db_Users::_EMAIL);
        return $this->getAdapter()->fetchAll($select);
    }

    public function socialAuth($provider, $uid) {
        $select = $this->select()
            ->from($this->_name, ['mail', 'password', 'id'])
            ->where('provider = ?', $provider)
            ->where('uid = ?', $uid);
        return  $this->getAdapter()->fetchRow($select);
    }

    public function addUserSocial($fio, $mail, $provider, $uid) {
        $data = [
            'provider' => $provider,
            'mail' => $mail,
            'fio' => $fio,
            'uid' => $uid,
            'grade' => 0,
        ];
        return $this->insert($data);
    }

    public function updateUserMailById($mail, $userId) {
        $data = [
            'mail' => $mail
        ];
        $where = [
            'id = ?' => $userId
        ];
        $this->update($data, $where);
    }

    public function updateUserLastVisitDate($id, $date) {
        $data = array(
            'lastvisitDate' => $date
        );
        $where = [
            'id = ?' => $id
        ];
        $this->update( $data, $where);
    }

    public function getUserByEmailForOrder($email) {
        $select = $this->select()
            ->from($this->_name, ['id', 'email', 'firstName', 'lastName', 'patronymic','phone', 'password'])
            ->where('email = ?', $email);
        return $this->getAdapter()->fetchRow($select);
    }

    public function addUserOrder($userData, $date = null) {
        $data = [
            'type' => 'user',
            'password' => $userData['password'],
            'email' => $userData['email'],
            'firstName' => $userData['firstName'],
            'lastName' => $userData['lastName'],
            'patronymic' => $userData['patronymic'],
            'phone' => $userData['phone'],
            'discount' => 0,
            'registrationDate' => $date
        ];
        return $this->insert($data);
    }

    public function updateUser($userId, $data)
    {
        $where = [
            'id = ?' => $userId,
        ];
        $this->update($data,$where);
    }

    private function _getFioField() {
        return  new Zend_Db_Expr('CONCAT_WS(" ",' . db_Users::_FIRST_NAME . ',' .
            db_Users::_LAST_NAME. ',' . db_Users::_PATRONYMIC . ')');
    }
}
