<div class="kb_custom_tabs kb_custom_panel">
    <span>
        <a class="kb_custom_tab {if $selected_nav == 'config'}kb_active{/if}" title="{l s='Module Configurations' mod='kbblockuser'}" id="kbcf_config_link" href="{$admin_cf_configure_controller}">{*Variable contains URL content, escape not required*}
            <i class="icon-gear"></i>
            {l s='Module Configuration' mod='kbblockuser'}
        </a>
    </span>

    <span>
        <a class="kb_custom_tab {if $selected_nav == 'kbadminblockbyip'}kb_active{/if}" title="{l s='Block User by IP Address' mod='kbblockuser'}" id="kbcf_block_ip" href="{$admin_cf_user_ip_controller}">{*Variable contains URL content, escape not required*}
            <i class="icon-laptop"></i>
            {l s='Block User by IP Address' mod='kbblockuser'}
        </a>
    </span>
    <span>
        <a class="kb_custom_tab {if $selected_nav == 'kbadminblockbycountry'}kb_active{/if}" title="{l s='Block User by Country' mod='kbblockuser'}" id="kbcf_block_country" href="{$admin_cf_user_country_controller}">{*Variable contains URL content, escape not required*}
            <i class="icon-globe"></i>
            {l s='Block User by Country' mod='kbblockuser'}
        </a>
    </span>
    <span>
        <a class="kb_custom_tab {if $selected_nav == 'kbadminblockbyagent'}kb_active{/if}" title="{l s='Block User by User Agent' mod='kbblockuser'}" id="kbcf_block_agent" href="{$admin_cf_user_agent_controller}">{*Variable contains URL content, escape not required*}
            <i class="icon-code"></i>
            {l s='Block User by User Agent' mod='kbblockuser'}
        </a>
    </span>
    <span>
        <a class="kb_custom_tab {if $selected_nav == 'kbadminblockrequest'}kb_active{/if}" title="{l s='Block User by User Agent' mod='kbblockuser'}" id="kbcf_block_request" href="{$admin_cf_custom_request_controller}">{*Variable contains URL content, escape not required*}
            <i class="icon-user"></i>
            {l s='Blocked Customer Request' mod='kbblockuser'}
        </a>
    </span>
</div>
        
        <script>
            var kb_numeric = "{l s='Field should be numeric.' mod='kbblockuser'}";
            var kb_positive = "{l s='Field should be positive.' mod='kbblockuser'}";
            var check_for_all = "{l s='Kindly check for all languages.' mod='kbblockuser'}";
            var no_select = "{l s='Please select placement' mod='kbblockuser'}";
            var empty_field = "{l s='Field cannot be empty' mod='kbblockuser'}";
        </script>
        
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin tpl file
*}