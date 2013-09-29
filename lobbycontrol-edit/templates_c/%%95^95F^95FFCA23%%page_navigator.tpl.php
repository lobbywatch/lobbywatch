<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'n', 'list/page_navigator.tpl', 8, false),array('function', 'eval', 'list/page_navigator.tpl', 38, false),array('modifier', 'escapeurl', 'list/page_navigator.tpl', 8, false),array('modifier', 'escape', 'list/page_navigator.tpl', 66, false),)), $this); ?>
<div class="pgui-pagination" data-total-record-count="<?php echo $this->_tpl_vars['PageNavigator']->GetRowCount(); ?>
">

    <div class="pagination">
        <ul><?php $_from = $this->_tpl_vars['PageNavigatorPages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['PageNavigatorPage']):
?><?php echo ''; ?><?php if ($this->_tpl_vars['PageNavigatorPage']->GetPageLink()): ?><?php echo '<li '; ?><?php if ($this->_tpl_vars['PageNavigatorPage']->IsCurrent()): ?><?php echo 'class="active"'; ?><?php endif; ?><?php echo '><a'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' href="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['PageNavigatorPage']->GetPageLink())) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?><?php echo '"'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' title="'; ?><?php echo $this->_tpl_vars['PageNavigatorPage']->GetHint(); ?><?php echo '"'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' '; ?><?php if ($this->_tpl_vars['PageNavigatorPage']->HasShortCut()): ?><?php echo 'pgui-shortcut="'; ?><?php echo $this->_tpl_vars['PageNavigatorPage']->GetShortCut(); ?><?php echo '"'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['PageNavigatorPage']->GetPageCaption(); ?><?php echo '</a></li>'; ?><?php else: ?><?php echo '<li class="pagination-spacer"><a'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' href="#"'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' title="'; ?><?php echo $this->_tpl_vars['PageNavigatorPage']->GetHint(); ?><?php echo '"'; ?><?php echo smarty_function_n(array(), $this);?><?php echo ' onclick="return false;">'; ?><?php echo $this->_tpl_vars['PageNavigatorPage']->GetPageCaption(); ?><?php echo '</a></li>'; ?><?php endif; ?><?php echo ''; ?>
<?php endforeach; endif; unset($_from); ?></ul>
        <ul><li><a class="define-page-size-button" href="#"><?php echo $this->_tpl_vars['Captions']->GetMessageString('DefaultPageSize'); ?>
</a></ul>
    </div>

    <div class="modal hide pagination-size">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
            <h3><?php echo $this->_tpl_vars['Captions']->GetMessageString('ChangePageSizeTitle'); ?>
</h3>
        </div>

        <div class="modal-body">
            <?php $this->assign('row_count', $this->_tpl_vars['PageNavigator']->GetRowCount()); ?>
            <p><?php echo smarty_function_eval(array('var' => $this->_tpl_vars['Captions']->GetMessageString('ChangePageSizeText')), $this);?>
</p>

            <table class="table table-bordered">
                <tr>
                    <th><?php echo $this->_tpl_vars['Captions']->GetMessageString('RecordsPerPage'); ?>
</th>
                    <th><?php echo $this->_tpl_vars['Captions']->GetMessageString('TotalPages'); ?>
</th>
                </tr>
                <?php $_from = $this->_tpl_vars['PageNavigator']->GetRecordsPerPageValues(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
                    <tr>
                        <td>
                            <div class="controls">
                                <label class="radio">
                                    <input type="radio" value="<?php echo $this->_tpl_vars['name']; ?>
" name="recperpage">
                                    <?php echo $this->_tpl_vars['value']; ?>

                                </label>
                            </div>
                        </td>
                        <td><?php echo $this->_tpl_vars['PageNavigator']->GetPageCountForPageSize($this->_tpl_vars['name']); ?>
</td>
                    </tr>
                <?php endforeach; endif; unset($_from); ?>
                <tr>
                    <td>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" value="custom" name="recperpage" checked="checked">
                                <?php echo $this->_tpl_vars['Captions']->GetMessageString('UseCustomPageSize'); ?>

                            </label>
                            <label class="text">
                                <input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PageNavigator']->GetRowsPerPage())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" class="input-medium pgui-custom-page-size">
                            </label>
                        </div>

                    </td>
                    <td><span class="custom_page_size_page_count"><?php echo $this->_tpl_vars['PageNavigator']->GetPageCount(); ?>
</span></td>
                </tr>
            </table>

        </div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Cancel'); ?>
</a>
            <a href="#" class="save-changes-button btn btn-primary"><?php echo $this->_tpl_vars['Captions']->GetMessageString('SaveChanges'); ?>
</a>
        </div>
    </div>


</div>