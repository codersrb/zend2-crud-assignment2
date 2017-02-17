<?php
namespace Admin\Forms;

use Zend\Form\Form;
use Zend\Form\Element;

class EditBarForm extends Form
{
    public function __construct(){
    
        parent::__construct('edit-bar');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'well form-horizontal');
        
        $this->add(new Element\Hidden('barid'));
        
        $this->add(array(
            'name' => 'barname',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Name',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true
            )
        ));
        
        //description
        $this->add(array(
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Bar description',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array( 'class' =>'form-control','required' => true, 'id'=> 'description')
        ));
        
        
        $this->add(array(
            'name' => 'lat',
            'type'  => 'Number',
            'options' => array(
                'label' => 'Lattitude',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true,
                'step'=>'0.00000001'
            )
        ));
        
        $this->add(array(
            'name' => 'lng',
            'type'  => 'Number',
            'options' => array(
                'label' => 'Longitude',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true,
                'step'=>'0.00000001'
            )
        ));
        
        $this->add(array(
            'name' => 'open',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Open hour',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true
            )
        ));
        
        $this->add(array(
            'name' => 'close',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Close hour',
                
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true
            )
        ));
        
        
        
        $this->add(array(
            'name' => 'defaultphoto',
            'type'  => 'Zend\Form\Element\File',
            'options' => array(
                'label' => 'Default photo',
                
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true
            )
        ));
        
        // STATUS
        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => '<strong>Status</strong>',
                'label_attributes' => array(
                    'class' => 'radio-inline'
                ),
                'label_options' => array(
                    'disable_html_escape' => true
                ),
                'value_options' => array(
                    1 => 'Active',
                    0 => 'Inactive'
                )
            ),
            'attributes' => array(
                'required' =>true
            )
        ));
        
        // MALEPERCENT
        $this->add(array(
            'name' => 'malepercent',
            'type'  => 'Number',
            'options' => array(
                'label' => 'Male Percent',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes'=> array(
                'class' => 'form-control',
                'required' => true,
                'step'=>"0.01",
                'max'=>"100",
                'min'=>"0",
                'onchange' => 'setFemalePercent(this.value)',
                'onkeyup' => 'setFemalePercent(this.value)'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Add Bar',
                'class' => 'btn btn-success'
            )
        ));
        
        //$this->add(new Element\Csrf('csrf'));
        
    }
    
}