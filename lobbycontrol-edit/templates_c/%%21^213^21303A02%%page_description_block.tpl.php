<?php if ($this->_tpl_vars['Description']): ?>
<div class="well description">
    <a href="#" class="close" onclick="$(this).closest('.well').hide(); return false;"><i class="icon-remove"></i></a>
    <?php echo $this->_tpl_vars['Description']; ?>

</div>
<?php endif; ?>