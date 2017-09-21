<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
    <!-- SMS -->
	<title>Central@visos</title>
	<?php
		echo $this->Html->meta('icon');

		//echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
                
                // bootstrap & fontawesome
                echo $this->Html->css('bootstrap');
                echo $this->Html->css('font-awesome');
                
                // text fonts
                echo $this->Html->css('ace-fonts');
                
                // ace styles
                echo $this->Html->css('ace');
                echo $this->Html->css('ace-rtl');
                //echo $this->Html->css('ace-skins');
                
                // ace settings handler
                //echo $this->Html->script('ace-extra');
	?>
</head>
<body class="login-layout light-login">
        <div class="main-container">
                <div class="main-content">
                        <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                        <div class="login-container">
                                                <div class="center">
                                                        <h1>
                                                                <!--<i class="ace-icon fa fa-commenting blue"></i>
                                                                <span class="blue" id="id-text2">Central@visos</span>-->
                                                        </h1>
                                                </div>

                                                <div class="space-6"></div>

                                                <?php echo $this->fetch('content'); ?>

                                        </div>
                                </div><!-- /.col -->
                        </div><!-- /.row -->
                </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script type="text/javascript">
                window.jQuery || document.write("<script src='<?php echo $this->base; ?>/js/jquery.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <script type="text/javascript">
                if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo $this->base; ?>/js/jquery.mobile.custom.js'>"+"<"+"/script>");
        </script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
                jQuery(function($) {
                 $(document).on('click', '.toolbar a[data-target]', function(e) {
                        e.preventDefault();
                        var target = $(this).data('target');
                        $('.widget-box.visible').removeClass('visible');//hide others
                        $(target).addClass('visible');//show target
                 });
                });

        </script>
</body>
</html>
