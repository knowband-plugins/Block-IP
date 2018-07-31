<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_ . 'kbblockuser/classes/KbBlockUserByIP.php');

class Kbblockuser extends Module
{

    const MODEL_FILE = 'model.sql';

    public function __construct()
    {
        $this->name = 'kbblockuser';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'knowband';
        $this->need_instance = 1;
        $this->module_key = 'c9a2aa5d15622c0a3a4af6b9b4c0b5fd';
        $this->bootstrap = true;

        parent::__construct();
        $this->displayName = $this->l('Knowband Blocker - Block Bot/User by IP, Country or User Agent - Free Version');
        $this->description = $this->l('Banned User by IP, Country or User Agent');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        
        if (!class_exists('KbIpLocation_Ip')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Ip2.php');
        }
        if (!class_exists('KbIpLocation_Service_CsvWebhosting')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/CsvWebhosting.php');
        }
        if (!class_exists('KbIpLocation_Service_GeoIp')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/GeoIp.php');
        }
        if (!class_exists('KbIpLocation_Results')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Results.php');
        }
        if (!class_exists('KbIpLocation_Service_Abstract')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/Abstract.php');
        }
        if (!class_exists('KbIpLocation_Service_CsvMaxmind')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/CsvMaxmind.php');
        }
        if (!class_exists('KbIpLocation_Service_Mysql')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/Mysql.php');
        }
        if (!class_exists('KbIpLocation_Url')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Url.php');
        }
        if (!class_exists('KbNet_GeoIP')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/Net/GeoIP.php');
        }
        if (!class_exists('KbNet_GeoIP_DMA')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/Net/GeoIP/DMA.php');
        }
        if (!class_exists('KbNet_GeoIP_Location')) {
            require_once(dirname(__FILE__) . '/libraries/ip_location/IpLocation/Service/Net/GeoIP/Location.php');
        }
    }

    /*
     * To install Database Table during install of the module
     */
    protected function installModel()
    {
        $installation_error = false;
        if (!file_exists(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file not found.');
            $installation_error = true;
        } elseif (!is_readable(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file is not readable.');
            $installation_error = true;
        } elseif (!$sql = Tools::file_get_contents(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file is empty.');
            $installation_error = true;
        }

        if (!$installation_error) {
            /*
             * Replace _PREFIX_ and ENGINE_TYPE with default Prestashop values
             */
            $sql = str_replace(
                array('_PREFIX_', 'ENGINE_TYPE'),
                array(_DB_PREFIX_, _MYSQL_ENGINE_),
                $sql
            );
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
            foreach ($sql as $query) {
                if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(trim($query))) {
                    $installation_error = true;
                }
            }
        }
        if ($installation_error) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Install function to install module
     */
    public function install()
    {
        /*
         * Create Database table and if there is some problem then display error message
         */
        if (!$this->installModel()) {
            $this->custom_errors[] = $this->l('Error occurred while installing/upgrading modal.');
            return false;
        }

        /*
         * Register various hook functions
         */
        if (!parent::install() ||
                !$this->registerHook('header') ||
                !$this->registerHook('actionDispatcher')) {
            return false;
        }

        $this->installKbModuleTabs();
               
        
        /*
         * Convert array of the default value of the configuration
         *  setting into jsonEncode and then save
         *  the value in the Configuration table
         */
        $defaultsettings = Tools::jsonEncode($this->getDefaultSettings());
        Configuration::updateValue('KB_BLOCK_USER', $defaultsettings);
        $default_custom_message = $this->display(__FILE__, 'views/templates/admin/custom_message.tpl');
        Configuration::updateValue('KB_BLOCK_USER_CUSTOM_MESSAGE', Tools::jsonEncode(Tools::htmlentitiesUTF8($default_custom_message)));
        
        if (!Configuration::get('BLOCK_USER_MAIL_CHECK')) {
            $mail_dir = dirname(__FILE__) . '/mails/en';
            $languages = Language::getLanguages(false);
            $language_count = count($languages);
            for ($i = 0; $i < $language_count; $i++) {
                if ($languages[$i]['iso_code'] != 'en') {
                    $new_dir = dirname(__FILE__) . '/mails/' . $languages[$i]['iso_code'];
                    $this->copyfolder($mail_dir, $new_dir);
                }
            }
            Configuration::updateGlobalValue('BLOCK_USER_MAIL_CHECK', 1);
        }
        
        return true;
    }

    /*
     * Function to uninstall the module with 
     * unregister various hook and 
     * also delete the configuration setting
     */

    public function uninstall()
    {
        if (!parent::uninstall() ||
                !$this->unregisterHook('actionGetIDZoneByAddressID') ||
                !Configuration::deleteByName('KB_BLOCK_USER')) {
            return false;
        }

        $this->uninstallKbModuleTabs();
        return true;
    }
    
    //Function to copy the folder from source to destination
    public function copyfolder($source, $destination)
    {
        $directory = opendir($source);
        if (!Tools::file_exists_no_cache($destination)) {
            mkdir($destination);
        }
        while (($file = readdir($directory)) != false) {
            if (version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
                copy($source . '/' . $file, $destination . '/' . $file);
            } else {
                Tools::copy($source . '/' . $file, $destination . '/' . $file);
            }
        }
        closedir($directory);
    }

    /*
     * Function to set the default configuration setting
     */

    private function getDefaultSettings()
    {
        $settings = array(
            'enable' => 0,
            'display_custom_message' => 0,
            'include_headerfooter' => 1,
            'email_notification' => 1,
            'subject' => $this->l('Customer has contact you regarding unblocking the site'),
        );
        return $settings;
    }
    
    /*
     * Function to create admin tabs
     */
    protected function installKbModuleTabs()
    {
        $lang = Language::getLanguages();
        $tab = new Tab();
        $tab->class_name = 'AdminKbBlockUserByIP';
        $tab->module = $this->name;
        $tab->active = 0;
        $tab->id_parent = 0;
        foreach ($lang as $l) {
            $tab->name[$l['id_lang']] = $this->l('Knowband Block User by IP');
        }
        $tab->save();
        
        $tab1 = new Tab();
        $tab1->class_name = 'AdminKbBlockUserByCountry';
        $tab1->module = $this->name;
        $tab1->active = 0;
        $tab1->id_parent = 0;
        foreach ($lang as $l) {
            $tab1->name[$l['id_lang']] = $this->l('Knowband Block User by Country');
        }
        $tab1->save();
        
        $tab2 = new Tab();
        $tab2->class_name = 'AdminKbBlockUserByAgent';
        $tab2->module = $this->name;
        $tab2->active = 0;
        $tab2->id_parent = 0;
        foreach ($lang as $l) {
            $tab2->name[$l['id_lang']] = $this->l('Knowband Block User by Agent');
        }
        $tab2->save();
        
        $tab3 = new Tab();
        $tab3->class_name = 'AdminKbBlockCustomerRequest';
        $tab3->module = $this->name;
        $tab3->active = 0;
        $tab3->id_parent = 0;
        foreach ($lang as $l) {
            $tab3->name[$l['id_lang']] = $this->l('Knowband Customer Request');
        }
        $tab3->save();

        return true;
    }
    
    /*
    * Function to remove admin tabs
    */
    protected function uninstallKbModuleTabs()
    {
        $parentTab = new Tab(Tab::getIdFromClassName('AdminKbBlockUserByIP'));
        $parentTab->delete();
        
        $parentTab1 = new Tab(Tab::getIdFromClassName('AdminKbBlockUserByCountry'));
        $parentTab1->delete();
        
        $parentTab2 = new Tab(Tab::getIdFromClassName('AdminKbBlockUserByAgent'));
        $parentTab2->delete();

        return true;
    }

    /*
     * Function to create module panel
     */

    public function getContent()
    {
        $errors = array();
        /*
         * Function to submit the configuration setting values,
         * first by validating the form data and then save into the DB
         */
        if (Tools::isSubmit('configsubmit' . $this->name)) {
            $db_value = Tools::jsonDecode(Configuration::get('KB_BLOCK_USER'), true);
            $config = Tools::getValue('kbblockuser');
            $config['display_custom_message'] = 0;
            Configuration::updateValue('KB_BLOCK_USER', Tools::jsonEncode($config));
            $this->context->cookie->__set('kb_redirect_success', $this->l('Configuration successfully updated.'));
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        }

        $output = '';
        if (isset($this->context->cookie->kb_redirect_success)) {
            $output .= $this->displayConfirmation($this->context->cookie->kb_redirect_success);
            unset($this->context->cookie->kb_redirect_success);
        }
        /*
         * Fetch configuration settings from the Database and convert them into array
         */
        $this->kb_block_user = Tools::jsonDecode(Configuration::get('KB_BLOCK_USER'), true);
        /*
         * Persistence the configuration setting form data
         */
        $config_form_data = Tools::getValue('kbblockuser');
        $config_field_value = array(
            'kbblockuser[enable]' => isset($config_form_data['enable']) ? $config_form_data['enable'] : $this->kb_block_user['enable'],
            'kbblockuser[display_custom_message]' => isset($config_form_data['display_custom_message']) ? $config_form_data['display_custom_message'] : $this->kb_block_user['display_custom_message'],
        );

        /*
         * loop to fetch all language with default language in an array
         */
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        /*
         * Create configuration setting form
         */
        $this->fields_form = $this->getConfigurationForm();
        /*
         * Create helper form for configuration setting form
         */
        $form = $this->getform(
            $this->fields_form,
            $languages,
            $this->l('Configuration'),
            false,
            $config_field_value,
            'general',
            'config'
        );
        $this->context->smarty->assign('form', $form);
        $this->context->smarty->assign('selected_nav', 'config');
        $this->context->smarty->assign('img_path', $this->getModuleDirUrl().$this->name);
        $this->context->smarty->assign(
            'admin_cf_configure_controller',
            $this->context->link->getAdminLink('AdminModules', true)
            . '&configure=' . urlencode($this->name) . '&tab_module=' . $this->tab
        );
        $this->context->smarty->assign(
            'admin_cf_user_ip_controller',
            $this->context->link->getAdminLink('AdminKbBlockUserByIP', true)
        );
        $this->context->smarty->assign(
            'admin_cf_user_country_controller',
            $this->context->link->getAdminLink('AdminKbBlockUserByCountry', true)
        );
        $this->context->smarty->assign(
            'admin_cf_user_agent_controller',
            $this->context->link->getAdminLink('AdminKbBlockUserByAgent', true)
        );
        $this->context->smarty->assign(
            'admin_cf_custom_request_controller',
            $this->context->link->getAdminLink('AdminKbBlockCustomerRequest', true)
        );
        $this->context->smarty->assign('firstCall', false);
        $this->context->smarty->assign(
            'kb_tabs',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/kb_tabs.tpl'
            )
        );
        
        $this->context->smarty->assign(
            'kb_buy_link',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/kb_buy_link.tpl'
            )
        );

        //Loads JS and CSS
        $this->setKbMedia();

        /*
         * Generate form using Helper class
         */
        $helper = new Helper();
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->override_folder = 'helpers/';
        $helper->base_folder = 'form/';
        $tpl = 'Form_custom.tpl';
        $helper->setTpl($tpl);
        $tpl = $helper->generate();

        $output .= $tpl;
        return $output;
    }

    /*
     * Function to create configuration setting form
     */
    private function getConfigurationForm()
    {
        $form = array(
            'form' => array(
                'id_form' => 'general_configuration_form',
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable'),
                        'type' => 'switch',
                        'name' => 'kbblockuser[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the plugin')
                    ),
                    array(
                        'label' => $this->l('Display Custom Message'),
                        'type' => 'switch',
                        'name' => 'kbblockuser[display_custom_message]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable to display custom message to the customer'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right form_general'
                ),
            ),
        );
        return $form;
    }

    /*
     * Load JS and CSS file
     */

    public function setKbMedia()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/admin/kb_admin.css');
        $this->context->controller->addJS($this->_path . 'views/js/admin/kbblockuser_admin.js');
        $this->context->controller->addJS($this->_path . 'views/js/velovalidation.js');
        $this->context->controller->addJS($this->_path . 'views/js/admin/validation_admin.js');
    }

    /*
     * Function to create Helper Form
     */

    public function getform($field_form, $languages, $title, $show_cancel_button, $field_value, $id, $action)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->fields_value = $field_value;
        $helper->name_controller = $this->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $title;
        if ($id == 'general') {
            $helper->show_toolbar = true;
        } else {
            $helper->show_toolbar = false;
        }
        $helper->table = $id;
        $helper->firstCall = true;
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = $show_cancel_button;
        $helper->submit_action = $action . 'submit' . $this->name;
        return $helper->generateForm(array('form' => $field_form));
    }

    /*
     * Function to get the URL upto module directory
     */

    private function getModuleDirUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        return $module_dir;
    }

    /*
     * Function to get the URL of the store,
     * this function also checks if the store
     * is a secure store or not and returns the URL accordingly
     */

    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Function is used to block the user as Action Dispatcher executes first
     */
    public function hookActionDispatcher($params)
    {
        $config = Tools::jsonDecode(Configuration::get('KB_BLOCK_USER'), true);
        if ($config['enable'] &&
            (Context::getContext()->controller->controller_type == 'front')) {
            //block user by IP Address
            $blockedIP = KbBlockUserByIP::getKbBlockIP();
            foreach ($blockedIP as $ip) {
                $exit_ip = trim($ip['ip']);
                $ip1 = str_replace("*", "0", $exit_ip);
                $ip2 = str_replace("*", "255", $exit_ip);
                $ip1 = ip2long($ip1);
                $ip2 = ip2long($ip2);
                $givenip = $this->getUserIp();
                $givenip = ip2long($givenip);
                if ($givenip >= $ip1 && $givenip <= $ip2) {
                    $this->kbDisplayBlockMessage('ip');
                }
            }
        }
    }
    
    /*
     * Function is used to display blocked message to the user
     */
    protected function kbDisplayBlockMessage($reason = null)
    {
        $config = Tools::jsonDecode(Configuration::get('KB_BLOCK_USER'), true);
        $blocker = '';
        if ($reason == 'ip') {
            $blocker = $this->l('IP is blocked');
        }
        $this->context->cookie->__set(
            'kbcustomer_block_reason',
            $blocker
        );
        if ($config['display_custom_message']) {
            Tools::redirect($this->context->link->getModuleLink('kbblockuser', 'kbcustomdisplay'));
        } elseif (!$config['display_custom_message']) {
            header('HTTP/1.0 403 Forbidden', true, 403);
            die($this->l('You are not allowed to access the page.'));
        }
    }
    
    /*
     * Function is used to get user IP
     */
    public function getUserIp()
    {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
