<?php

/*
 * Paypal Donation
 *
 * @author Knowband <support@knowband.com>
 * @copyright Copyright (c) 2018 Knowband.
 * @license GNU/LGPL version 3
 * @version 1.1.3
 * @link www.knowband.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class donationpaypal extends Module
{

    public function __construct()
    {
        $this->bootstrap = true;
        $this->name = 'donationpaypal';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Knowband';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();

        $this->displayName = $this->l('Donation Paypal');
        $this->description = $this->l('Allows customers to give you a donation with Paypal service.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

        if (!Configuration::get('donationPaypal-business_id'))
            $this->warning = $this->l('You must enter your PayPal ID');
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        return (parent::install() &&
                Configuration::updateValue('donationPaypal-page_style', 'paypal') &&
                Configuration::updateValue('donationPaypal-display_message', 'Support our organization by donating with PayPal') &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayFooter') &&
                $this->registerHook('displayLeftColumn') &&
                $this->registerHook('displayRightColumn')
                );
    }

    public function uninstall()
    {
        return (parent::uninstall() &&
                Configuration::deleteByName('donationPaypal-business_id') &&
                Configuration::deleteByName('donationPaypal-item_name') &&
                Configuration::deleteByName('donationPaypal-page_style') &&
                Configuration::deleteByName('donationPaypal-cbt') &&
                Configuration::deleteByName('donationPaypal-display_message')
                );
    }

    private function _displayInfos()
    {
        $this->context->smarty->assign(
                array(
                    'moduleName' => $this->displayName,
                    'description' => $this->description,
                    'version' => $this->version
                )
        );

        return $this->display(__FILE__, 'infos.tpl');
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submit' . $this->name)) {
            $business_id = strval(Tools::getValue('business_id'));

            if (!$business_id || empty($business_id))
                $output .= $this->displayError($this->l('Invalid PayPal ID'));
            else {
                Configuration::updateValue('donationPaypal-business_id', $business_id);
                Configuration::updateValue('donationPaypal-item_name', strval(Tools::getValue('item_name')));
                Configuration::updateValue('donationPaypal-page_style', Tools::getValue('page_style'));
                Configuration::updateValue('donationPaypal-cbt', strval(Tools::getValue('cbt')));
                Configuration::updateValue('donationPaypal-display_message', strval(Tools::getValue('display_message')));
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        $output .= $this->_displayInfos();

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        // Get default Language
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'logo'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('PayPal ID'),
                    'name' => 'business_id',
                    'desc' => $this->l('Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed'),
                    'class' => 'fixed-width-xl',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Description of the donation'),
                    'name' => 'item_name',
                    'desc' => $this->l('If blank, the donor is able to fill in the field paypal interface'),
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Custom payment page style for paypal'),
                    'name' => 'page_style',
                    'desc' => $this->l('If you have a custom page size, you can choose "custom", otherwise leave default'),
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array(
                                'page_style' => 'paypal',
                                'name' => 'default'
                            ),
                            array(
                                'page_style' => 'primary',
                                'name' => 'custom'
                            )
                        ),
                        'id' => 'page_style',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text for the Return to Merchant button on the PayPal Payment Complete page'),
                    'name' => 'cbt',
                    'desc' => $this->l('If blanck, the text reads "Return to donations coordinator" by default.'),
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Message posted on the website'),
                    'name' => 'display_message',
                    'required' => false,
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;

        // Load current value
        $helper->fields_value['business_id'] = Configuration::get('donationPaypal-business_id');
        $helper->fields_value['item_name'] = Configuration::get('donationPaypal-item_name');
        $helper->fields_value['page_style'] = Configuration::get('donationPaypal-page_style');
        //$helper->fields_value['no_note'] = Configuration::get('donationPaypal-no_note');
        //$helper->fields_value['cn'] = Configuration::get('donationPaypal-cn');
        $helper->fields_value['cbt'] = Configuration::get('donationPaypal-cbt');
        $helper->fields_value['display_message'] = Configuration::get('donationPaypal-display_message');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS(($this->_path) . 'views/css/donationpaypal.css', 'all');
    }

    public function hookDisplayLeftColumn()
    {
        $this->assignement();
        return $this->display(__FILE__, '/views/templates/front/display_left_column.tpl');
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayLeftColumn();
    }

    public function hookDisplayFooter()
    {
        $this->assignement();
        return $this->display(__FILE__, '/views/templates/front/display_footer.tpl');
    }

    private function assignement()
    {
        $this->context->smarty->assign(
                array(
                    'title' => $this->l('Donate'),
                    'business_id' => Configuration::get('donationPaypal-business_id'),
                    'company_name' => str_replace(' ', '', Configuration::get('donationPaypal-blockcontactinfos_company')),
                    'iso_code' => $this->context->language->iso_code,
                    'item_name' => Configuration::get('donationPaypal-item_name'),
                    'page_style' => Configuration::get('donationPaypal-page_style'),
                    //'no_note' => Configuration::get('donationPaypal-no_note'),
                    //'cn' => Configuration::get('donationPaypal-cn'),
                    'cbt' => Configuration::get('donationPaypal-cbt'),
                    'display_message' => Configuration::get('donationPaypal-display_message'),
                    'language_code' => $this->context->language->language_code
                )
        );
        $this->context->smarty->assignByRef('currency', $this->context->currency);
        $this->context->smarty->assignByRef('customer', $this->context->customer);
        if ($idAddress = Address::getFirstCustomerAddressId($this->context->customer->id)) {
            $this->context->smarty->assignByRef('address', new Address($idAddress));
        }
    }

}
