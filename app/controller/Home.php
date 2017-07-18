<?php
namespace App\Controller;
use core\Config;
use core\Controller;
use core\View;
use library\Sessions\Sessions;
use library\User\User;
use appcms\controller\Userdemographics;

class Home extends Controller  {

    public $regSuccess,
        $username,
        $userLogin,
        $siteName,
        $siteYear,
        $currUserInfo,
        $userDemoInfo,
        $userId,
        $artistBio;

    /**
     * Before filter which is useful for login authentication
     *
     * @return void
     */
    protected function before()
    {
        $this->siteName = parent::getSiteName();
        $this->siteYear = parent::getSiteDate();

        /*$loggedInUserName = new Userprofile();
       $this->username = $loggedInUserName->userName;
       $this->userLogin = $loggedInUserName->checkLoggedInUser();*/

        $this->currUserInfo = new User();
        //$this->userId =  $this->currUserInfo->data()->id;
        $this->userId = 4;

        $this->userDemoInfo = new Userdemographics();
        $this->userDemoInfo->find($this->userId);

        $this->artistBio = $this->userDemoInfo->data()->regMem_Bio;
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

        if(Sessions::exists('success')){
            $this->regSuccess = Sessions::flash('success');
        }

        View::renderTemplate('index.phtml', [
            'tabTitle' => Config::SITE_NAME,
            'formName' => Config::REGISTER_FORM_NAME,
            'processform' => Config::REGISTER_FORM_PROCESS,
            'submitbutton' =>  Config::REGISTER_FORM_SUBMIT_BUTTON,
            'userReg' => $this->regSuccess,
            'siteName' => $this->siteName,
            'siteYear' => $this->siteYear,
            'username' =>  $this->username,
            'userLogin' => $this->userLogin,
            'bio'=> $this->artistBio,
        ]);
    }


}