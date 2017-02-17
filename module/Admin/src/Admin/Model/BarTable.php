<?php
namespace Admin\Model;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
// use Zend\Db\Sql\Select;
// use Zend\Db\Sql\Expression;
class BarTable extends AbstractTableGateway implements AdapterAwareInterface
{

    protected $table = 'tbl_bars';

    public $barid;

    protected $oUserSessionTable;

    public function __construct($adapter = '')
    {
        if (! $this->adapter && $adapter instanceof Adapter) {
            $this->setDbAdapter($adapter);
        }
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function getBars($id = 0)
    {
        $this->oUserSessionTable = new UserSessionTable($this->adapter);

        $aWhere = $id ? array(
            'barid' => $id
        ) : array();
        $aBars = $this->select()->toArray();

        foreach ($aBars as $key => $aBar) {
            $aBars[$key]['femalepercent'] = number_format((float) (100 - $aBar['malepercent']), 2, '.', '');
            $aBars[$key]['users'] = $this->oUserSessionTable->getNearestUsers($aBar['lat'], $aBar['lng'], 5);
        }

        return $aBars;
    }

    // public function getUserVisitedBarLastNight()
    // {
    // $this->oUserSessionTable = new UserSessionTable($this->adapter);

    // $aBars = $this->select(function (Select $select) {
    // $select->columns(array( 'barid', 'barname', 'lat', 'lng' ))
    // ->where(array( 'status' => 1 ));
    // });

    // foreach ($aBars as $key => $aBar) {
    // $aUsers = $this->oUserSessionTable->getNearestUsers($aBar['lat'], $aBar['lng'], 5);
    // usort($aUsers, function($a, $b) {
    // return $a['distance'] - $b['distance'];
    // });
    // $aBars[$key]['users'] = $aUsers;
    // }
    // }

    /**
     * Set Validation rule for adding a Bar
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getAddInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        // name
        $inputFilter->add($factory->createInput(array(
            'name' => 'barname',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 200
                    )
                )
            )
        )));

        // desc
        $inputFilter->add($factory->createInput(array(
            'name' => 'description',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        )));

        // lat
        $inputFilter->add($factory->createInput(array(
            'name' => 'lat',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // lng
        $inputFilter->add($factory->createInput(array(
            'name' => 'lng',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // status
        $inputFilter->add($factory->createInput(array(
            'name' => 'status',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // malepercent
        $inputFilter->add($factory->createInput(array(
            'name' => 'malepercent',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        return $inputFilter;
    }

    public function getEditInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        // status
        $inputFilter->add($factory->createInput(array(
            'name' => 'barid',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // name
        $inputFilter->add($factory->createInput(array(
            'name' => 'barname',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 200
                    )
                )
            )
        )));

        // desc
        $inputFilter->add($factory->createInput(array(
            'name' => 'description',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        )));

        // lat
        $inputFilter->add($factory->createInput(array(
            'name' => 'lat',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // lng
        $inputFilter->add($factory->createInput(array(
            'name' => 'lng',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // status
        $inputFilter->add($factory->createInput(array(
            'name' => 'status',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        // malepercent
        $inputFilter->add($factory->createInput(array(
            'name' => 'malepercent',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));

        return $inputFilter;
    }
}
