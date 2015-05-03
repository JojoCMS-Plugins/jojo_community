{if $error}<div class="error text-danger">{$error}</div>{/if}
{if $message}<div class="message">{$message}</div>{/if}

<form id="register-form" method="post" action="" enctype="multipart/form-data" class="contact-form no-ajax" role="form">


    {foreach from=$tabnames item=tab}{foreach from=$fields key=fieldname item=field}
    {if $field.tabname == $tab.tabname && $field.flags.PROFILE}
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

   <div class="form-group submit">
        <button type="submit" name="update" id="submit" value="Update Profile" class="btn btn-primary" >Update Profile</button>
   </div>
</form>

<h3>Change Password</h3>
<p>Passwords can be changed from our <a href="change-password/" rel="nofollow">change password</a> page.</p>
{if $OPTIONS.jojo_community_public_profile == 'yes'}
<h3>View profile</h3>
<p>View <a href="{$public_uri}" rel="nofollow">your user profile</a>.</p>
{/if}