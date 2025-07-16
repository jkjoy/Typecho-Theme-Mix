<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php');?>
	<!-- /////////////////////////////////////////Content -->
	<div id="page-content" class="sub-page">
		<div id="main-content">
			<article>
				<div class="container">
					<div class="art-content">
						<h1 class="entry-title"><?php $this->title() ?></h1>
						<div class="row">
						<!--<div class="col-md-4">
							<img src="images/plane.jpg" class="img-responsive" alt="About">
						</div>
						<div class="col-md-8">
							<p> <?php $this->date('Y-m-d'); ?> </p>
						</div>-->
					    </div>
						<p> <?php $this->content(); ?> </p>
					</div>
				</div>
			</article>
		</div>
	</div>
<?php $this->need('footer.php'); ?>