<?php
namespace Admin\Model;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class UserClickBarTable extends AbstractTableGateway implements AdapterAwareInterface
{

    protected $table = 'tbl_user_click_bar';

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }
    
    public function avgClickOnBarPerUser(){
        $aSession = $this->select(function (Select $select) {
            $select->columns(array(
                'avgclick' => new Expression('count(*)/count(distinct(barid))/count(distinct(userid)) ')
            ));
        })
        ->current();
        return $aSession['avgclick'];
    }
    
}