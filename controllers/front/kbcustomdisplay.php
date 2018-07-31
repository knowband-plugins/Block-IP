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

include_once(_PS_MODULE_DIR_ . 'kbblockuser/classes/KbBlockUserByIP.php');

class KbBlockUserkbcustomdisplayModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function setMedia()
    {
        parent::setMedia();
    }
    
    /**
     * Initializes front controller: sets smarty variables,
     * class properties, redirects depending on context, etc.
     *
     * @throws PrestaShopException
     */
    public function init()
    {
        parent::init();
    }
    
    public function initContent()
    {
        parent::initContent();
        $config = Tools::jsonDecode(Configuration::get('KB_BLOCK_USER'), true);
        if (!$config['display_custom_message']) {
            Tools::redirect(Context::getContext()->link->getPageLink('index', true));
        }
        if (!$this->isKbUserBlocked()) {
            Tools::redirect(Context::getContext()->link->getPageLink('index', true));
        }
    }
    
    /**
     * Function to check if user is blocked or not
     */
    public function isKbUserBlocked()
    {
        //block user by IP Address
        $blockedIP = KbBlockUserByIP::getKbBlockIP();
        $kbblock = new Kbblockuser();
        foreach ($blockedIP as $ip) {
            $exit_ip = trim($ip['ip']);
            $ip1 = str_replace("*", "0", $exit_ip);
            $ip2 = str_replace("*", "255", $exit_ip);
            $ip1 = ip2long($ip1);
            $ip2 = ip2long($ip2);
            $givenip = $kbblock->getUserIp();
            $givenip = ip2long($givenip);
            if ($givenip >= $ip1 && $givenip <= $ip2) {
                return true;
            }
        }
        return false;
    }
    
    /** Prestashop Default Function in FrontController
     * @TODO uses redirectAdmin only if !$this->ajax
     * @return bool
     */
    public function postProcess()
    {
        parent::postProcess();
    }
    
    /*
     * Function for returning the URL of PrestaShop Root Modules Directory
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
     * Function for checking SSL
     */
    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
}
