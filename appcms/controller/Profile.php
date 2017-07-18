<?php
/**
 * Created by PhpStorm.
 * User: katan-hgmhub
 * Date: 4/5/17
 * Time: 11:47 PM
 */

namespace appcms\controller;
use core\Config;
use core\Controller;
use core\View;
use Library\Controller\Styles;
use Library\CSRF\CSRF;
use library\Users\User;
use Library\Controller\United_States;
use library\Form\Validation;
use library\Form\Input;
use library\Controller\Redirect;
use Exception;
use library\Models\Model;

class Profile extends Controller
{
    public $regSuccess,
        $username,
        $userLogin;


    public function __construct()
    {

    }


    /**
     * Before filter which is useful for login authentication
     *
     * @return void
     */
    protected function before()
    {
        $this->siteName = parent::getSiteName();

        $this->getSiteKeywords = new SiteKeyWordsModel();
        $this->getSiteKeywords->find($this->keyword_type);
        $this->siteKeywords = $this->getSiteKeywords->data()->pages_Keywords;

        $this->getContactEntries = new ContactModel();
        $this->getContactEntries->find($this->entry);
        $this->formEntry = $this->getContactEntries->data()->id;

        $loggedInUserName = new Userprofile();
        $this->username = $loggedInUserName->getLoggedInUserInfo()->regMem_Name;
        $this->userId =  $loggedInUserName->getLoggedInUserInfo()->id;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }


    public function indexAction()
    {
        View::renderTemplate('memberprofile/index.phtml', [
            'tabtitle' => 'Home of Get Marvelle',
            'formName' => Config::REGISTER_FORM_NAME,
            'processform' => Config::REGISTER_FORM_PROCESS,
            'submitbutton' =>  Config::REGISTER_FORM_SUBMIT_BUTTON,
            'userReg' => $this->regSuccess,
            'site_name' => $this->siteName,
            'username' =>  $this->username,
            'userLogin' => $this->userLogin,
        ]);
    }


}