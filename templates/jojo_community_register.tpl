{if $success}{include file='jojo_register_success.tpl'}
{else}

{if $error}<div class="error text-danger">{$error}</div>{/if}
{if $message}<div class="message">{$message}</div>{/if}

{jojoHook hook="register_before_form"}
<form id="register-form" method="post" action="" enctype="multipart/form-data" class="contact-form no-ajax" role="form">
    {if $redirect}<input type="hidden" name="redirect" id="redirect" value="{$redirect}" />{/if}
    {jojoHook hook="register_top"}

    {foreach from=$tabnames item=tab}{foreach from=$fields key=fieldname item=field}
    {if $field.tabname == $tab.tabname && $field.flags.REGISTER}
    <div id="wrap_{$fieldname}" class="form-group{if $field.error} has-error{/if}{if $field.type=='hidden' || $field.type=='privacy'} hidden{/if}" >
          {if $field.type=='permissions'}{$field.name}:
          {elseif !($field.type=='texteditor' ||  $field.type=='wysiwygeditor' || $field.type=='bbeditor' || $field.showlabel=='no')}<label for="fm_{$fieldname}" class="control-label">{$field.name}{if $field.required=='yes'} <span class="required">*</span>{/if}</label>
          {/if}
          {if $OPTIONS.jojo_community_public_profile == 'yes' && $field.flags.PRIVACY}<div class="input-group">{/if}
         {$field.html}
          {if $OPTIONS.jojo_community_public_profile == 'yes' && $field.flags.PRIVACY}
          <span class="input-group-addon"><label><input type="checkbox" name="privacy[{$fieldname}]" id="privacy_{$fieldname}" value="Y"{if $field.privacy=='y' || $field.privacy=='Y'} checked="checked"{/if} /> private</label></span>
          </div>
          <input type="hidden" name="hasprivacy[{$fieldname}]" value="1" />
          {/if}
    </div>
    {/if}
    {/foreach}{/foreach}

    {if $OPTIONS.jojo_community_register_captcha == 'yes'}<div class="form-group captcha">
        {if $OPTIONS.captcha_recaptcha=="yes" && $OPTIONS.captcha_sitekey}<div class="g-recaptcha" data-sitekey="{$OPTIONS.captcha_sitekey}"></div>
        {else}
        <label for="CAPTCHA" class="control-label">Spam prevention:</label>
        <img src="external/php-captcha/visual-captcha.php" width="200" height="60" alt="Visual CAPTCHA" /><br />
        Please enter the {$OPTIONS.captcha_num_chars|default:3} letter code in the box below. This helps us prevent spam.<br />
        <em>Code is not case-sensitive</em><br />
        <input type="text" class="form-control text required" size="8" name="CAPTCHA" id="CAPTCHA" value="" />
    {/if}
    </div>
    {/if}


    {jojoHook hook="register_bottom"}

    <div class="form-group submit">
        <button type="submit" name="submit" id="submit" value="Register" class="btn btn-primary" >Register</button>
   </div>

</form>

{/if}