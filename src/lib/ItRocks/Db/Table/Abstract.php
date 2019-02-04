<?php

class ItRocks_Db_Table_Abstract extends Zend_Db_Table_Abstract {

	CONST FETCH_ROW = 'row';
	CONST FETCH_COL = 'col';
	CONST FETCH_ALL = 'all';
	CONST FETCH_ONE = 'one';

	/**
	 * The field of table
	 *
	 * @var array
	 */
	protected $_fields = array();
    
    /**
     * Inserts multiple rows into the database. For 
     * databases implementing the compound insert statement 
     * according to SQL-92 (DB2, PostgreSQL > 8.2, MySQL), 
     * this happens in a single query. For other databases, 
     * all rows are inserted separately. 
     * 
     * The first parameter is an array containing the 
     * columns for which data should be inserted. The second 
     * parameter is an array of arrays containing the values 
     * for these columns. 
     * The values (= the data) are escaped for the query; 
     * you can specify the data type of a column using the 
     * Zend_Db constants by setting the column name as the 
     * array key and the data type as the array value. 
     * 
     * <code>
     * $columns = array('name', 
     *                  'postcount' => Zend_Db::INT_TYPE); 
     * $data = array(array('John Dorian',  4), 
     *               array('Percival Cox', 12)); 
     * 
     * $this->insertMultiple($columns,$data); 
     * </code>
     * 
     * @param array $columns The column names 
     * @param array $data The values 
     * @return void 
     */
    public function insertMultiple(array $columns, array $data) {
        /*
         * Check if the used database adapter is supported 
         * by the method. 
         */
        $db = $this->getAdapter();
        $dbConfig = $db->getConfig();
        $dbType = strtolower($dbConfig['adapter']);
        $supportedSql92 = array('db2', 'mysqli', 'pdo_mysql', 'pdo_pgsql');
        $insertMethod = null;
        if (in_array($dbType, $supportedSql92)) {
            if ($dbType === 'pdo_pgsql' && !version_compare($db->getServerVersion(),
                            '8.2', '>=')) {
                throw new Exception('PostgreSQL ' . $db->getServerVersion() . ' does not support the compound insert statement according to SQL-92.');
            }
            // Adapter supports the SQL-92 standard. 
            $insertMethod = 'sql92';
        } else {
            // Adapter does not support SQL-92. 
            $insertMethod = 'seperately';
        }

        // Array for column data types. 
        $types = array_fill(0, count($columns), null);

        // Scan the column array for type declarations. 
        $i = 0;
        foreach ($columns as $key => &$value) {
            if (is_string($key)) {
                // $key = column, $value = type. Remember the data type for later and set the column as value. 
                $types[$i] = $value;
                $value = $db->quoteIdentifier($key);
            } else {
                $value = $db->quoteIdentifier($value);
            }
            $i++;
        }
        unset($value);

        if ($insertMethod === 'sql92') {
            /**
             * Build the query according to SQL-92. 
             */
            $query = 'INSERT INTO ' . $db->quoteIdentifier($this->_name) . ' (' . implode(',',
                            $columns) . ') VALUES ';

            // Quote every value of every row with the correct type declaration. 
            $rowsSql = array();
            foreach ($data as $row) {
                $rowValues = array();
                $i = 0;
                foreach ($row as $value) {
                    $rowValues[] = $db->quote($value, $types[$i]);
                    $i++;
                }
                $rowsSql[] = '(' . implode(',', $rowValues) . ')';
            }

            $query .= implode(',', $rowsSql);
            $db->query($query);
        } else {
            /**
             * Or do all inserts seperately. 
             */
            $query = 'INSERT INTO ' . $db->quoteIdentifier($this->_name) . ' (' . implode(',',
                            $columns) . ') VALUES ';

            foreach ($data as $row) {
                $valuesSql = '';
                $i = 0;
                foreach ($row as $value) {
                    $rowValues[] = $db->quote($value, $types[$i]);
                    $i++;
                }
                $valuesSql = '(' . implode(',', $rowValues) . ')';
                $db->query($query . $valuesSql);
            }
        }
    }

    public function quoteInto($cond, $value) {
        return $this->getAdapter()->quoteInto($cond, $value);
    }

	public function get($value, $field, $fetch = self::FETCH_ROW) {
		if ( !$this->_fields || !in_array( $field, $this->_fields ) ) {
			return false;
		}
		$select = $this->select()
				->from( $this->_name, $this->_fields )
				->where( $field . " = ?", $value );

		return $this->_returnByFetch( $select, $fetch );
	}

    /**
     * The field of table for search
     *
     * @var array
     */
    protected $_fieldsForSearch = array();

    /**
     * The fields for search
     *
     * @param array $fields fields array( 'id', 'title', 'description' ); if use alias please add 'alias' in array
     */
    protected function setFieldsForSearch( $fields ) {
        $this->_fieldsForSearch = $fields;
    }

    /**
     * Item per page for paginator (search)
     *
     * @var int
     */
    protected $_perPage = 0;

    protected function setPerPage( $value ) {
        $this->_perPage = $value;
    }

	private function _returnByFetch($select, $fetch) {
		switch ( $fetch ) {
			case self::FETCH_ROW:
				return $this->getAdapter()->fetchRow( $select );

			case self::FETCH_ALL:
				return $this->getAdapter()->fetchAll( $select );

			case self::FETCH_COL:
				return $this->getAdapter()->fetchCol( $select );

			case self::FETCH_ONE:
				return $this->getAdapter()->fetchOne( $select );

			default:
				return false;
		}
	}

}
