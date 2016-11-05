{if not $filter->isEmpty() and not ($ignoreDisabled and $filter->isCommandFilterEmpty())}
    <div class="filter-status-value filter-status-value-{$typeClass}{if not $filter->isEnabled()} filter-status-value-disabled{/if}{if $isEditable} filter-status-value-editable{/if}" title="{$stringRepresentation|strip_tags:false}">
        <i class="filter-status-value-icon icon-{$icon}"></i>
        <span class="filter-status-value-expr">{$stringRepresentation}</span>
        <div class="filter-status-value-controls">

            {if $isEditable}
                <a href="#" class="js-edit-filter" data-id="{$id}" title="{$Captions->GetMessageString('EditFilter')}">
                    <i class="icon-edit"></i>
                </a>
            {/if}

            {if $isToggable}
                {if $filter->isEnabled()}
                    <a href="#" class="js-toggle-filter" data-value="false" data-id="{$id}" title="{$Captions->GetMessageString('DisableFilter')}">
                        <i class="icon-disable"></i>
                    </a>
                {else}
                    <a href="#" class="js-toggle-filter" data-value="true" data-id="{$id}" title="{$Captions->GetMessageString('EnableFilter')}">
                        <i class="icon-enable"></i>
                    </a>
                {/if}
            {/if}

            <a href="#" class="js-reset-filter" data-id="{$id}" title="{$Captions->GetMessageString('ResetFilter')}">
                <i class="icon-remove"></i>
            </a>
        </div>
    </div>
{/if}