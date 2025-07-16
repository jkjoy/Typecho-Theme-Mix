<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
	<!-- /////////////////////////////////////////Footer -->
	<footer>
		<div id="footer">
			<div class="container">
				<?php $this->options->tongji() ?>
				<p>Copyright &copy; <?php echo date('Y'); ?> . <?php $this->options->title() ?> All rights reserved.</p>
			</div>
		</div>
	</footer>
	<!-- Footer -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/jquery-2.1.1.js'); ?>"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/bootstrap.min.js'); ?>"></script> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/SmoothScroll.js'); ?>"></script> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/wow.min.js'); ?>"></script> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/jquery.isotope.js'); ?>"></script> 
<script type="text/javascript" src="<?php $this->options->themeUrl('js/main.js'); ?>"></script>

</body>
</html>