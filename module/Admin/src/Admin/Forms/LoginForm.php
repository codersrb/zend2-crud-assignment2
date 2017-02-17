<?php
namespace Admin\Forms;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;

class LoginForm extends Form
{

    function __construct()
    {
        parent::__construct('login-form');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'uname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Username'
            )
        ));

        $this->add(array(
            'name' => 'passwd',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password:',
                'label_options' => array(
                    'disable_html_escape' => true
                )
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Password'
            )
        ));

        $this->add(new Csrf('token'));
        $this->add(new Hidden('attempt'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'class' => 'btn btn-primary'
            )
        ));
    }
}
