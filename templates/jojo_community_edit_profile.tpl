{if $error}<div class="error">{$error}</div>{/if}
{if $message}<div class="message">{$message}</div>{/if}

<form method="post" action="" enctype="multipart/form-data">
<div class="register-form">

  <table>
      <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        {if $OPTIONS.jojo_community_public_profile == 'yes'}<th>Keep<br />private?</th>{/if}
      </tr>

{foreach from=$tabnames item=tab}

      {foreach from=$fields key=fieldname item=field}
      {if $field.tabname == $tab.tabname}
          {if $field.flags.PROFILE}
              {if $field.error}<tr class="error">{else}<tr class="{if $field.type=='hidden' || $field.type=='privacy'}hidden {/if}{tif $field.mode $field.mode ''}">{/if}
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
              {if $OPTIONS.jojo_community_public_profile == 'yes'}
              <td>{if $field.flags.PRIVACY}<input type="hidden" name="hasprivacy[{$fieldname}]" value="1" /><input type="checkbox" name="privacy[{$fieldname}]" id="privacy_{$fieldname}" value="Y"{if $field.privacy=='y' || $field.privacy=='Y'} checked="checked"{/if} />{else}&nbsp;{/if}</td>
              {/if}
              </tr>
          {/if}

      {/if}
      {/foreach}

{/foreach}

      </table>
<label for="submit"></label><input class="button" type="submit" name="update" id="submit" value="Update Profile" />
      <div class="clear"></div>
</div>
</form>

<h3>Change Password</h3>
<p>Passwords can be changed from our <a href="change-password/" rel="nofollow">change password</a> page.</p>
{if $OPTIONS.jojo_community_public_profile == 'yes'}
<h3>View profile</h3>
<p>View <a href="{$public_uri}" rel="nofollow">your user profile</a>.</p>
{/if}