<?php
namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;

class FriendTable extends AbstractTableGateway implements AdapterAwareInterface
{
    protected $table = 'tbl_friend';
    
    public function setDbAdapter(Adapter $adapter){
        $this->adapter = $adapter;
        $this->initialize();
    }
    
    
}