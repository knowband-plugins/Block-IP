
    <div class="box" style="{if $include_header} margin: 50px;    box-shadow: rgba(0, 0, 0, 0.17) 0px 5px 13px;background:#fff;{else} margin: 75px;
    box-shadow: rgba(0, 0, 0, 0.17) 1px 1px 5px 3px;
    background: #fff;padding: 50px;{/if}">
        {$custom_message}{*Variable contains HTML content, escape not required*}

        {if $email_notification}
            {if $cookie_success == ''}
                <div class="container" style="    border: 2px solid #777777;padding: 15px;margin-left: 15%;margin-right: 15%;margin-top: 2%;margin-bottom: 2%;">
                    <h4>{l s='Enter your email address requesting system administrator to allow acess of the site.' mod='kbblockuser'}</h4>
                    <p class='error_message' style="color:red; {if $cookie_error != ''}display:block;{else}display:none;{/if}">{if $cookie_error !=''}{$cookie_error|escape:'htmlall':'UTF-8'}{/if}</p>
                    <form action="{$action|escape:'quotes':'UTF-8'}" method="post">
                        <div class="form-group">
                            <label for="kb_request_email" class="required">{l s='Enter Email' mod='kbblockuser'}</label>
                            <input  type="email" class="form-control" data-validate="isEmail" required name="kb_request_email" id="kb_request_email" placeholder="{l s='Enter your email' mod='kbblockuser'}" value='{if is_object($cookie_data) && !empty($cookie_data)}{$cookie_data->email|escape:'htmlall':'UTF-8'}{/if}'>
                        </div>
                        <div class="form-group">
                            <label for="kb_request_message">{l s='Message' mod='kbblockuser'}</label>
                            <textarea  placeholder="{l s='Enter your message' mod='kbblockuser'}" class="form-control" name="kb_request_message" style="width: 100%">{if is_object($cookie_data) && !empty($cookie_data)}{$cookie_data->kb_request_message|escape:'htmlall':'UTF-8'}{/if}</textarea>
                        </div>
                        <div class="form-group" style="text-align: right;">
                            <button type="submit" name="submitRequest" id="submitRequest" class="btn btn-default button button-small">
                                <span>
                                    {l s='Send Request' mod='kbblockuser'}
                                    <i class="icon-chevron-right right"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            {elseif $cookie_success != ''}
                <div style="    background-color: #55c65e;border-color: #48b151;color: #fff;padding: 1px;margin-bottom: 12px;">
                    <h4 style="text-align: center;">
                        {$cookie_success|escape:'htmlall':'UTF-8'}
                    </h4>
                </div>
            {/if}
        {/if}

    </div>        

<style>
            .form-group {
                margin-bottom: 15px;
            }
            label {
                color: #333;
                display: inline-block;
                margin-bottom: 5px;
                font-weight: bold;
                margin: 0;
                padding: 0;
                border: 0;

                font-size: 100%;
                vertical-align: baseline;
            }
            .form-control {
                padding: 3px 5px;
                height: 27px;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
            .form-control {
                display: block;
                width: 100%;
                height: 32px;
                padding: 6px 12px;
                font-family: inherit;
                font-size: 13px;
                line-height: 1.42857;
                color: #9c9b9b;
                vertical-align: middle;
                background-color: #fff;
                border: 1px solid #d6d4d4;
                border-radius: 0px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
                transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
                font-family: inherit;
                font-size: 100%;
                margin: 0;
            }
            textarea.form-control {
                height: auto;
            }
            textarea {
                resize: none;
            }
            textarea {
                overflow: auto;
            }
            {if !$include_header}
                html {
                    padding: 30px 10px;
                    font-size: 16px;
                    line-height: 1.4;
                    color: #737373;
                    background: #f0f0f0;
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }
                .button.button-small {
                    font: bold 13px/17px Arial, Helvetica, sans-serif;
                    color: #fff;
                    background: #6f6f6f;
                    border: 1px solid;
                    border-color: #666666 #5f5f5f #292929 #5f5f5f;
                    padding: 0;
                    text-shadow: 1px 1px rgba(0, 0, 0, 0.24);
                    -moz-border-radius: 0;
                    -webkit-border-radius: 0;
                    border-radius: 0;
                }
                .button.button-small span {
                    display: block;
                    padding: 3px 8px 3px 8px;
                    border: 1px solid;
                    border-color: #8b8a8a;
                }
                .button.button-small span i {
                    vertical-align: 0px;
                    margin-right: 5px;
                }
                [class^="icon-"] {
                    display: inline-block;
                    font: normal normal normal 14px/1 FontAwesome;
                    font-size: inherit;
                    text-rendering: auto;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    transform: translate(0, 0);
                }
            {/if}
            
        </style>
        
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
*}