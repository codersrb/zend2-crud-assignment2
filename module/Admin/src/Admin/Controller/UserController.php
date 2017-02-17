<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Admin\Forms\EditBarForm;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\File\Transfer\Adapter\Http;

/**
 * BarController
 *
 * @author
 *
 * @version
 *
 */
class UserController extends AbstractActionController
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
     * @todo Index Action
     */
    public function indexAction()
	{
		/** Check if logged-in */
        if(!$this->auth->hasIdentity())
		{
			$this->redirect()->toRoute('admin-login');
        }


        $data = $this->getUserTable()->select()->toArray();

		// \Zend\Debug\Debug::dump($data->buffer() );
		// \Zend\Debug\Debug::dump( get_class_methods( $data ) );
		// die;

        return new ViewModel([
			'users' => $data,
			'title' => 'User Listing'
		]);
    }





    public function addAction() {
        if (!$this->auth->hasIdentity()) {
            $this->redirect()->toRoute('admin-login');
        }

        $aErrors = array();

        // get the form
        $oEditBarForm = new EditBarForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $aUnfilteredData = $request->getPost()->toArray();

            unset($aUnfilteredData['submit']);

            // TODO: Validate
//            $aInsertData = $aUnfilteredData;

            $oEditBarForm->setInputFilter($this->getBarTable()
                            ->getAddInputFilter());

            $oEditBarForm->setData($aUnfilteredData);

            // validate

            if ($oEditBarForm->isValid()) {

                $aInsertData = $oEditBarForm->getData();

                // handle files
                $files = $request->getFiles()->toArray();

                $data['defaultphoto'] = $files['defaultphoto']['name'] != '' ? $files['defaultphoto']['name'] : null;

                try {
                    if ($data['defaultphoto'] !== null) {
                        $size = new Size(array(
                            'max' => 2048000
                        ));
                        $isImage = new IsImage();
                        $filename = $data['defaultphoto'];

                        $adapter = new Http();
                        $adapter->setValidators(array(
                            $size,
                            $isImage
                                ), $filename);

                        if (!$adapter->isValid($filename)) {
                            $errors = array();
                            foreach ($adapter->getMessages() as $key => $row) {
                                $errors[] = $row;
                            }
                            $oEditBarForm->setMessages(array(
                                'avatar' => $errors
                            ));
                        }

                        $destPath = 'public/uploads/images/';
                        $adapter->setDestination($destPath);

                        $fileinfo = $adapter->getFileInfo();
                        preg_match('/.+\/(.+)/', $fileinfo['defaultphoto']['type'], $matches);
                        $extension = $matches[1];
                        $newFilename = sprintf('%s.%s', sha1(uniqid(time(), true)), $extension);
                        // Debug::dump($newFilename,$label='New file name',$echo=true);
                        $adapter->addFilter('File\Rename', array(
                            'target' => $destPath . $newFilename,
                            'overwrite' => true
                        ));

                        if ($adapter->receive($filename)) {

                            // FIXME: SET DYNAMIC URI
                            $aInsertData['defaultphoto'] = 'http://www.nachtadmin.com/uploads/images/' . $newFilename;
                        } else {
                            $aErrors = $adapter->getMessages();
                        }
                    }

                    $iInsertId = $this->getBarTable()->insert($aInsertData);
                    return $this->redirect()->toRoute('bar-admin');
                } catch (\Exception $e) {

                    $aErrors = $e->getMessages();
                }
            } else {
                $aErrors = $oEditBarForm->getMessages();
            }
        }

        return array(
            'editBarForm' => $oEditBarForm
        );
    }

    public function editAction() {

        //$aErrors
        $aErrors = [];

        // get the id from URI
        $id = $this->params('id');

        // holds original Bar data
        $aBar = $this->getBarTable()->select(['barid' => $id])->current();

        //if it is an invalid entry, user will be redirected to list page with error
        if (empty($aBar)) {
            $this->flashMessenger()->addErrorMessage("No such Bar exist !! ");
            return $this->redirect()->toRoute('bar-admin');
        } else {

            // create a new form for edit
            $oEditBarForm = new EditBarForm();

            //update submit button
            $oEditBarForm->get('submit')->setValue("Update bar details");

            // set the original data for edit
            $oEditBarForm->setData($aBar);
        }

        //handle the request
        $request = $this->getRequest();

        if ($request->isPost()) {
            // get the posted data & set the data to form again, in case validation fails
            $aUnfilteredData = $request->getPost()->toArray();
            $oEditBarForm->setData($aUnfilteredData);

            // get input filter for edit
            $oEditBarForm->setInputFilter($this->getBarTable()->getEditInputFilter());

            //check validation
            if ($oEditBarForm->isValid()) {

                $aUpdateData = $oEditBarForm->getData();

                // handle files
                $files = $request->getFiles()->toArray();

                $data['defaultphoto'] = $files['defaultphoto']['name'] != '' ? $files['defaultphoto']['name'] : null;

                // validation passed update the posted data
                try {
                    if ($data['defaultphoto'] !== null) {
                        $size = new Size(array(
                            'max' => 2048000
                        ));
                        $isImage = new IsImage();
                        $filename = $data['defaultphoto'];

                        $adapter = new Http();
                        $adapter->setValidators(array(
                            $size,
                            $isImage
                                ), $filename);

                        if (!$adapter->isValid($filename)) {
                            $errors = array();
                            foreach ($adapter->getMessages() as $key => $row) {
                                $errors[] = $row;
                            }
                            $oEditBarForm->setMessages(array(
                                'avatar' => $errors
                            ));
                        }

                        $destPath = 'public/uploads/images/';
                        $adapter->setDestination($destPath);

                        $fileinfo = $adapter->getFileInfo();
                        preg_match('/.+\/(.+)/', $fileinfo['defaultphoto']['type'], $matches);
                        $extension = $matches[1];
                        $newFilename = sprintf('%s.%s', sha1(uniqid(time(), true)), $extension);
                        // Debug::dump($newFilename,$label='New file name',$echo=true);
                        $adapter->addFilter('File\Rename', array(
                            'target' => $destPath . $newFilename,
                            'overwrite' => true
                        ));

                        if ($adapter->receive($filename)) {
                            // FIXME: SET DYNAMIC URI
                            $aUpdateData['defaultphoto'] = 'http://www.nachtadmin.com/uploads/images/' . $newFilename;
                        } else {
                            $aErrors = $adapter->getMessages();
                        }
                    }

                    //remove bar id from update
                    unset($aUpdateData['barid']);
                    unset($aUpdateData['submit']);

                    $this->getBarTable()->update($aUpdateData, ['barid' => $id]);

                    $this->flashMessenger()->addSuccessMessage("Bar updated successfully");

                    return $this->redirect()->toRoute('bar-admin');
                } catch (\Exception $e) {
                    $aErrors = $e->getMessage();
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            }
        }

//        \Zend\Debug\Debug::dump($aErrors);

        return array(
            'editBarForm' => $oEditBarForm
        );
    }

    /**
     * Delete a bar
     * @return Response
     */
    public function deleteAction() {
        $id = $this->params('id');

        try {
            $this->getBarTable()->delete(['barid' => $id]);
            $this->flashMessenger()->addSuccessMessage("Bar deleted successfully");

            return $this->redirect()->toRoute('bar-admin');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
    }



    /**
     * Return existing Bar Table
     *
     * @return \Admin\Model\BarTable | NULL
     */
    protected function getBarTable() {
        return $this->getServiceLocator()->get('Admin\Model\BarTable');
    }



	/**
     * Return existing User Table
     *
     * @return \Admin\Model\UserTable | NULL
     */
    protected function getUserTable() {
        return $this->getServiceLocator()->get('Admin\Model\UserTable');
    }

}
