<?php
/**
 * 缝合怪主题
 * @package 缝合怪
 * @author  老孙 
 * @version 1.0.0
 * @link https://www.imsun.org
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php');?>
	<!-- /////////////////////////////////////////Content -->
	<div id="page-content" class="index-page">	
		<!-- ////////////Content Box 01 -->
		<section id="" class="box-content box-style text-center">
			<div class="container">
				<div class="row heading wow fadeInDown">
					<div class="col-lg-12">
						<h2><strong><?php $this->options->customtitle(); ?></strong></h2>
					  <hr>
					  <div class="clearfix"></div>
					  <p><?php $this->options->customdescription(); ?></p>
					</div>
				</div>
				<div class="categories">
				  <ul class="cat">
					<li>
						<ol class="type list-inline">
							<li><a href="#" data-filter="*" class="active">All</a></li>
              <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
              <?php while ($categories->next()): ?>
              <li><a href="#" data-filter=".<?php echo $categories->slug; ?>"><?php echo $categories->name; ?></a></li>
							<?php endwhile; ?>
						</ol>
					</li>
				  </ul>
				  <div class="clearfix"></div>
				</div>
				<div class="row">
				  <div class="portfolio-items">
<?php $delay = 200;  
$this->widget('Widget_Archive@custom', 'pageSize=9999')->to($posts); 
while ($posts->next()): 
$db = Typecho_Db::get();
$query = $db->select('slug')
    ->from('table.metas')
    ->join('table.relationships', 'table.metas.mid = table.relationships.mid')
    ->where('table.relationships.cid = ?', $posts->cid)
    ->where('table.metas.type = ?', 'category');
$slugs = $db->fetchAll($query);
$categoryClass = $slugs ? implode(' ', array_column($slugs, 'slug')) : 'uncategorized';
?>
    <div class="col-sm-6 col-md-3 col-lg-3 <?php echo $categoryClass; ?>">
        <div class="portfolio-item wow fadeInUp" data-wow-delay="<?php echo $delay;$delay += 200; ?>ms">
            <div class="hover-bg">
                <a href="#portfolioModal-<?php $posts->cid(); ?>" class="portfolio-link" data-toggle="modal">
                    <div class="hover-text">
                        <h4><?php $posts->title(); ?></h4>
                         
                        <div class="clearfix"></div>
                        <i class="fa fa-plus"></i>
                    </div>
                        <?php $firstImage = img_postthumb($posts->cid);$cover = $posts->fields->cover;$imageToDisplay = !empty($cover) ? $cover : $firstImage;if($imageToDisplay): $imageToDisplay = process_cover_image($imageToDisplay);?>
                        <img src="<?php echo $imageToDisplay; ?>" class="img-responsive" alt="<?php $posts->title(); ?>">
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
				  </div>
				</div>
			</div>
		</section>
		
	</div>
<?php $this->widget('Widget_Archive@custom', 'pageSize=9999')->to($posts); while($posts->next()): ?>
<!-- Portfolio Modal <?php echo $posts->cid; ?> -->
<div class="portfolio-modal modal fade" id="portfolioModal-<?php echo $posts->cid; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-content">
    <div class="close-modal" data-dismiss="modal">
      <div class="lr">
        <div class="rl"> </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
          <div class="modal-body"> 
            <!-- Project Details Go Here -->
            <h2><?php $posts->title() ?></h2>
            <p class="item-intro"><?php $posts->date('Y-m-d'); ?></p>
            <!--<img class="img-responsive img-centered" src="<?php echo $imageToDisplay; ?>" alt="">-->
            <p><?php $posts->content(); ?></p>
            <ul class="list-inline">
              <!--
              <li><strong>作者</strong>: <?php $posts->author(); ?></li>
              <?php foreach($posts->categories as $category): ?>
              <li><strong>分类</strong>: <?php echo $category['name']; ?> </li>
              <?php endforeach; ?>
              -->
            </ul>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>	
<?php endwhile; ?>
<?php $this->need('footer.php'); ?>