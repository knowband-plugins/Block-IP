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

class AdminKbBlockUserByAgentController extends ModuleAdminController
{
    protected $kb_module_name = 'kbblockuser';
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Block User by Agent', 'AdminKbBlockUserByAgentController');
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
        
        $this->context->smarty->assign('selected_nav', 'kbadminblockbyagent');
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
        $html = $this->module->adminDisplayWarning(
            $this->module->l('Note:: This option is not available in Free Version.', 'AdminKbBlockUserByAgentController')
        );
        $buy_link = $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/kb_buy_link.tpl'
            );
        $this->content .= $kb_tabs;
        $this->content .= $buy_link;
        $this->content .= $html;
        
        parent::initContent();
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
    }
}
