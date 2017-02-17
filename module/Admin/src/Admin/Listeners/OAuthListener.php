<?php
namespace Admin\Listeners;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class OAuthListener extends AbstractListenerAggregate
{

    /**
     * Holds the attached listeners
     *
     * @var array
     */
    protected $listeners = array();

    /**
     * Method to register this listener on the render event
     *
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, __CLASS__ . '::onDispatch', 1000);
    }

    /**
     * Method executed when the dispatch event is triggered
     *
     * @param MvcEvent $e
     * @return void
     */
    public static function onDispatch(MvcEvent $e)
    {
        if ($e->getRequest() instanceof \Zend\Console\Request) {
            return;
        }

        //TODO: API TOKEN VERIFICATION
        /*
        if (! $server->verifyResourceRequest(Request::createFromGlobals())) {
            $model = new JsonModel(array(
                'errorCode' => $server->getResponse()->getStatusCode(),
                'errorMsg' => $server->getResponse()->getStatusText()
            ));

            $response = $e->getResponse();
            $response->setContent($model->serialize());
            $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $response->setStatusCode($server->getResponse()
                ->getStatusCode());

            return $response;
        } */

    }
}
