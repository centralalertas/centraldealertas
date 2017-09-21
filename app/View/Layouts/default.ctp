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
    <!-- gest@oCarismatica -->
	<title>Central@visos</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
                
                // bootstrap & fontawesome
                echo $this->Html->css('bootstrap');
                //echo $this->Html->css('bootstrap-modal-bs3patch');
                echo $this->Html->css('font-awesome');
                
                // text fonts
                echo $this->Html->css('ace-fonts');
                
                // ace styles
                echo $this->Html->css('ace');
                echo $this->Html->css('ace-skins');
                
                // ace settings handler
                echo $this->Html->script('ace-extra');
                
                echo $this->Html->css( 'jquery.treegrid' );
                
                echo $this->Html->css( 'chosen' );
                
                echo $this->Html->css( 'colorpicker' );
                
                echo $this->Html->css( 'bootstrap-datepicker3' );
                
                echo $this->Html->css( 'jquery.datetimepicker' );
	?>
</head>
<body class="skin-3">
        
        <!--[if !IE]> -->
        <script type="text/javascript">
                window.jQuery || document.write("<script src='<?php echo $this->base; ?>/js/jquery.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <script type="text/javascript">
                if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo $this->base; ?>/js/jquery.mobile.custom.js'>"+"<"+"/script>");
        </script>
        <?php
        echo $this->Html->script( 'bootstrap' );
        
        echo $this->Html->script( 'jquery.treegrid' );
        echo $this->Html->script( 'jquery.treegrid.bootstrap2' );
        echo $this->Html->script( 'jquery.cookie' );
        
        echo $this->Html->script( 'ace/elements.scroller' );
        echo $this->Html->script( 'ace/elements.colorpicker' );
        echo $this->Html->script( 'ace/elements.fileinput' );
        echo $this->Html->script( 'ace/elements.typeahead' );
        echo $this->Html->script( 'ace/elements.spinner' );
        echo $this->Html->script( 'ace/elements.treeview' );
        echo $this->Html->script( 'ace/elements.wizard' );
        echo $this->Html->script( 'ace/elements.aside' );
        echo $this->Html->script( 'ace/ace' );
        echo $this->Html->script( 'ace/ace.ajax-content' );
        echo $this->Html->script( 'ace/ace.touch-drag' );
        echo $this->Html->script( 'ace/ace.sidebar' );
        echo $this->Html->script( 'ace/ace.sidebar-scroll-1' );
        echo $this->Html->script( 'ace/ace.submenu-hover' );
        echo $this->Html->script( 'ace/ace.widget-box' );
        echo $this->Html->script( 'ace/ace.settings' );
        echo $this->Html->script( 'ace/ace.settings-rtl' );
        echo $this->Html->script( 'ace/ace.settings-skin' );
        echo $this->Html->script( 'ace/ace.widget-on-reload' );
        echo $this->Html->script( 'ace/ace.searchbox-autocomplete' );
        
        echo $this->Html->script( 'jquery.validate' );
        echo $this->Html->script( 'additional-methods' );
        echo $this->Html->script( 'jquery.metadata' );
        
        echo $this->Html->script( 'chosen.jquery' );
        echo $this->Html->script( 'bootbox' );
        
        echo $this->Html->script( 'bootstrap-colorpicker' );
        
        echo $this->Html->script( 'jquery.maskedinput' );
        
        echo $this->Html->script( 'date-time/bootstrap-datepicker' );

        echo $this->Html->script( 'jquery.datetimepicker.full' );
        
        echo $this->Html->script( 'jquery-smshelper' );
        
        ?>
        
<!--        <script type="text/javascript">

            jQuery(function($) {
                var url_sd = "< ?php echo $this->base; ?>/mensagens/getSaldoSms";
                $.ajax({
                    type:"GET",
                    url:url_sd,
                    success:function(result){
                        $('#SaldoRestanteMes').text(result);
                    }
                });
            });

        </script>-->
    
        <!-- #section:basics/navbar.layout -->
        <div id="navbar" class="navbar navbar-default navbar-fixed-top">
                <script type="text/javascript">
                        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
                </script>

                <div class="navbar-container" id="navbar-container">
                        <!-- #section:basics/sidebar.mobile.toggle -->
                        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                                <span class="sr-only">Alternar</span>

                                <span class="icon-bar"></span>

                                <span class="icon-bar"></span>

                                <span class="icon-bar"></span>
                        </button>

                        <!-- /section:basics/sidebar.mobile.toggle -->
                        <div class="navbar-header pull-left">
                                <!-- #section:basics/navbar.layout.brand -->
                                <!--<a href="#" class="navbar-brand">
                                        <small>
                                                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                                Central@visos
                                        </small>
                                </a>-->

                                <!-- /section:basics/navbar.layout.brand -->

                                <!-- #section:basics/navbar.toggle -->

                                <!-- /section:basics/navbar.toggle -->
                        </div>

                        <!-- #section:basics/navbar.dropdown -->
                        <div class="navbar-header pull-right">
                            <?php if(AuthComponent::user('Cliente.logo') != '') { ?>
                            <!--<img src="<?php echo $this->base; ?>/usuarios/getLogo" class="msg-photo" height="45" />-->
                            <?php } ?>
<!--                                        <h5 class="white">
                                            Saldo SMS: <span id="SaldoRestanteMes"></span>
                                        </h5>-->
                        </div>

                        <!-- /section:basics/navbar.dropdown -->
                </div><!-- /.navbar-container -->
        </div>

        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
                <script type="text/javascript">
                        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
                </script>

                <!-- #section:basics/sidebar -->
                <div id="sidebar" class="sidebar sidebar-fixed responsive">
                        <script type="text/javascript">
                                try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
                        </script>

<!--                        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                                        <button class="btn btn-success">
                                                <i class="ace-icon fa fa-signal"></i>
                                        </button>

                                        <button class="btn btn-info">
                                                <i class="ace-icon fa fa-pencil"></i>
                                        </button>

                                         #section:basics/sidebar.layout.shortcuts 
                                        <button class="btn btn-warning">
                                                <i class="ace-icon fa fa-users"></i>
                                        </button>

                                        <button class="btn btn-danger">
                                                <i class="ace-icon fa fa-cogs"></i>
                                        </button>

                                         /section:basics/sidebar.layout.shortcuts 
                                </div>

                                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                                        <span class="btn btn-success"></span>

                                        <span class="btn btn-info"></span>

                                        <span class="btn btn-warning"></span>

                                        <span class="btn btn-danger"></span>
                                </div>
                        </div> /.sidebar-shortcuts -->

                        <ul class="nav nav-list">
                                <li class="<?php if ($this->params['controller'] == 'dashboard'){echo 'active';}; ?>">
                                        <a href="<?php echo $this->base; ?>/dashboard">
                                                <i class="menu-icon fa fa-pie-chart"></i>
                                                <span class="menu-text"> Resumo </span>
                                        </a>

                                        <b class="arrow"></b>
                                </li>
                                <li class="<?php if ($this->params['controller'] == 'grupos'){echo 'active';}; ?>">
                                        <a href="<?php echo $this->base; ?>/grupos">
                                                <i class="menu-icon fa fa-list"></i>
                                                <span class="menu-text"> Grupos </span>
                                        </a>

                                        <b class="arrow"></b>
                                </li>
                                <li class="<?php if ($this->params['controller'] == 'contatos'){echo 'active open';}; ?>">
                                        <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-book"></i>
                                                <span class="menu-text"> Contatos </span>

                                                <b class="arrow fa fa-angle-down"></b>
                                        </a>

                                        <b class="arrow"></b>

                                        <ul class="submenu">
                                                <li class="<?php if ($this->params['controller'] == 'contatos' && $this->params['action'] == 'index'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/contatos">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Geral (Whatsapp)
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li >
                                                        <!--<a href="<?php echo $this->base; ?>/contatos">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Facebook
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li >
                                                        <!--<a href="<?php echo $this->base; ?>/contatos">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Instagram
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li >
                                                        <!--<a href="<?php echo $this->base; ?>/contatos">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Telegran
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li >
                                                        <!--<a href="<?php echo $this->base; ?>/contatos">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Twitter
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>


                                                <li class="<?php if ($this->params['controller'] == 'contatos' && $this->params['action'] == 'importar'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/contatos/importar">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Importar
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                        </ul>
                                </li>


                                <li class="<?php if ($this->params['controller'] == 'mensagens' || $this->params['controller'] == 'categorias' || $this->params['controller'] == 'aniversariantes'){echo 'active open';}; ?>">
                                        <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-send"></i>
                                                <span class="menu-text"> Mensagens </span>

                                                <b class="arrow fa fa-angle-down"></b>
                                        </a>

                                        <b class="arrow"></b>

                                        <ul class="submenu">
                                                <li class="<?php if ($this->params['controller'] == 'mensagens'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/mensagens">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Enviar Mensagens
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>

                                                <li class="<?php if ($this->params['controller'] == 'categorias'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/categorias">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Assuntos
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                
                                                <li class="<?php if ($this->params['controller'] == 'aniversariantes'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/aniversariantes">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Aniversariantes
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li >
                                                        <!--<a href="<?php echo $this->base; ?>/mensagens">-->
                                                        <a data-toggle="modal" data-target="#myModal">                                                        
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Compromissos 
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                        </ul>
                                </li>
                                <li class="">
                                        <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-send"></i>
                                                <span class="menu-text"> Noticias </span>

                                                <b class="arrow fa fa-angle-down"></b>
                                        </a>

                                        <b class="arrow"></b>

                                        <ul class="submenu">
                                                <li class="">
                                                        <!--<a href="<?php echo $this->base; ?>/mensagens">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Cadastro
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                                <li class="">
                                                        <!--<a href="<?php echo $this->base; ?>/mensagens">-->
                                                        <a data-toggle="modal" data-target="#myModal">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Categoria
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>
                                        </ul>
                                </li>
                                <li class="">
                                        <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-send"></i>
                                                <span class="menu-text"> Relat&oacute;rios </span>

                                                <b class="arrow fa fa-angle-down"></b>
                                        </a>

                                        <b class="arrow"></b>

                                        
                                </li>
<?php
                                if(AuthComponent::user('role') != 'subordinado') {
                                ?>
                                <li class="<?php if ($this->params['controller'] == 'usuarios' && $this->params['action'] != 'perfil'){echo 'active';}; ?>">
                                        <a href="<?php echo $this->base; ?>/usuarios">
                                                <i class="menu-icon fa fa-users"></i>
                                                <span class="menu-text"> Usu&aacute;rios </span>
                                        </a>

                                        <b class="arrow"></b>
                                </li>
                                <?php
                                }
                                ?>




                                <li class="<?php if ($this->params['controller'] == 'usuarios' && $this->params['action'] == 'perfil'){echo 'active';}; ?>">
                                        <a href="<?php echo $this->base; ?>/usuarios/perfil">
                                                <i class="menu-icon fa fa-user"></i>
                                                <span class="menu-text"> Perfil </span>
                                        </a>

                                        <b class="arrow"></b>
                                </li>

                                <?php
                                if(AuthComponent::user('role') == 'admin') {
                                ?>

                                <li class="<?php if ($this->params['controller'] == 'configuracoes' || $this->params['controller'] == 'clientes' ){echo 'active open';}; ?>">
                                        <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-cogs"></i>
                                                <span class="menu-text"> Administra&ccedil;&atilde;o</span>

                                                <b class="arrow fa fa-angle-down"></b>
                                        </a>

                                        <b class="arrow"></b>

                                        <ul class="submenu">
                                                <li class="<?php if ($this->params['controller'] == 'clientes'){echo 'active';}; ?>">
                                                        <a href="<?php echo $this->base; ?>/clientes">
                                                                <i class="menu-icon fa fa-caret-right"></i>
                                                                Clientes
                                                        </a>

                                                        <b class="arrow"></b>
                                                </li>

                                        </ul>
                                </li>
                                <?php
                                } ?>


                                <li class="">
                                        <a href="<?php echo $this->base; ?>/usuarios/sair">
                                                <i class="menu-icon fa fa-power-off"></i>
                                                <span class="menu-text"> Sair </span>
                                        </a>

                                        <b class="arrow"></b>
                                </li>
                        </ul><!-- /.nav-list -->

                        <!-- #section:basics/sidebar.layout.minimize -->
                        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                                <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                        </div>

                        <!-- /section:basics/sidebar.layout.minimize -->
                        <script type="text/javascript">
                                try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                        </script>
                </div>

                <!-- /section:basics/sidebar -->
                <div class="main-content">
                        <div class="main-content-inner">
                                <!-- #section:basics/content.breadcrumbs -->
                                <div class="breadcrumbs" id="breadcrumbs">
                                        <script type="text/javascript">
                                                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                                        </script>

                                        <ul class="breadcrumb">
                                                <h4>
                                                    &nbsp;&nbsp;
                                                    <?php if ($this->params['controller'] == 'dashboard'){ ?>
                                                        <i class="menu-icon fa fa-pie-chart blue"></i>
                                                        &nbsp; Resumo
                                                    <?php } ?>
                                                        
                                                    <?php if ($this->params['controller'] == 'grupos'){ ?>
                                                        <i class="menu-icon fa fa-list purple"></i>
                                                        &nbsp; Grupos
                                                    <?php } ?>
                                                        
                                                    <?php 
                                                        if ($this->params['controller'] == 'contatos'){ 
                                                            if ($this->params['action'] == 'importar'){ 
                                                    ?>
                                                        <i class="menu-icon fa fa-download green"></i>
                                                        &nbsp; Importar Contatos
                                                    <?php
                                                            } else {
                                                    ?>
                                                        <i class="menu-icon fa fa-book green"></i>
                                                        &nbsp; Contatos
                                                    <?php } } ?>
                                                        
                                                    <?php if ($this->params['controller'] == 'mensagens'){ ?>
                                                        <i class="menu-icon fa fa-send-o pink2"></i>
                                                        &nbsp; Mensagens
                                                    <?php } ?>
                                                        
                                                    <?php if ($this->params['controller'] == 'categorias'){ ?>
                                                        <i class="menu-icon fa fa-list-ol orange"></i>
                                                        &nbsp; Assuntos
                                                    <?php } ?>
                                                        
                                                    <?php if ($this->params['controller'] == 'aniversariantes'){ ?>
                                                        <i class="menu-icon fa fa-gift pink"></i>
                                                        &nbsp; Aniversariantes
                                                    <?php } ?>
                                                        
                                                    <?php if ($this->params['controller'] == 'clientes'){ ?>
                                                        <i class="menu-icon fa fa-globe red"></i>
                                                        &nbsp; Clientes
                                                    <?php } ?>
                                                        
                                                    <?php 
                                                        if ($this->params['controller'] == 'usuarios'){ 
                                                            if ($this->params['action'] == 'perfil'){ 
                                                    ?>
                                                        <i class="menu-icon fa fa-user red2"></i>
                                                        &nbsp; Perfil
                                                    <?php
                                                            } else {
                                                    ?>
                                                        <i class="menu-icon fa fa-group grey"></i>
                                                        &nbsp; Usu&aacute;rios
                                                    <?php } } ?>
                                                </h4>

<!--                                                <li>
                                                        <a href="#">Other Pages</a>
                                                </li>
                                                <li class="active">Blank Page</li>-->
                                        </ul><!-- /.breadcrumb -->

                                        <!-- #section:basics/content.searchbox -->
<!--                                        <div class="nav-search" id="nav-search">
                                                <form class="form-search">
                                                        <span class="input-icon">
                                                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                                                        </span>
                                                </form>
                                        </div> /.nav-search -->

                                        <!-- /section:basics/content.searchbox -->
                                </div>

                                <!-- /section:basics/content.breadcrumbs -->
                                <div class="page-content">
                                        <!-- #section:settings.box -->
<!--                                        <div class="ace-settings-container" id="ace-settings-container">
                                                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                                                        <i class="ace-icon fa fa-cog bigger-130"></i>
                                                </div>

                                                <div class="ace-settings-box clearfix" id="ace-settings-box">
                                                        <div class="pull-left width-50">
                                                                 #section:settings.skins 
                                                                <div class="ace-settings-item">
                                                                        <div class="pull-left">
                                                                                <select id="skin-colorpicker" class="hide">
                                                                                        <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                                                                        <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                                                                        <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                                                                        <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                                                                </select>
                                                                        </div>
                                                                        <span>&nbsp; Choose Skin</span>
                                                                </div>

                                                                 /section:settings.skins 

                                                                 #section:settings.navbar 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                                                                        <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                                                                </div>

                                                                 /section:settings.navbar 

                                                                 #section:settings.sidebar 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                                                                        <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                                                                </div>

                                                                 /section:settings.sidebar 

                                                                 #section:settings.breadcrumbs 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                                                                        <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                                                                </div>

                                                                 /section:settings.breadcrumbs 

                                                                 #section:settings.rtl 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
                                                                        <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                                                                </div>

                                                                 /section:settings.rtl 

                                                                 #section:settings.container 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                                                                        <label class="lbl" for="ace-settings-add-container">
                                                                                Inside
                                                                                <b>.container</b>
                                                                        </label>
                                                                </div>

                                                                 /section:settings.container 
                                                        </div> /.pull-left 

                                                        <div class="pull-left width-50">
                                                                 #section:basics/sidebar.options 
                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
                                                                        <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                                                                </div>

                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
                                                                        <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                                                                </div>

                                                                <div class="ace-settings-item">
                                                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
                                                                        <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                                                                </div>

                                                                 /section:basics/sidebar.options 
                                                        </div> /.pull-left 
                                                </div> /.ace-settings-box 
                                        </div> /.ace-settings-container -->

                                        <!-- /section:settings.box -->
                                        <div class="row">
                                                <div class="col-xs-12">
                                                        <!-- PAGE CONTENT BEGINS -->
                <?php echo $this->Session->flash(); ?>

                <?php echo $this->fetch('content'); ?>
                                                        
                <?php echo $this->element('sql_dump'); ?>
                                                        <!-- PAGE CONTENT ENDS -->
                                                </div><!-- /.col -->
                                        </div><!-- /.row -->
                                </div><!-- /.page-content -->
                        </div>
                </div><!-- /.main-content -->

                <div class="footer">
                        <div class="footer-inner">
                                <!-- #section:basics/footer -->
                                <!--<div class="footer-content">
                                    © 2015 Cliente@visos - 2.5.2
                                </div>-->

                                <!-- /section:basics/footer -->
                        </div>
                </div>

                <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
                </a>
        </div><!-- /.main-container -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Atenção</h4>
      </div>
      <div class="modal-body">
        <h2>Estará disponível apos implantação deste recurso!</h2>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
