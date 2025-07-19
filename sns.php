<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<a href="<?php $this->options->siteUrl('feed'); ?>" target="_blank" aria-label="RSS订阅" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa fa-rss"></i></a>
<?php if($this->options->telegramurl): ?>
<a href="<?php $this->options->telegramurl() ?>" target="_blank" aria-label="电报" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa-brands fa-telegram"></i>
</a>
<?php endif; ?>
<?php if($this->options->twitterurl): ?>
<a href="<?php $this->options->twitterurl() ?>" target="_blank" aria-label="推特" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa-brands fa-twitter"></i>
</a>
<?php endif; ?>
<?php if($this->options->instagramurl): ?>
<a href="<?php $this->options->instagramurl() ?>" target="_blank" aria-label="Ins" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa-brands fa-instagram"></i>
</a>
<?php endif; ?>
<?php if($this->options->githuburl): ?>
<a href="<?php $this->options->githuburl() ?>" target="_blank" aria-label="github" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa-brands fa-github"></i>
</a>
<?php endif; ?>
<?php if($this->options->mastodonurl): ?>
<a href="<?php $this->options->mastodonurl() ?>" target="_blank" aria-label="Mastodon" class="btn btn-default btn-lg page-scroll wow fadeInUp">
<i class="fa-brands fa-mastodon"></i>
</a>
<?php endif; ?>