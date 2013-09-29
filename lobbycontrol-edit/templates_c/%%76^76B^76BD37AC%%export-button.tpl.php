<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escapeurl', 'export-button.tpl', 15, false),)), $this); ?>
<div class="btn-group export-button">


    <?php if ($this->_tpl_vars['Items']['excel'] || $this->_tpl_vars['Items']['pdf'] || $this->_tpl_vars['Items']['csv'] || $this->_tpl_vars['Items']['xml'] || $this->_tpl_vars['Items']['word']): ?>
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="pg-icon-export"></i>
            <?php echo $this->_tpl_vars['Captions']->GetMessageString('Export'); ?>

        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu">
    <?php $_from = $this->_tpl_vars['Items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Name'] => $this->_tpl_vars['Item']):
?>
        <?php if ($this->_tpl_vars['Name'] != 'print_page' && $this->_tpl_vars['Name'] != 'print_all'): ?>
            <?php if ($this->_tpl_vars['Item']['BeginNewGroup']): ?><li class="divider"></li><?php endif; ?>
            <li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Item']['Href'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
                <i class="pg-icon-<?php echo $this->_tpl_vars['Item']['IconClass']; ?>
"></i>
                <?php echo $this->_tpl_vars['Item']['Caption']; ?>

            </a></li>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </ul>
    <?php endif; ?>


    <?php if ($this->_tpl_vars['Items']['print_page']): ?>
    <a class="btn" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Items']['print_page']['Href'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
        <i class="pg-icon-<?php echo $this->_tpl_vars['Items']['print_page']['IconClass']; ?>
"></i>
        <?php echo $this->_tpl_vars['Items']['print_page']['Caption']; ?>

    </a>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['Items']['print_all']): ?>
    <a class="btn" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['Items']['print_all']['Href'])) ? $this->_run_mod_handler('escapeurl', true, $_tmp) : smarty_modifier_escapeurl($_tmp)); ?>
">
        <i class="pg-icon-<?php echo $this->_tpl_vars['Items']['print_all']['IconClass']; ?>
"></i>
        <?php echo $this->_tpl_vars['Items']['print_all']['Caption']; ?>

    </a>
    <?php endif; ?>

</div>