{if $success}
{include file='jojo_register_success.tpl'}
{else}
{if $error}<div class="error">{$error}</div>{/if}
{if $message}<div class="message">{$message}</div>{/if}
{jojoHook hook="register_before_form"}
<form method="post" action="" enctype="multipart/form-data">
{if $redirect}<input type="hidden" name="redirect" id="redirect" value="{$redirect}" />{/if}
  <div class="register-form">
    <h3>Registration Information</h3>

      {jojoHook hook="register_top"}
      <table>
      <tr>
        <th>&nbsp;</th>
<th>&nbsp;</th>
        <th>Keep<br />private?</th>
      </tr>

{foreach from=$tabnames item=tab}

      {foreach from=$fields key=fieldname item=field}
      {if $field.tabname == $tab.tabname}
          {if $field.flags.REGISTER}
              {if $field.error}<tr class="error">{else}<tr class="{if $field.type=='hidden' || $field.type=='privacy'}hidden {/if}">{/if}
              {if $field.type=='texteditor' ||  $field.type=='wysiwygeditor' || $field.type=='bbeditor' || $field.showlabel=='no'}
              <td class="col2" colspan="2" id="wrap_{$fieldname}">
              {else}
              <td class="col1">{if $field.type=='permissions'}{$field.name}:{else}<label for="fm_{$fieldname}">{$field.name}:</label>{/if}</td>
              <td class="col2" title="{$field.help|replace:"\"":""}" id="wrap_{$fieldname}">
              {/if}
                  {$field.html}
                  {if $field.error}<img src="images/cms/icons/error.png" border="0" alt="Error: {$field.error}"  title="Error: {$field.error}" />{/if}
                  {if $field.required=="yes"} <img src="images/cms/icons/star.png" title="Required Field" alt="" />{/if}
              </td>
              {*<td>{if $field.flags.PRIVACY}<input type="checkbox" name="" value="" title="Keep this data private"{if $field.flags.PRIVATE} checked="checked"{/if} />{/if}</td>*}
<td>{if $field.flags.PRIVACY}<input type="hidden" name="hasprivacy[{$fieldname}]" value="1" /><input type="checkbox" name="privacy[{$fieldname}]" id="privacy_{$fieldname}" value="Y"{if $field.privacy=='y' || $field.privacy=='Y'} checked="checked"{/if} />{else}&nbsp;{/if}</td>
              </tr>
          {/if}

      {/if}
      {/foreach}

{/foreach}

{if $OPTIONS.jojo_community_register_captcha == 'yes'}
    <br />
    <td class="col1"><label for="CAPTCHA">Spam prevention:</label></td>
    <td>
        Please enter the {$OPTIONS.captcha_num_chars|default:3} letter code in the box below. This helps us prevent spam.<br />
        <img src="external/php-captcha/visual-captcha.php" width="200" height="60" alt="Visual CAPTCHA" /><br />
        <em>Code is not case-sensitive</em><br />
        <input type="text" class="text" size="8" name="CAPTCHA" id="CAPTCHA" value="" />*
    </td>
{/if}

      </table>

      {jojoHook hook="register_bottom"}

	  <label for="submit"></label><input class="button" type="submit" name="submit" id="submit" value="Register" />
	  <div class="clear"></div>
  </div>

</form>
<div class="clear"></div>

{/if}