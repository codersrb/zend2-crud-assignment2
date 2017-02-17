<?php
namespace Admin\Model;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Having;

class UserSessionTable extends AbstractTableGateway implements AdapterAwareInterface
{

    public $table = 'tbl_session';

    protected $lat, $lng, $distance, $iTotalUser;

    protected $oBarTable;

    public function __construct($adapter = '')
    {
        if (! $this->adapter) {
            if ($adapter instanceof Adapter)
                $this->setDbAdapter($adapter);
        }
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    /**
     *
     * @param float $lat            
     * @param float $lng            
     * @param int $distance
     *            distance in KM
     */
    public function getNearestUsers($lat, $lng, $distance = 5)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->distance = $distance;
        
        return $aUsers = $this->select(function (Select $select) {
            
            $select->columns(array(
                'userid',
                'distance' => new Expression('(
                                          6371 * ACOS(
                                            COS(RADIANS(' . $this->lat . ')) * COS(RADIANS(lat)) * COS(
                                              RADIANS(lng) - RADIANS(' . $this->lng . ')
                                            ) + SIN(RADIANS(' . $this->lat . ')) * SIN(RADIANS(lat))
                                          )
                                        )')
            ))
                ->join(array(
                'u' => 'tbl_users'
            ), 'u.userid = ' . $this->table . '.userid', array(
                'fbid',
                'email',
                'fullname',
                'fname',
                'lname'
            ));
            
            $having = new Having();
            $having->lessThanOrEqualTo('distance', 50000);
            $select->having($having)
                ->order('u.userid ASC, distance ASC ')
                ->limit(20);
        })
            ->toArray();
    }

    /**
     * avarage number of app open per user
     *
     * @param number $dayInterval            
     */
    public function getAvarageOpenPerUser()
    {
        $aSession = $this->select(function (Select $select) {
            $select->columns(array(
                'avgopen' => new Expression('COUNT( userid ) / COUNT( DISTINCT ( userid ) ) ')
            ));
        })
            ->current();
        return $aSession['avgopen'];
    }

    /**
     *
     * @return time in minutes
     */
    public function getAvarageSpentPerUser()
    {
        $aSession = $this->select(function (Select $select) {
            $select->columns(array(
                'avgminutes' => new Expression('AVG(TIMESTAMPDIFF(MINUTE,opentime,closetime))')
            ));
        })
            ->current();
        return $aSession['avgminutes'];
    }

    /**
     *
     * @return array List of user by distance
     */
    public function getUserVisitedBar()
    {
        
        return $aUserData = $this->select(function (Select $select) {
            $select->columns(array(
                'opentime',
                'distance' => new Expression('(6371 * ACOS(
	                       COS(RADIANS(B.lat)) * COS(RADIANS( ' . $this->table . '.lat)) * COS(
	                       RADIANS( ' . $this->table . '.lng) - RADIANS(B.lng)
        	               ) + SIN(RADIANS(B.lat)) * SIN(RADIANS( ' . $this->table . '.lat))
                          )
                        )')
            ))
                ->join(array(
                'U' => 'tbl_users'
            ), 'U.userid = ' . $this->table . '.userid', array(
                'fbid',
                'fullname'
            ))
                ->join(array(
                'B' => 'tbl_bars'
            ), 'B.barid = B.barid', array(
                'barid',
                'barname'
            ));
            
            $having = new Having();
            $having->lessThanOrEqualTo('distance', 1000);
            $select->having($having)
                ->order('opentime DESC, distance ASC ')
                ->limit(50);
        })
            ->toArray();
    }
    
    public function getAvaragePeakUsagePerHour(){
        
        $oUserTable = new UserTable($this->adapter);
        
        // get total users
        $this->iTotalUser = $oUserTable->getCountUser();
        
        return $this->select(function(Select $select){
            $select->columns(array(
                'hour' => new Expression('HOUR(closetime) '),
                'activeusers' => new Expression('COUNT(DISTINCT(userid))'),
                'avaragepercentusers' => new Expression('COUNT(DISTINCT(userid)) * 100 /'.$this->iTotalUser)
            ))
            ->group('hour');
        })->toArray();
    }
    
}
