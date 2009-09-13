{jojoHook hook="profile_top"}      
{if $loggedIn && $thisuser}<p>Edit your <a href="{$editprofileprefix}/">user profile</a>.</p>{/if}
    <table>

{foreach from=$tabnames item=tab}

      {foreach from=$fields key=fieldname item=field}
      {if $field.tabname == $tab.tabname}
          {if $field.flags.PROFILE && ($field.html != '') && ($field.privacy!='y' && $field.privacy!='Y')}
              {if $field.error}<tr class="error">{else}<tr class="{if $field.type=='hidden' || $field.type=='privacy'}hidden {/if}{$field.mode}">{/if}
              {if $field.type=='texteditor' ||  $field.type=='wysiwygeditor' || $field.type=='bbeditor' || $field.showlabel=='no'}
              <td class="col2" colspan="2" id="wrap_{$fieldname}">
              {else}
              <td>{if $field.type=='permissions'}{$field.name}:{else}{$field.name}:{/if}</td>
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