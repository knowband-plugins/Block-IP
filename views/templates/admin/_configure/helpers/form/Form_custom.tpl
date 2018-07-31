{extends file='helpers/form/form.tpl'}

{block name='defaultForm'}

    {$kb_tabs} {*Variable contains html content, escape not required*}
    {$form} {*Variable contains html content, escape not required*}
    {$kb_buy_link} {*Variable contains html content, escape not required*}
    <script type="text/javascript">
        var validate_css_length = "{l s='Length of CSS should be less than 10000' mod='kbblockuser'}";
        var kb_numeric = "{l s='Field should be numeric.' mod='kbblockuser'}";
        var kb_positive = "{l s='Field should be positive.' mod='kbblockuser'}";
        var img_path = "{$img_path}"; {*Variable contains URL, escape not required*}
            velovalidation.setErrorLanguage({
            alphanumeric: "{l s='Field should be alphanumeric.' mod='kbblockuser'}",
            digit_pass: "{l s='Password should contain atleast 1 digit.' mod='kbblockuser'}",
            empty_field: "{l s='Field cannot be empty.' mod='kbblockuser'}",
            number_field: "{l s='You can enter only numbers.' mod='kbblockuser'}",            
            positive_number: "{l s='Number should be greater than 0.' mod='kbblockuser'}",
            maxchar_field: "{l s='Field cannot be greater than # characters.' mod='kbblockuser'}",
            minchar_field: "{l s='Field cannot be less than # character(s).' mod='kbblockuser'}",
            invalid_date: "{l s='Invalid date format.' mod='kbblockuser'}",
            valid_amount: "{l s='Field should be numeric.' mod='kbblockuser'}",
            valid_decimal: "{l s='Field can have only upto two decimal values.' mod='kbblockuser'}",
            maxchar_size: "{l s='Size cannot be greater than # characters.' mod='kbblockuser'}",
            specialchar_size: "{l s='Size should not have special characters.' mod='kbblockuser'}",
            maxchar_bar: "{l s='Barcode cannot be greater than # characters.' mod='kbblockuser'}",
            positive_amount: "{l s='Field should be positive.' mod='kbblockuser'}",
            maxchar_color: "{l s='Color could not be greater than # characters.' mod='kbblockuser'}",
            invalid_color: "{l s='Color is not valid.' mod='kbblockuser'}",
            specialchar: "{l s='Special characters are not allowed.' mod='kbblockuser'}",
            script: "{l s='Script tags are not allowed.' mod='kbblockuser'}",
            style: "{l s='Style tags are not allowed.' mod='kbblockuser'}",
            iframe: "{l s='Iframe tags are not allowed.' mod='kbblockuser'}",
            image_size: "{l s='Uploaded file size must be less than #.' mod='kbblockuser'}",
            html_tags: "{l s='Field should not contain HTML tags.' mod='kbblockuser'}",
            number_pos: "{l s='You can enter only positive numbers.' mod='kbblockuser'}",
});
    </script>
    <div class="vss_overlay_paid2" style="display: none;">
        <div>
       <span class="vss_overlay_paid_text2">
           {l s='You are using the Free version of the module. Click here to buy the Paid version which is having the advanced features.' mod='kbblockuser'}
       </span>
       <br>
       <br>
       <a target="_blank" class="vss_free_version_link2" href="https://www.knowband.com/prestashop-block-bot-user-ip-country-user-agent">
           <span class="vss_free_version_button2">{l s='Buy Now' mod='kbblockuser'}</span>
       </a>
        </div>
</div>
{/block}
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
    * @copyright 2015 Knowband
    * @license   see file: LICENSE.txt
    *
    * Description
    *
    * Admin tpl file
    *}

