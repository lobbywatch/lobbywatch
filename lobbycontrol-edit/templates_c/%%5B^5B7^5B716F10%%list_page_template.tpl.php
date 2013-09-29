<?php ob_start(); ?><?php $this->_smarty_vars['capture']['SideBar'] = ob_get_contents(); ob_end_clean(); ?>

<?php $this->assign('JavaScriptMain', "pgui.list-page-main"); ?>

<?php $this->assign('HideSideBarByDefault', $this->_tpl_vars['HideSideBarByDefault']); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['LayoutTemplateName'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>