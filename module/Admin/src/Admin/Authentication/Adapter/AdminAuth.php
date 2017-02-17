<?php
namespace Admin\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Session\Container;

class AdminAuth implements AdapterInterface
{

    /**
     * Holds the credentials
     *
     * @var string
     */
    private $mbrlogin = null;

    private $mbrpasswd = null;

    protected $adminTable = null;

    /**
     * Sets mbrlogin and mbrpasswd for authentication
     *
     * @return void
     */
    public function __construct($uname, $passwd, $adminTable)
    {
        $this->uname = $uname;
        $this->passwd = $passwd;
        $this->adminTable = $adminTable;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $result = $this->adminTable->select(array(
            'uname' => $this->uname
        ))->current();


        // verify passwd
        if ($result['passwd'] === $this->passwd) {

            $session = new Container('oauth_session');
            $session->setExpirationSeconds(36000);

            $hydrator = new ClassMethods();

            $response = new Result(Result::SUCCESS, $result, array(
                'Authentication successful.'
            ));
        } else {
            $response = new Result(Result::FAILURE, NULL, isset($result['errors']) ? $result['errors'] : array(
                'Invalid credentials.'
            ));
        }

        return $response;
    }
}
