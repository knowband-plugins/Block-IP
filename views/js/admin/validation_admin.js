/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

$(document).ready(function() { 
    $('button[name="submitAddkb_block_user_ip"]').click(function() {
        var error = false;
        $(".error_message").remove();
        $('input[name="ip"]').removeClass('error_field');
        
        var ip_mand = velovalidation.checkMandatory($('input[name="ip"]'));
        if (ip_mand != true) {
            error = true;
            $('input[name="ip"]').addClass('error_field');
            $('input[name="ip"]').after('<span class="error_message">' + ip_mand + '</span>');
        }
        
        if (error) {
           return false;
       }

        /*Knowband button validation start*/
            $("button[name='submitAddkb_block_user_ip']").attr('disabled', 'disabled');
            $('#kb_block_user_ip_form').submit();
        /*Knowband button validation end*/
        
    });
});