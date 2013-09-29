<!DOCTYPE html>
<html dir="<?php echo $this->_tpl_vars['App']['Direction']; ?>
">
<head>
    <?php if ($this->_tpl_vars['App']['ContentEncoding']): ?>
        <meta charset="UTF-8">
    <?php endif; ?>
    <?php echo $this->_tpl_vars['App']['CustomHtmlHeadSection']; ?>

    <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10">
    <?php if ($this->_tpl_vars['App']): ?>
        <title><?php echo $this->_tpl_vars['App']['PageCaption']; ?>
</title>
    <?php else: ?>
        <title>Error</title>
    <?php endif; ?>

    <link rel="stylesheet" type="text/css" href="components/css/main.css" />
    <link rel="stylesheet" type="text/css" href="components/css/user.css" />

    <script src="components/js/jquery/jquery.min.js"></script>
    <script src="components/js/libs/amplify.store.js"></script>
    <script src="components/js/bootstrap/bootstrap.js"></script>

    <script type="text/javascript" src="components/js/require-config.js"></script>
    <?php if ($this->_tpl_vars['JavaScriptMain']): ?>
        <script type="text/javascript" data-main="<?php echo $this->_tpl_vars['JavaScriptMain']; ?>
" src="components/js/require.js"></script>
    <?php else: ?>
        <script type="text/javascript" src="components/js/require.js"></script>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['App']): ?>
    <script><?php echo $this->_tpl_vars['App']['ValidationScripts']; ?>
</script>
    <?php endif; ?>
    <script><?php echo $this->_tpl_vars['Scripts']; ?>
</script>
</head>
<body>

<div class="navbar" id="navbar">
    <div class="navbar-inner">
        <div class="container">
            <?php if ($this->_tpl_vars['App']): ?>
            <div class="pull-left"><?php echo $this->_tpl_vars['App']['Header']; ?>
</div>
            <?php endif; ?>

        <?php if ($this->_tpl_vars['Authentication']['Enabled']): ?>
            <ul class="nav pull-right">
                <li class="active">
                    <a href="#" onclick="return false;" style="cursor: default;">
                        <i class="pg-icon-user"></i>
                        <?php echo $this->_tpl_vars['Authentication']['CurrentUser']['Name']; ?>
</a>
                </li>
                <?php if ($this->_tpl_vars['Authentication']['LoggedIn']): ?>
                    <?php if ($this->_tpl_vars['Authentication']['CanChangeOwnPassword']): ?>
                    <li><a id="self-change-password" href="#" title="<?php echo $this->_tpl_vars['Captions']->GetMessageString('ChangePassword'); ?>
">
                            <i class="pg-icon-password-change"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li><a href="login.php?operation=logout"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Logout'); ?>
</a></li>
                    <?php else: ?>
                    <li><a href="login.php"><?php echo $this->_tpl_vars['Captions']->GetMessageString('Login'); ?>
</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php if (! isset ( $this->_tpl_vars['HideSideBarByDefault'] )): ?>
    <?php $this->assign('HideSideBarByDefault', false); ?>
<?php endif; ?>


<div class="container-fluid">
    <div class="row-fluid">
        <?php if ($this->_tpl_vars['SideBar']): ?>
        <div class="span3 expanded" id="side-bar">

            <div class="sidebar-nav-fixed">
                <a href="#" class="close" style="margin: 4px 4px 0 0"><i class="icon-chevron-left"></i></a>
                <div class="content">
                    <?php echo $this->_tpl_vars['SideBar']; ?>

                </div>
            </div>

            <script><?php echo '
            $(\'.sidebar-nav-fixed\').css(\'top\',
                Math.max(0, $(\'#navbar\').outerHeight() - $(window).scrollTop())
            );
            $(\'#navbar img\').load(function() {
                $(\'.sidebar-nav-fixed\').css(\'top\',
                    Math.max(0, $(\'#navbar\').outerHeight() - $(window).scrollTop())
                );
            });
            $(window).scroll(function() {
                $(\'.sidebar-nav-fixed\').css(\'top\',
                        Math.max(0, $(\'#navbar\').outerHeight() - $(window).scrollTop())
                );
            });
            //$(\'#content\').css(\'top\', $(\'.navbar-fixed-top\').height() + 10);
            //$(\'#side-bar\').css(\'top\', $(\'.navbar-fixed-top\').height() - 10);
            '; ?>
</script>

        </div>
        <?php endif; ?>
        <div class="<?php if ($this->_tpl_vars['SideBar']): ?>span9<?php else: ?>span12<?php endif; ?>" id="content-block">
            <?php if ($this->_tpl_vars['SideBar']): ?>
            <script><?php echo '
            var sideBarContainer = $(\'#side-bar\');
            var sidebar = $(\'#side-bar .sidebar-nav-fixed\');
            var toggleButton = sidebar.find(\'a.close\');
            var toggleButtonIcon = toggleButton.children(\'i\');

            function hideSideBar() {
                sideBarContainer.removeClass(\'expanded\');
                sidebar.children(\'.content\').hide();
                sideBarContainer.width(20);
                toggleButtonIcon.removeClass(\'icon-chevron-left\');
                toggleButtonIcon.addClass(\'icon-chevron-right\');
                $(\'#content-block\').css(\'left\', 0);
                $(\'#content-block\').addClass(\'span10\');
                $(\'#content-block\').removeClass(\'span9\');
            }

            function showSideBar() {
                sideBarContainer.addClass(\'expanded\');
                sidebar.children(\'.content\').show();
                sideBarContainer.width(240);
                toggleButtonIcon.addClass(\'icon-chevron-left\');
                toggleButtonIcon.removeClass(\'icon-chevron-right\');
                $(\'#content-block\').css(\'left\', 240);
                $(\'#content-block\').removeClass(\'span10\');
                $(\'#content-block\').addClass(\'span9\');
            }

            '; ?>

            <?php if ($this->_tpl_vars['HideSideBarByDefault']): ?>
                hideSideBar();
            <?php else: ?>
                <?php echo '
                if (amplify.store(\'side-bar-collapsed\')) {
                    hideSideBar();
                }
                '; ?>

            <?php endif; ?>
            <?php echo '


            toggleButton.click(function(e) {
                e.preventDefault();
                if (sideBarContainer.hasClass(\'expanded\')) {
                    hideSideBar();
                    amplify.store(\'side-bar-collapsed\', true);
                }
                else {
                    showSideBar();
                    amplify.store(\'side-bar-collapsed\', false);
                }
            });
            '; ?>
</script>
            <?php endif; ?>
            <?php echo $this->_tpl_vars['ContentBlock']; ?>

            <?php echo $this->_tpl_vars['Variables']; ?>

            <hr>
            <footer><p><?php echo $this->_tpl_vars['Footer']; ?>
</p></footer>
        </div>


    </div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/change_password_dialog.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="components/js/pg.user_management_api.js"></script>
<script type="text/javascript" src="components/js/pgui.change_password_dialog.js"></script>
<script type="text/javascript" src="components/js/pgui.password_dialog_utils.js"></script>
<script type="text/javascript" src="components/js/pgui.self_change_password.js"></script>

</body>
</html>