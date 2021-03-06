<?php
namespace App\Controller;

use core\Controller;
use core\View;
use core\Config;
use library\Models\CorePagesModel;
use library\Models\SiteKeyWordsModel;
use library\User\User;
use appcms\controller\Userdemographics;

class About extends Controller
{
    public $display_page_content,
        $get_page_content,
        $core_page_number = 1115,
        $keyword_type = Config::CORE_PAGES_KEYWORDS,
        $getSiteKeywords,
        $siteName,
        $siteContent,
        $currUserInfo,
        $username,
        $userLogin,
        $userDemoInfo,
        $userId,
        $artistBio;

    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
    }


    /**
     * Before filter which is useful for login authentication
     * session control and cookies
     *
     * @return void
     */
    protected function before()
    {
        $this->siteName = parent::getSiteName();

        $this->getSiteKeywords = new SiteKeyWordsModel();
        $this->getSiteKeywords->find($this->keyword_type);
        $this->siteKeywords = $this->getSiteKeywords->data()->pages_Keywords;

        $this->getPageContent = new CorePagesModel();
        $this->getPageContent->find($this->core_page_number);
        $this->siteContent = $this->getPageContent->data()->corePages_Content;

        $this->currUserInfo = new User();
        //$this->userId =  $this->currUserInfo->data()->id;
        $this->userId = 4;

        $this->userDemoInfo = new Userdemographics();
        $this->userDemoInfo->find($this->userId);

        $this->artistBio = $this->userDemoInfo->data()->regMem_Bio;


        /* $loggedInUserName = new Userprofile();
       $this->username = $loggedInUserName->getLoggedInUserInfo();*/

    }

    /**
     * After filter which could potentially be good for destroying sessions etc
     *
     * @return void
     */
    protected function after(){}


    public function indexAction()
    {
        View::renderTemplate('about.phtml', [
            'tabTitle' => 'Bio',
            'pageTitle' => 'Bio',
            'siteName' => $this->siteName,
            'siteKeywords' => $this->siteKeywords,
            'pageDescription' => 'About Us',
            'username' =>  $this->username,
            'userLogin' => $this->userLogin,
            'pageContent' => $this->siteContent,
            'bio' => $this->artistBio,
        ]);

    }
}