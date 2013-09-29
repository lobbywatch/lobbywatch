<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'list/grid.tpl', 4, false),array('modifier', 'escapeurl', 'list/grid.tpl', 5, false),array('function', 'jsbool', 'list/grid.tpl', 5, false),array('function', 'jsstring', 'list/grid.tpl', 299, false),array('block', 'style_block', 'list/grid.tpl', 6, false),)), $this); ?>
<table
    id="<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
"
    class="pgui-grid grid legacy <?php echo $this->_tpl_vars['DataGrid']['Classes']; ?>
"
    data-grid-hidden-values="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['HiddenValuesJson'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"
    data-inline-edit="{ &quot;enabled&quot;:&quot;<?php echo smarty_function_jsbool(array('value' => $this->_tpl_vars['DataGrid']['UseInlineEdit']), $this);?>
&quot;, &quot;request&quot;:&quot;<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['InlineEditRequest'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
&quot}"
    <?php $this->_tag_stack[] = array('style_block', array()); $_block_repeat=true;smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <?php echo $this->_tpl_vars['DataGrid']['Styles']; ?>

    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <?php echo $this->_tpl_vars['DataGrid']['Attributes']; ?>
>
<thead>
    <?php if ($this->_tpl_vars['DataGrid']['ActionsPanelAvailable']): ?>
    <tr>
        <td colspan="<?php echo $this->_tpl_vars['DataGrid']['ColumnCount']; ?>
" class="header-panel">
            <div class="btn-toolbar pull-left">
                <div class="btn-group">
                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['InlineAdd']): ?>
                    <button class="btn inline_add_button" href="#">
                        <i class="pg-icon-add-record"></i>
                        <?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>

                    </button>
                <?php endif; ?>

                <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['AddNewButton']): ?>
                    <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['AddNewButton'] == 'modal'): ?>
                        <button class="btn"
                                dialog-title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>
"
                                content-link="<?php echo $this->_tpl_vars['DataGrid']['Links']['ModalInsertDialog']; ?>
"
                                modal-insert="true">
                            <i class="pg-icon-add-record"></i>
                            <?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>

                        </button>
                    <?php else: ?>
                        <a class="btn" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['SimpleAddNewRow'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
                            <i class="pg-icon-add-record"></i>
                            <?php echo $this->_tpl_vars['Captions']->GetMessageString('AddNewRecord'); ?>

                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['DeleteSelectedButton']): ?>
                        <button class="btn delete-selected">
                            <i class="pg-icon-delete-selected"></i>
                            <?php echo $this->_tpl_vars['Captions']->GetMessageString('DeleteSelected'); ?>

                        </button>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['DataGrid']['ActionsPanel']['RefreshButton']): ?>
                        <a class="btn" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['Links']['Refresh'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
                            <i class="pg-icon-page-refresh"></i>
                            <?php echo $this->_tpl_vars['Captions']->GetMessageString('Refresh'); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($this->_tpl_vars['DataGrid']['AllowQuickFilter']): ?>
            <div class="btn-toolbar pull-right">
                <div class="btn-group">
                    <div class="input-append" style="float: left; margin-bottom: 0;">
                        <input placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('QuickSearch'); ?>
" type="text" size="16" class="quick-filter-text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['DataGrid']['QuickFilter']['Value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><button type="button" class="btn quick-filter-go"><i class="pg-icon-quick-find"></i></button><button type="button" class="btn quick-filter-reset"><i class="pg-icon-filter-reset"></i></button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>

    <tr class="addition-block messages hide">
        <td colspan="<?php echo $this->_tpl_vars['DataGrid']['ColumnCount']; ?>
">
            <?php if ($this->_tpl_vars['DataGrid']['ErrorMessage']): ?>
            <div class="alert alert-error">
                <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
                <?php echo $this->_tpl_vars['DataGrid']['ErrorMessage']; ?>

            </div>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['DataGrid']['GridMessage']): ?>
                <div class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
                    <?php echo $this->_tpl_vars['DataGrid']['GridMessage']; ?>

                </div>
            <?php endif; ?>

        </td>
    </tr>

    <tr class="header">
    <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
        <th class="row-selection"><input type="checkbox"></th>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
        <th>
            <a class="expand-all-details collapsed" href="#">
                <i class="toggle-detail-icon"></i>
            </a>
        </th>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
        <th>#</th>
    <?php endif; ?>
        
    <!-- <Grid Head Columns> -->
    <?php $_from = $this->_tpl_vars['DataGrid']['Bands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Band']):
?>
        <?php if ($this->_tpl_vars['Band']['ConsolidateHeader'] && $this->_tpl_vars['Band']['ColumnCount'] > 0): ?>
            <th colspan="<?php echo $this->_tpl_vars['Band']['ColumnCount']; ?>
">
                <?php echo $this->_tpl_vars['Band']['Caption']; ?>

            </th>
        <?php else: ?>
            <?php $_from = $this->_tpl_vars['Band']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
                <th class="<?php echo $this->_tpl_vars['Column']['Classes']; ?>
"
                    <?php echo $this->_tpl_vars['Column']['Attributes']; ?>

                    <?php $this->_tag_stack[] = array('style_block', array()); $_block_repeat=true;smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['Column']['Styles']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_style_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
                    data-sort-url="<?php echo ((is_array($_tmp=$this->_tpl_vars['Column']['SortUrl'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"
                    data-field-caption="<?php echo $this->_tpl_vars['Column']['Caption']; ?>
"
                    data-comment="<?php echo $this->_tpl_vars['Column']['Comment']; ?>
">
                    <i class="additional-info-icon"></i>
                    <span <?php if ($this->_tpl_vars['Column']['Comment']): ?>class="commented"<?php endif; ?>><?php echo $this->_tpl_vars['Column']['Caption']; ?>
</span>
                    <i class="sort-icon"></i>
                </th>
            <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </tr>

    <?php if ($this->_tpl_vars['DataGrid']['AllowFilterRow'] && count ( $this->_tpl_vars['DataGrid']['FilterRow']['Columns'] ) > 0): ?>
    <tr class="addition-block search-line"  dir="ltr">
        <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
            <td></td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
            <td></td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
            <td></td>
        <?php endif; ?>

        <?php $_from = $this->_tpl_vars['DataGrid']['FilterRow']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['SearchColumn']):
?>
            <?php if ($this->_tpl_vars['SearchColumn']['ResetButtonPlacement']): ?>
            <td>
                <div style="text-align: <?php echo $this->_tpl_vars['SearchColumn']['ResetButtonAlignment']; ?>
">
                    <a href="#" class="reset-filter-row" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('ResetFilterRow'); ?>
"><i  class="pg-icon-filter-builder-reset"></i></a>
                </div>
            </td>
            <?php else: ?>
            <td class="column-filter">
                <?php if ($this->_tpl_vars['SearchColumn']): ?>
                <table style="padding: 0;">
                    <tr>
                        <td style="padding: 0;">
                            <div style="white-space: nowrap; margin: 0;" class="input-append btn-group filter-control" data-field-name="<?php echo $this->_tpl_vars['SearchColumn']['FieldName']; ?>
" data-operator="<?php echo $this->_tpl_vars['SearchColumn']['CurrentOperator']['Name']; ?>
">
                                <input type="text" class="input" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['SearchColumn']['Value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php echo $this->_tpl_vars['SearchColumn']['Attributes']; ?>
>
                            </div>
                        </td>

                        <td style="padding: 0; overflow: visible;">
                            <div class="btn-group">
                            <a style="white-space: nowrap; " class="btn dropdown-toggle operator-dropdown" data-toggle="dropdown" href="#">
                                <i class="<?php echo $this->_tpl_vars['SearchColumn']['CurrentOperator']['ImageClass']; ?>
"></i>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu pull-right operator-menu">
                                <?php $_from = $this->_tpl_vars['SearchColumn']['Operators']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Operator']):
?>
                                    <li><a href="#" data-operator="<?php echo $this->_tpl_vars['Operator']['Name']; ?>
">
                                        <i class="<?php echo $this->_tpl_vars['Operator']['ImageClass']; ?>
"></i>
                                        <?php echo $this->_tpl_vars['Operator']['Caption']; ?>
</a>
                                    </li>
                                <?php endforeach; endif; unset($_from); ?>
                            </ul>
                            </div>
                        </td>
                    </tr>
                </table>
                <?php endif; ?>
            </td>
            <?php endif; ?>

        <?php endforeach; endif; unset($_from); ?>
    </tr>
    <?php endif; ?>
</thead>

<tbody>
	<tr class="new-record-row" style="display: none;" data-new-row="false">

        <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
            <td data-column-name="sm_multi_delete_column"></td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
            <td class="details">
                <a class="expand-details collapsed" href="#"><i class="toggle-detail-icon"></i></a>
            </td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
            <td class="line-number"></td>
        <?php endif; ?>

        <?php $_from = $this->_tpl_vars['DataGrid']['Bands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Band']):
?>
            <?php $_from = $this->_tpl_vars['Band']['Columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Column']):
?>
                <td data-column-name="<?php echo $this->_tpl_vars['Column']['Name']; ?>
"></td>
            <?php endforeach; endif; unset($_from); ?>
        <?php endforeach; endif; unset($_from); ?>
    </tr>


    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['SingleRowTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <tr class="empty-grid<?php if (count ( $this->_tpl_vars['DataGrid']['Rows'] ) > 0): ?> hide<?php endif; ?>">
        <td colspan="<?php echo $this->_tpl_vars['DataGrid']['ColumnCount']; ?>
" class="empty-grid">
            <?php echo $this->_tpl_vars['DataGrid']['EmptyGridMessage']; ?>

        </td>
    </tr>

</tbody>

<tfoot>
    <?php if ($this->_tpl_vars['DataGrid']['Totals']): ?>
    <tr class="data-summary">
        <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
            <td></td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
            <td></td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
            <td></td>
        <?php endif; ?>

        <?php $_from = $this->_tpl_vars['DataGrid']['Totals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Total']):
?>
            <td><?php echo $this->_tpl_vars['Total']['Value']; ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
    </tr>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['DataGrid']['FilterBuilder']): ?>
    <tr>
        <td colspan="<?php echo $this->_tpl_vars['DataGrid']['ColumnCount']; ?>
" class="addition-block filter-builder-row">
            <?php if ($this->_tpl_vars['IsActiveFilterEmpty']): ?>
                <i class="pg-icon-filter-new"></i>
                <a class="create-filter" href="#">
                <?php echo $this->_tpl_vars['Captions']->GetMessageString('CreateFilter'); ?>

            </a>
            <?php else: ?>
                <i class="pg-icon-filter"></i>
                <a class="edit-filter" href="#">
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['ActiveFilterBuilderAsString'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>

                </a>

                <i class="pg-icon-filter-builder-reset"></i>
                <a class="reset-filter" href="#">
                    <?php echo $this->_tpl_vars['Captions']->GetMessageString('ResetFilter'); ?>

                </a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
</tfoot>

</table>

<script type="text/javascript">

    <?php if ($this->_tpl_vars['AdvancedSearchControl']): ?>
    <?php echo '
            require([\'pgui.text_highlight\'], function(textHighlight) {
    '; ?>

    <?php $_from = $this->_tpl_vars['AdvancedSearchControl']->GetHighlightedFields(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['HighlightFields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['HighlightFields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['HighlightFieldName']):
        $this->_foreach['HighlightFields']['iteration']++;
?>
        textHighlight.HighlightTextInGrid(
                '#<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
', '<?php echo $this->_tpl_vars['HighlightFieldName']; ?>
',
                <?php echo $this->_tpl_vars['TextsForHighlight'][($this->_foreach['HighlightFields']['iteration']-1)]; ?>
,
                '<?php echo $this->_tpl_vars['HighlightOptions'][($this->_foreach['HighlightFields']['iteration']-1)]; ?>
');
    <?php endforeach; endif; unset($_from); ?>
    <?php echo '
    });
    '; ?>

    <?php endif; ?>


    <?php echo '
    $(function() {
        var gridId = \''; ?>
<?php echo $this->_tpl_vars['DataGrid']['Id']; ?>
<?php echo '\';
        var $gridContainer = $(\'#\' + gridId);

        require([\'pgui.grid\', \'pgui.advanced_filter\'], function(pggrid, fb) {

            var grid = new pggrid.Grid($gridContainer);

            grid.onConfigureFilterBuilder(function(filterBuilder) {
            '; ?>

                <?php $_from = $this->_tpl_vars['FilterBuilder']['Fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['FilterBuilderField']):
?>
                filterBuilder.addField(
                        <?php echo smarty_function_jsstring(array('value' => $this->_tpl_vars['FilterBuilderField']['Name']), $this);?>
,
                        <?php echo smarty_function_jsstring(array('value' => $this->_tpl_vars['FilterBuilderField']['Caption']), $this);?>
,
                        fb.FieldType.<?php echo $this->_tpl_vars['FilterBuilderField']['Type']; ?>
,
                        fb.<?php echo $this->_tpl_vars['FilterBuilderField']['EditorClass']; ?>
,
                        <?php echo $this->_tpl_vars['FilterBuilderField']['EditorOptions']; ?>
);
                <?php endforeach; endif; unset($_from); ?>
            <?php echo ';
            });

            var activeFilterJson = '; ?>
<?php echo $this->_tpl_vars['ActiveFilterBuilderJson']; ?>
<?php echo ';
            var activeFilter = new fb.Filter();
            activeFilter.fromJson(activeFilterJson);
            grid.setFilter(activeFilter);
        });
    });
    '; ?>

</script>