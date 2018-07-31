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
    $('#kb_buy_link').insertAfter($('#form-kb_block_user_ip'));
    $('#kb_buy_link').insertAfter($('#kb_block_user_ip_form'));
    $("input[name='kbblockuser[display_custom_message]']").prop('disabled', true);
    $("input[name='kbblockuser[display_custom_message]']").closest('.form-group').hide();
    
    
    if (typeof img_path != 'undefined') {
       $('<div class="vss_demo_block2"><img src="'+img_path+'/views/img/kbblockuser/enablesetting.png" id="kb_custom_message_block"></div>').insertAfter($("input[name='kbblockuser[display_custom_message]']").closest('.form-group'));
       $('#kb_custom_message_block').after($('.vss_overlay_paid2'));
       $('#kb_custom_message_block').hover(function () {
           if ($('.vss_overlay_paid2').length) {
               if ($('.vss_overlay_paid2').is(':visible')) {
//                   $('#kb_custom_message_block').removeClass('vss_demo_block_hovered2');
//                   $('.vss_overlay_paid2').hide();
               } else {
                   $('#kb_custom_message_block').addClass('vss_demo_block_hovered2');
                   $('.vss_overlay_paid2').show();
               }
           }
       });
   }

    
});
