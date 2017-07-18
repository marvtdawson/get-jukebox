<?php
/**
 * Created by PhpStorm.
 * User: katan-hgmhub
 * Date: 3/16/17
 * Time: 12:26 AM
 */

namespace AppCMS\Controller;
use core\Config;
use core\Controller;
use core\View;
use library\Controller\Redirect;
use library\CSRF\CSRF;
use library\Form\Validation;
use library\Form\Input;
use Exception;
use library\Models\CorePagesModel;
use library\Models\SiteKeyWordsModel;

class Corepages extends Controller
{
    public $username,
        $userId,
        $curruserInfo,
        $fields,
        $displaypagecontent,
        $editpagecontent,
        $selectedpagenumber,
        $_tableName = 'cor3Pag3s',
        $keyword_type = Config::CPANEL_PAGES_KEYWORDS,
        $getSiteKeywords,
        $siteName,
        $getPageContent,
        $siteContent,
        $core_page_number,
        $pageSelection;

    public static $corePagesName;

    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
    }
    /**
     * Before filter which is useful for login authentication
     *
     * @return void
     */
    protected function before(){


        $this->siteName = parent::getSiteName();

        $this->getSiteKeywords = new SiteKeyWordsModel();
        $this->getSiteKeywords->find($this->keyword_type);
        $this->siteKeywords = $this->getSiteKeywords->data()->pages_Keywords;

        $loggedInUserName = new Userprofile();
        $this->username = $loggedInUserName->getLoggedInUserInfo()->regMem_Name;
        $this->userId =  $loggedInUserName->getLoggedInUserInfo()->id;

    }

    /**
     * After filter which could potentially be good for destroying sessions
     *
     * @return void
     */
    protected function after(){}


    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        $this->getPageContent = new CorePagesModel();

        //echo 'User index on User Page<br>';
        View::renderTemplate('/corepages/index.phtml', [
            'csrftoken' => CSRF::generatetoken(),
            'tabTitle' => 'Edit Core Pages',
            'pageTitle' => 'Edit Core Pages',
            'breadcrumb_index' => 'cPanel',
            'siteName' => $this->siteName,
            'siteKeywords' => $this->siteKeywords,
            'indexpage' => Config::APP_CMS_CPANEL_INDEX_PRETTY_URI,
            'cpanelindexpage' => Config::APP_CMS_CPANEL_INDEX_PRETTY_URI,
            'pageName' => 'Pages',
            'processuploadPageContent' => Config::APP_CMS_SAVE_CORE_PAGES_PRETTY_URI,
            'processPageSelection' => Config::APP_CMS_CORE_PAGES_PRETTY_URI,
            'pageContent' => $this->siteContent,
            'pageNames' => $this->getPageContent->corePagesName(),  // display page names in drop down
            'username' =>  $this->username
        ],  'appcms/views');

        // 1. check if the input data via post or get method exist
        if(Input::exists()){

            echo 'Input Does Exists<br>';

            // 2. get input field csrf_token and check if it exist
            if(CSRF::check(Input::get('csrf_token'))) {

                echo "CSRF Token Checked Out<br>";

                $validate = new Validation();

                $validation = $validate->check($_POST, array(
                    'core-page' => array(
                        'required' => true,
                    )
                ));

                if ($validation->passed()) {

                    echo 'Validation Passed<br>';
                    echo $this->core_page_number . '<br>';

                    try {

                        $this->core_page_number = $this->getSelectedValue();
                        $this->getPageContent->find($this->core_page_number);
                        $this->siteContent = $this->getPageContent->data()->corePages_Content;

                        $this->getPageContent->get($this->_tableName, array(
                            'corePages_N' => Input::get('core-page'),
                        ));

                    }catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else { // loop through each validation error that is return
                    foreach ($validation->errors() as $error) {
                        echo $error, "<br>";
                    }
                }
            }
        }


    }

    public function getSelectedValue()
    {
        if (isset($_GET['corepagenumber']) &&  !empty($_GET['corepagenumber'])) {

            $this->selectedpagenumber = $_GET['corepagenumber'];
            echo 'You are about to update page number: ' . $this->selectedpagenumber;
        }
        return $this->selectedpagenumber;
    }



    public function editPageContent(){

        echo 'Edit Page Content Function Found<br>';

        // 1. check if the input data via post or get method exist
        if(Input::exists()){

            echo 'Input Does Exists<br>';

            // 2. get input field csrf_token and check if it exist
            if(CSRF::check(Input::get('csrf_token'))) {

                echo "CSRF Token Checked Out<br>";


                $validate = new Validation();

                $validation = $validate->check($_POST, array(
                    'core-page' => array(
                        'required' => true,
                    )
                ));

                if ($validation->passed()) {

                    echo 'Validation Passed<br>';

                    $this->getPageContent = new CorePagesModel();
                    $this->core_page_number = Input::get('core-page');

                    echo $this->core_page_number . '<br>';


                    try {
                        $this->getPageContent->get($this->_tableName, array(
                            'corePages_N' => Input::get('core-page'),
                        ));

                        $this->siteContent = $this->getPageContent->find($this->core_page_number);

                    }catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else { // loop through each validation error that is return
                    foreach ($validation->errors() as $error) {
                        echo $error, "<br>";
                    }
                }
            }
        }
    }

    public function savePageContentAction(){
        Redirect::to(Config::APP_CMS_CORE_PAGES_PRETTY_URI);
    }
}