<?php
namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Zend\Db\Sql;

/**
 * @todo User Model
 * @method User Table
 */
class UserTable extends AbstractTableGateway implements AdapterAwareInterface
{
	/** table name */
    protected $table = 'tbl_users';

    public function __construct($adapter = '')
	{
        if(!$this->isInitialized && $adapter instanceof  Adapter)
        {
            $this->setDbAdapter($adapter);
        }
    }

	/**
	 * @todo initialize and set adapter
	 */
    public function setDbAdapter(Adapter $adapter)
	{
        $this->adapter = $adapter;
        $this->initialize();
    }
}
