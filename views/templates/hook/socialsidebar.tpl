{**
*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <DARK SIDE TEAM> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Poul-Henning Kamp
 * ----------------------------------------------------------------------------
 *
*}
<div id='socialsidebar' {if $socialsidebar.position == true}style='right: 0px;left: unset; display: flex; align-items: flex-end; flex-direction: column;'{/if}>
    {if $socialsidebar.facebook != null}
        <div class='group-icon facebook {if $socialsidebar.hover == true}hover{/if}'>
            <a href='{$socialsidebar.facebook}' rel='nofollow'>
                <i class='icon icon-facebook'>&#xf30c;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.instagram != null}
        <div class='group-icon instagram {if $socialsidebar.hover == true}hover{/if}'>
             <a href='{$socialsidebar.instagram}' rel='nofollow'>
                <i class='icon icon-instagram'>&#xf32d;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.twitter != null}
        <div class='group-icon twitter {if $socialsidebar.hover == true}hover{/if}'>
             <a href='{$socialsidebar.twitter}' rel='nofollow'>
                <i class='icon icon-twitter'>&#xf309;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.youtube != null}
        <div class='group-icon youtube {if $socialsidebar.hover == true}hover{/if}'>
             <a href='{$socialsidebar.youtube}' rel='nofollow'>
                <i class='icon icon-youtube'>&#xf315;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.printerest != null}
        <div class='group-icon printerest {if $socialsidebar.hover == true}hover{/if}'>
             <a href={$socialsidebar.printerest}' rel='nofollow'>
                <i class='icon icon-pinterest'>&#xf312;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.tumbrl != null}
        <div class='group-icon tumbrl {if $socialsidebar.hover == true}hover{/if}'>
             <a href='{$socialsidebar.tumbrl}' rel='nofollow'>
                <i class='icon icon-tumblr'>&#xf173;</i>
            </a>
        </div>
    {/if}
    {if $socialsidebar.linkedin != null}
        <div class='group-icon linkedin {if $socialsidebar.hover == true}hover{/if}'>
             <a href='{$socialsidebar.linkedin}' rel='nofollow'>
                <i class='icon icon-linkedin'>&#xf0e1;</i>
            </a>
        </div>
    {/if}
</div>