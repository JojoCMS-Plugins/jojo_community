{if $OPTIONS.jojo_community_public_profile == 'yes'}
{jojoHook hook="profile_top"}
    <table>

{foreach from=$tabnames item=tab}

      {foreach from=$fields key=fieldname item=field}
      {if $field.tabname == $tab.tabname}
          {if $field.flags.PROFILE && ($field.html != '') && ($field.privacy!='y' && $field.privacy!='Y')}
              <tr class="{if $field.type=='hidden' || $field.type=='privacy'}hidden {/if}{tif $field.mode $field.mode ''}">
              {if $field.type=='texteditor' ||  $field.type=='wysiwygeditor' || $field.type=='bbeditor' || $field.showlabel=='no'}
              <td class="col2" colspan="2" id="wrap_{$fieldname}">
              {else}
              <td>{$field.name}:&nbsp;</td>
              <td>
              {/if}
                  {$field.html}
              </td>
              </tr>
          {/if}

      {/if}
      {/foreach}

{/foreach}

      </table>

{jojoHook hook="profile_bottom"}
{if $loggedIn && $thisuser}<p><br><a href="{$editprofileprefix}/" class="btn btn-primary">Edit your user profile</a></p>{/if}
{else}
<p>User profiles have been disabled.</p>
{/if}