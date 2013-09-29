<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escapeurl', 'list/single_row.tpl', 10, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['DataGrid']['Rows'] ) > 0): ?>

    <?php $_from = $this->_tpl_vars['DataGrid']['Rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['RowsGrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['RowsGrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Row']):
        $this->_foreach['RowsGrid']['iteration']++;
?>

    <tr class="pg-row" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
        <?php if ($this->_tpl_vars['DataGrid']['AllowDeleteSelected']): ?>
            <td class="row-selection" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
">
                <input type="checkbox" name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
" >
                <?php $_from = $this->_tpl_vars['Row']['PrimaryKeys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['CPkValues'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['CPkValues']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['PkValue']):
        $this->_foreach['CPkValues']['iteration']++;
?>
                    <input type="hidden" name="rec<?php echo ($this->_foreach['RowsGrid']['iteration']-1); ?>
_pk<?php echo ($this->_foreach['CPkValues']['iteration']-1); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PkValue'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
" />
                <?php endforeach; endif; unset($_from); ?>
            </td>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['DataGrid']['HasDetails']): ?>
            <td dir="ltr" class="details" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
; width: 40px;">
                <div class="btn-group detail-quick-access" style="display: inline-block;" >
                <a class="expand-details collapsed"
                   style="display: inline-block;"
                   data-info="<?php echo $this->_tpl_vars['Row']['Details']['JSON']; ?>
"
                   href="#"><i class="toggle-detail-icon"></i>
                </a><a data-toggle="dropdown" href="#"><i class="pg-icon-detail-additional"></i></a><ul class="dropdown-menu">
                        <?php $_from = $this->_tpl_vars['Row']['Details']['Items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Detail']):
?>
                            <li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Detail']['SeperatedPageLink'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
"><?php echo $this->_tpl_vars['Detail']['caption']; ?>
</a></li>
                        <?php endforeach; endif; unset($_from); ?>
                    </ul>
                </div>
            </td>
        <?php endif; ?>


        <?php if ($this->_tpl_vars['DataGrid']['ShowLineNumbers']): ?>
            <td class="line-number" style="<?php echo $this->_tpl_vars['Row']['Style']; ?>
"><?php echo $this->_tpl_vars['Row']['LineNumber']; ?>
</td>
        <?php endif; ?>

        <?php $_from = $this->_tpl_vars['Row']['DataCells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['Cells'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['Cells']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['Cell']):
        $this->_foreach['Cells']['iteration']++;
?>
            <td data-column-name="<?php echo $this->_tpl_vars['Cell']['ColumnName']; ?>
" style="<?php echo $this->_tpl_vars['Cell']['Style']; ?>
" class="<?php echo $this->_tpl_vars['Cell']['Classes']; ?>
"><?php echo $this->_tpl_vars['Cell']['Data']; ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
    </tr>

    <?php endforeach; endif; unset($_from); ?>

<?php endif; ?>
