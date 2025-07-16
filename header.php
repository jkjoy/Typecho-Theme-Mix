<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php $this->options->description(); ?>">
    <meta name="author" content="<?php $this->author(); ?>">

    <title><?php if($this->_currentPage>1) echo '第 '.$this->_currentPage.' 页 - '; ?><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'date'      =>  _t('在<span> %s </span>发布的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?>
        <?php $this->options->title(); ?><?php if ($this->is('index')) echo ' - '; ?>
        <?php if ($this->is('index')) $this->options->description() ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php $this->options->themeUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('css/animate.min.css'); ?>">
	
	<!-- Custom Fonts -->
    <link href="<?php $this->options->themeUrl('font-awesome/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
	
    <?php if ($this->options->icoUrl): ?>

    <link rel="icon" href="<?php $this->options->icoUrl() ?>" sizes="32x32" />
    <link rel="apple-touch-icon" href="<?php $this->options->icoUrl() ?>" />

    <?php endif; ?>
    <?php $this->options->addhead() ?>
    <?php //$this->header(); ?>
</head>
<body>
	<!-- /////////////////////////////////////////Navigation -->
    <nav id="menu" class="navbar navbar-default navbar-fixed-top">
		<div class="container"> 
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> 
                <span class="sr-only">Toggle navigation</span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
              </button>
			  <a class="navbar-brand" href="<?php $this->options->siteUrl(); ?>">
                <i class="fa fa-flag-checkered fa-flip-horizontal"></i> <?php $this->options->title() ?>
                <!--<strong>123</strong>-->
              </a> 
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav navbar-right">
                <li><a href="/" class="page-scroll">首页</a></li>
            <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
            <?php while($pages->next()): ?>
                <li><a class="page-scroll" href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
            <?php endwhile; ?>
			  </ul>
			</div>
			<!-- /.navbar-collapse --> 
		</div>
		<!-- /.container-fluid --> 
	</nav>
	<!-- Navigation -->

	<!-- /////////////////////////////////////////Header -->
	<header class="text-center" name="home" <?php if ($this->options->headerbg): ?>style="background-image: url(<?php $this->options->headerbg(); ?>);"<?php endif; ?>>
		<div class="intro-text">
			<h1 class="wow fadeInDown">
                <img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>" class="avatar">
                <!--<strong><span class="color"><?php $this->author(); ?></span></strong>-->
            </h1>
			<p class="wow fadeInDown"><?php $this->author(); ?></p>
			<!--<a href="#" class="btn btn-default btn-lg page-scroll wow fadeInUp" data-wow-delay="200ms">Our Portfolio</a> -->
            <?php $this->need('sns.php'); ?> 
		</div>
	</header>
	<!-- Header -->
	