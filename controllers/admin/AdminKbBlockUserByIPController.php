<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

include_once(_PS_MODULE_DIR_.'kbblockuser/classes/KbBlockUserByIP.php');

class AdminKbBlockUserByIPController extends ModuleAdminController
{
    public $kb_smarty;
    public $all_languages = array();
    public $countryNameArray = array();
    public $zoneNameArray = array();
    protected $kb_module_name = 'kbblockuser';
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->context = Context::getContext();
        $this->list_no_link = true;
        $this->all_languages = $this->getAllLanguages();
        $this->table = 'kb_block_user_ip';
        $this->className = 'KbBlockUserByIP';
        $this->identifier = 'id_block_ip';
        $this->lang = false;
        $this->display = 'list';
        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Block User by IP', 'AdminKbBlockUserByIPController');
            
        $this->fields_list = array(
            'id_block_ip' => array(
                'title' => $this->module->l('ID', 'AdminKbBlockUserByIPController'),
                'search' => true,
                'align' => 'text-center',
            ),
            'ip' => array(
                'title' => $this->module->l('IP Address', 'AdminKbBlockUserByIPController'),
                'search' => true,
                'align' => 'text-center',
            ),
            'active' => array(
                'title' => $this->module->l('Active', 'AdminKbBlockUserByIPController'),
                'align' => 'text-center',
                'active' => 'active',
                'type' => 'bool',
                'order_key' => 'status',
                'search' => true
            ),
            'date_upd' => array(
                'title' => $this->module->l('Last Update', 'AdminKbBlockUserByIPController'),
                'type' => 'date',
            )
        );
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }
    
    /*
     * Function for returning the URL of PrestaShop Root Modules Directory
     */
    protected function getModuleDirUrl()
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
     * Function for returning all the languages in the system
     */
    public function getAllLanguages()
    {
        return Language::getLanguages(false);
    }
    
    /**
    * Prestashop Default Function in AdminController.
    * Assign smarty variables for all default views,
     * list and form, then call other init functions
    */
    public function initContent()
    {
        if (isset($this->context->cookie->kb_redirect_error)) {
            $this->errors[] = $this->context->cookie->kb_redirect_error;
            unset($this->context->cookie->kb_redirect_error);
        }

        if (isset($this->context->cookie->kb_redirect_success)) {
            $this->confirmations[] = $this->context->cookie->kb_redirect_success;
            unset($this->context->cookie->kb_redirect_success);
        }
        
        $this->context->smarty->assign('selected_nav', 'kbadminblockbyip');
        $this->context->smarty->assign(
            'admin_cf_configure_controller',
            $this->context->link->getAdminLink('AdminModules', true)
            .'&configure='.urlencode($this->module->name)
            .'&tab_module='.$this->module->tab
            .'&module_name='.urlencode($this->module->name)
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
        
        $kb_tabs = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->kb_module_name.'/views/templates/admin/kb_tabs.tpl'
        );
        
        $buy_link = $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/kb_buy_link.tpl'
            );
        $this->content .= $kb_tabs;
        $this->content .= $buy_link;
        
        parent::initContent();
    }
    
    /**
     * Function used to render the form for this controller
     *
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function renderForm()
    {
        $this->fields_form =  array(
            'id_form' => 'kbcf_add_block_user_by_ip',
            'legend' => array(
                'title' => $this->l('Add IP', 'AdminKbBlockUserByIPController'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->module->l('IP Address', 'AdminKbBlockUserByIPController'),
                    'name' => 'ip',
                    'col' => 4,
                    'hint' => $this->module->l('Enter IP Address to block', 'AdminKbBlockUserByIPController'),
                    'required' => true,
                    'desc' => $this->module->l('Wildcard supported.').'<br/>'
                    .$this->module->l('Examples:-').'<br/>'
                    .'172.0.0.1'.'<br/>'
                    .'172.0.0.*'.'<br/>'
                    .'172.0.*.*'.'<br/>'
                    .'172.*.*.*'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->module->l('Active', 'AdminKbBlockUserByIPController'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'value' => 1
                        ),
                        array(
                            'value' => 0
                        )
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->module->l('Save', 'AdminKbBlockUserByIPController'),
                'class' => 'btn btn-default pull-right form_kb_add_ip'
            ),
        );
        return parent::renderForm();
    }
    
    /** Prestashop Default Function in AdminController
     * @TODO uses redirectAdmin only if !$this->ajax
     * @return bool
     */
    public function postProcess()
    {
        if (Tools::isSubmit('active'.$this->table)) {
            $id = Tools::getValue('id_block_ip');
            $object = new $this->className((int) $id);
            if ($object->active == 1) {
                $object->active = 0;
            } else {
                $object->active = 1;
            }
            $object->update();
            $this->context->cookie->__set(
                'kb_redirect_success',
                $this->module->l('The status has been successfully updated.', 'AdminKbBlockUserByIPController')
            );
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
        }
        
        if (Tools::isSubmit('submitBulkenableSelection' . $this->table)) {
            $this->processBulkEnableSelection();
            $this->context->cookie->__set(
                'kb_redirect_success',
                $this->module->l('The status has been successfully updated.', 'AdminKbBlockUserByIPController')
            );
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
        }
        
        if (Tools::isSubmit('submitBulkdisableSelection' . $this->table)) {
            $this->processBulkDisableSelection();
            $this->context->cookie->__set(
                'kb_redirect_success',
                $this->module->l('The status has been successfully updated.', 'AdminKbBlockUserByIPController')
            );
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
        }
        
        parent::postProcess();
    }
    
    /**
     * Function to create new record
     */
    public function processAdd()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            if (KbBlockUserByIP::getIpTableCount()) {
                $ip = Tools::getValue('ip');
                $active = Tools::getValue('active');
                $is_ip_exist = KbBlockUserByIP::isKbIPExists($ip);
                if (!Tools::isEmpty($is_ip_exist)) {
                    $this->errors[] = $this->module->l('IP already exists.', 'AdminKbBlockUserByIPController');
                }

                if (is_array($this->errors) && empty($this->errors)) {
                    $kbblockip = new KbBlockUserByIP();
                    $kbblockip->active = $active;
                    $kbblockip->ip = $ip;
                    if ($kbblockip->add()) {
                        $this->context->cookie->__set('kb_redirect_success', $this->module->l('IP Address is successfully added.', 'AdminKbBlockUserByIPController'));
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
                    }
                }
            } else {
                $this->context->cookie->__set('kb_redirect_error', $this->module->l('Maximum limit for adding IP has been reached. Kindly buy paid version in order to continue.', 'AdminKbBlockUserByIPController'));
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
            }
        }
    }
    
    /**
     * Function to update existing record
     */
    public function processUpdate()
    {
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            $ip = Tools::getValue('ip');
            $active = Tools::getValue('active');
            $id = Tools::getValue('id_block_ip');
            $is_ip_exist = KbBlockUserByIP::getKbIPByIDAndIp($id, $ip);
            if (!empty($is_ip_exist) && is_array($is_ip_exist)) {
                $this->errors[] = $this->module->l('IP already exists.', 'AdminKbBlockUserByIPController');
            }
            
            if (is_array($this->errors) && empty($this->errors)) {
                $kbblockip = new KbBlockUserByIP($id);
                $kbblockip->active = $active;
                $kbblockip->ip = $ip;
                if ($kbblockip->update()) {
                    $this->context->cookie->__set('kb_redirect_success', $this->module->l('IP Address is successfully updated.', 'AdminKbBlockUserByIPController'));
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminKbBlockUserByIP', true));
                }
            }
        }
    }
    
    /*
     * Function for returning the absolute path of the module directory
     */
    protected function getKbModuleDir()
    {
        return _PS_MODULE_DIR_.$this->kb_module_name.'/';
    }
    
    /*
     * Default function, used here to include JS/CSS files for the module.
     */
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS($this->getKbModuleDir() . 'views/css/admin/kb_admin.css');
        $this->context->controller->addJS($this->getKbModuleDir() . 'views/js/admin/kbblockuser_admin.js');
        $this->context->controller->addJS($this->getKbModuleDir() . 'views/js/velovalidation.js');
        $this->context->controller->addJS($this->getKbModuleDir() . 'views/js/admin/validation_admin.js');
    }
    
    /**
    * Function used display toolbar in page header
    */
    public function initPageHeaderToolbar()
    {
        if (!Tools::getValue('id_block_ip') && !Tools::isSubmit('add'.$this->table)) {
            $this->page_header_toolbar_btn['new_template'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->module->l('Add IP', 'AdminKbBlockUserByIPController'),
                'icon' => 'process-icon-new'
            );
        }
        if (Tools::getValue('id_block_ip') || Tools::isSubmit('id_block_ip')) {
            $this->page_header_toolbar_btn['kb_cancel_action'] = array(
                'href' => self::$currentIndex.'&token='.$this->token,
                'desc' => $this->module->l('Cancel', 'AdminKbBlockUserByIPController'),
                'icon' => 'process-icon-cancel'
            );
        } else {
            $this->page_header_toolbar_btn['back_url'] = array(
                'href' => 'javascript: window.history.back();',
                'desc' => $this->module->l('Back', 'AdminKbBlockUserByIPController'),
                'icon' => 'process-icon-back'
            );
        }
        parent::initPageHeaderToolbar();
    }
    
    protected function processBulkEnableSelection()
    {
        return $this->processBulkStatusSelection(1);
    }

    protected function processBulkDisableSelection()
    {
        return $this->processBulkStatusSelection(0);
    }

    /**
    * Function used to update the bulk action selection
    */
    protected function processBulkStatusSelection($status)
    {
        $boxes = Tools::getValue($this->table.'Box');
        $result = true;
        if (is_array($boxes) && !empty($boxes)) {
            foreach ($boxes as $id) {
                $object = new $this->className((int) $id);
                $object->active = (int) $status;
                $result &= $object->update();
            }
        }
        return $result;
    }
}
