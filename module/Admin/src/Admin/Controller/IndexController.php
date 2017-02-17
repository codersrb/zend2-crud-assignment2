<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Admin\Forms\LoginForm;
use Admin\Authentication\Adapter\AdminAuth as AuthAdapter;

class IndexController extends AbstractActionController
{
	/**
	 * @var Auth Holder property
	 */
    protected $auth;

	/**
	 * @todo Constructor method.
	 * @todo To autoload dependencies
	 */
    public function __construct()
    {
        if (!$this->auth instanceof AuthenticationService)
		{
			$this->auth = new AuthenticationService();
        }
    }

	/**
	 * @todo Index action
	 */
    public function indexAction()
    {
		/** Check if logged-in */
        if(!$this->auth->hasIdentity())
		{
			$this->redirect()->toRoute('admin-login');
        }

		return ['title' => 'Dashboard'];
    }


	/**
	 * @todo Login Action
	 */
    public function loginAction()
    {
		/** Check if logged-in */
        if($this->auth->hasIdentity())
		{
            $this->redirect()->toRoute('home');
        }

		$this->layout('login');


        $formErrors = [];
        $loginForm = new LoginForm();

		/**
		 * @todo If POST Request
		 */
        if($this->getRequest()->isPost())
		{

            $loginData = $this->getRequest()->getPost()->toArray();

            /** Validate form data */
            $loginForm->setInputFilter($this->getAdminTable()->loginFilter());

            $loginForm->setData($loginData);

            if($loginForm->isValid())
			{
                /** valid form, now login to system */
                $data = $loginForm->getData();

                $authAdapter = new AuthAdapter($data['uname'], $data['passwd'], $this->getAdminTable());
                $result = $this->auth->authenticate($authAdapter);

                // check if credential is invalid
                if(!$result->isValid())
				{
                    $formErrors = $result->getMessages();
                }
				else
				{
                    $this->flashMessenger()->addMessage('Logged In');
                    return $this->redirect()->toRoute('home');
                }
            }
			else
			{
                $formErrors = $loginForm->getMessages();
            }
        }

        return array(
            'loginForm' => $loginForm,
            'aErrors' => $formErrors,
			'title' => 'Login'
        );


    }

    public function logoutAction()
    {
        if($this->auth->hasIdentity())
		{
            $this->auth->clearIdentity();
        }

        $this->flashMessenger()->addErrorMessage('Successfully logged out');

        return $this->redirect()->toRoute('home');
    }

    /**
     *
     * @return \Admin\Model\AdminTable
     */
    protected function getAdminTable()
    {
        return $this->getServiceLocator()->get('Admin\Model\AdminTable');
    }

    /**
     *
     * @return \Admin\Model\UserTable
     */
    protected function getUserTable()
    {
        return $this->getServiceLocator()->get('Admin\Model\UserTable');
    }
}
