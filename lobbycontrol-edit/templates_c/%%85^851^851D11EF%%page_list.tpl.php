<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsstring', 'page_list.tpl', 16, false),array('modifier', 'escapeurl', 'page_list.tpl', 24, false),)), $this); ?>
<div class="sidebar-nav">
    <ul class="nav nav-list pg-page-list">

        <li class="nav-header"><?php echo $this->_tpl_vars['Captions']->GetMessageString('PageList'); ?>
</li>

        <?php $_from = $this->_tpl_vars['List']['Pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['PageListPage']):
?>
            <?php if ($this->_tpl_vars['PageListPage']['BeginNewGroup']): ?><li class="divider"></li><?php endif; ?>

            <?php if ($this->_tpl_vars['PageListPage']['IsCurrent']): ?>
                <li class="active">

                    <a href="#" title="<?php echo $this->_tpl_vars['PageListPage']['Hint']; ?>
" onclick="return false;" style="cursor: default;">
                        <i class="page-list-icon"></i>
                        <?php echo $this->_tpl_vars['PageListPage']['Caption']; ?>

                        <?php if ($this->_tpl_vars['List']['RSSLink']): ?>
                        <span class="pull-right" style="cursor: pointer;" onclick="window.location.href=<?php echo smarty_function_jsstring(array('value' => $this->_tpl_vars['List']['RSSLink']), $this);?>
;">
                            <i class="pg-icon-rss"></i>
                        </span>
                        <?php endif; ?>
                    </a>

                </li>
            <?php else: ?>
                <li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['PageListPage']['Href'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['PageListPage']['Hint']; ?>
">
                    <i class="page-list-icon"></i>
                    <?php echo $this->_tpl_vars['PageListPage']['Caption']; ?>

                </a></li>
            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>