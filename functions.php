<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
//主题设置
function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 LOGO 地址'));
    $form->addInput($logoUrl);
    $icoUrl = new Typecho_Widget_Helper_Form_Element_Text('icoUrl', NULL, NULL, _t('站点 Favicon 地址'));
    $form->addInput($icoUrl);
    $sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky', NULL, NULL, _t('置顶文章cid'), _t('多篇文章以`|`符号隔开'), _t('会在首页展示置顶文章。'));
    $form->addInput($sticky);
    $headerbg = new Typecho_Widget_Helper_Form_Element_Text('headerbg', NULL, NULL, _t('header背景'), _t('填写背景图片url'), _t('header背景图片'));
    $form->addInput($headerbg);
    $defaultcover = new Typecho_Widget_Helper_Form_Element_Text('defaultcover', NULL, NULL, _t('默认封面'), _t('填写背景图片url'), _t('文章封面默认图片'));
    $form->addInput($defaultcover);
    $instagramurl = new Typecho_Widget_Helper_Form_Element_Text('instagramurl', NULL, NULL, _t('Instagram'), _t('会在个人信息显示'));
    $form->addInput($instagramurl);
    $telegramurl = new Typecho_Widget_Helper_Form_Element_Text('telegramurl', NULL, 'https://t.me/', _t('电报'), _t('会在个人信息显示'));
    $form->addInput($telegramurl);
    $githuburl = new Typecho_Widget_Helper_Form_Element_Text('githuburl', NULL, 'https://github.com/', _t('github'), _t('会在个人信息显示'));
    $form->addInput($githuburl);
    $twitterurl = new Typecho_Widget_Helper_Form_Element_Text('twitterurl', NULL, NULL, _t('twitter'), _t('会在个人信息显示'));
    $form->addInput($twitterurl);
    $mastodonurl = new Typecho_Widget_Helper_Form_Element_Text('mastodonurl', NULL, NULL, _t('mastodon'), _t('会在个人信息显示'));
    $form->addInput($mastodonurl);
    $friendlyTime = new Typecho_Widget_Helper_Form_Element_Radio('friendlyTime', 
        array('0' => _t('否'),
              '1' => _t('是')),
        '0', _t('是否显示友好时间'), _t('默认不显示友好时间，显示标准时间格式'));
    $form->addInput($friendlyTime);
    $cnavatar = new Typecho_Widget_Helper_Form_Element_Text('cnavatar', NULL, NULL , _t('Gravatar镜像'), _t('默认https://cravatar.cn/avatar/'));
    $form->addInput($cnavatar);
    $addhead = new Typecho_Widget_Helper_Form_Element_Textarea('addhead', NULL, NULL, _t('Head内代码用于网站验证等'), _t('支持HTML'));
    $form->addInput($addhead);
    $tongji = new Typecho_Widget_Helper_Form_Element_Textarea('tongji', NULL, NULL, _t('统计代码'), _t('支持HTML'));
    $form->addInput($tongji);
}

function saveThemeConfig($config) {
    // 可以在这里添加额外的验证或处理逻辑
    return $config;
}

// 自定义字段
function themeFields($layout) {
    $summary= new Typecho_Widget_Helper_Form_Element_Textarea('summary', NULL, NULL, _t('文章摘要'), _t('自定义摘要'));
    $layout->addItem($summary);
    $cover= new Typecho_Widget_Helper_Form_Element_Text('cover', NULL, NULL, _t('文章封面'), _t('自定义文章封面'));
    $layout->addItem($cover);
}

/*
* 文章浏览数统计
*/
function get_post_view($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie
            
        }
    }
    echo $row['views'];
}

/** 头像镜像     */
$options = Typecho_Widget::widget('Widget_Options');
$gravatarPrefix = empty($options->cnavatar) ? 'https://cravatar.cn/avatar/' : $options->cnavatar;
define('__TYPECHO_GRAVATAR_PREFIX__', $gravatarPrefix);

/**
* 页面加载时间
*/
function timer_start() {
    global $timestart;
    $mtime = explode( ' ', microtime() );
    $timestart = $mtime[1] + $mtime[0];
    return true;
    }
    timer_start();
    function timer_stop( $display = 0, $precision = 3 ) {
    global $timestart, $timeend;
    $mtime = explode( ' ', microtime() );
    $timeend = $mtime[1] + $mtime[0];
    $timetotal = number_format( $timeend - $timestart, $precision );
    $r = $timetotal < 1 ? $timetotal * 1000 . " ms" : $timetotal . " s";
    if ( $display ) {
    echo $r;
    }
    return $r;
    }

/**
* 获取文章第一张图片
*/    
function img_postthumb($cid) {
    $db = Typecho_Db::get();
    
    // 首先检查是否设置了自定义封面
    $cover = $db->fetchRow($db->select('str_value')
        ->from('table.fields')
        ->where('cid = ?', $cid)
        ->where('name = ?', 'cover'));
    $options = Typecho_Widget::widget('Widget_Options');
    // 如果找到自定义封面，直接返回
    if ($cover && !empty($cover['str_value'])) {
        return $cover['str_value'];
    }
    
    // 否则尝试从文章内容中获取第一张图片
    $rs = $db->fetchRow($db->select('table.contents.text')
        ->from('table.contents')
        ->where('table.contents.cid=?', $cid)
        ->order('table.contents.cid', Typecho_Db::SORT_ASC)
        ->limit(1));
    // 检查是否获取到结果
    if (!$rs) {
        return "";
    }
    preg_match_all("/https?:\/\/[^\s]*.(png|jpeg|jpg|gif|bmp|webp)/", $rs['text'], $thumbUrl);  //通过正则式获取图片地址
    // 检查是否匹配到图片URL
    if (count($thumbUrl[0]) > 0) {
        return $thumbUrl[0][0];  // 返回第一张图片的URL
    } else {
        return $options->defaultcover;
    }
}


/**
 * 处理图片为封面图（裁剪为5:3，最大宽度500px，转换为webp）
 * 
 * @param string $imageUrl 原始图片URL
 * @return string 处理后的图片URL
 */
function process_cover_image($imageUrl) {
    // 检查GD库是否可用
    if (!function_exists('imagecreatetruecolor')) {
        return $imageUrl; // 如果GD库不可用，返回原图
    }
    
    // 分析URL
    $parsed = parse_url($imageUrl);
    
    // 如果图片是外部链接，需要下载
    $isExternalUrl = !empty($parsed['host']) && $parsed['host'] !== $_SERVER['HTTP_HOST'];
    
    // 生成唯一的文件名（使用MD5哈希）
    $filename = md5($imageUrl) . '.webp';
    
    // 处理后图片的保存路径
    $themeDir = dirname(__FILE__);
    $savePath = $themeDir . '/images/' . $filename;
    $webPath = Helper::options()->themeUrl . '/images/' . $filename;
    
    // 如果缓存文件已存在，直接返回
    if (file_exists($savePath)) {
        return $webPath;
    }
    
    // 获取原始图片内容
    if ($isExternalUrl) {
        // 外部图片，需要下载
        $imageContent = @file_get_contents($imageUrl);
        if (!$imageContent) {
            return $imageUrl; // 无法下载，返回原图
        }
    } else {
        // 本地图片
        $localPath = $_SERVER['DOCUMENT_ROOT'] . $parsed['path'];
        if (!file_exists($localPath)) {
            return $imageUrl; // 无法找到本地文件，返回原图
        }
        $imageContent = @file_get_contents($localPath);
    }
    
    // 创建图像资源
    $originalImage = @imagecreatefromstring($imageContent);
    if (!$originalImage) {
        return $imageUrl; // 无法创建图像资源，返回原图
    }
    
    // 获取原始图片尺寸
    $originalWidth = imagesx($originalImage);
    $originalHeight = imagesy($originalImage);
    
    // 计算目标尺寸（5:3比例，最大宽度500px）
    $targetWidth = min(500, $originalWidth);
    $targetHeight = intval($targetWidth * 1 / 1);
    
    // 计算裁剪坐标（居中裁剪）
    $cropX = 0;
    $cropY = 0;
    $cropWidth = $originalWidth;
    $cropHeight = $originalHeight;
    
    // 计算比例
    $originalRatio = $originalWidth / $originalHeight;
    $targetRatio = 1 / 1;
    
    if ($originalRatio > $targetRatio) {
        // 原图过宽，需要裁剪宽度
        $cropWidth = intval($originalHeight * $targetRatio);
        $cropX = intval(($originalWidth - $cropWidth) / 2);
    } else {
        // 原图过高，需要裁剪高度
        $cropHeight = intval($originalWidth / $targetRatio);
        $cropY = intval(($originalHeight - $cropHeight) / 2);
    }
    
    // 创建目标图像
    $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
    
    // 裁剪并调整大小
    imagecopyresampled(
        $targetImage, 
        $originalImage, 
        0, 0, 
        $cropX, $cropY, 
        $targetWidth, $targetHeight, 
        $cropWidth, $cropHeight
    );
    
    // 保存为webp格式
    if (!function_exists('imagewebp')) {
        // 如果不支持webp，保存为png
        $filename = md5($imageUrl) . '.png';
        $savePath = $themeDir . '/images/' . $filename;
        $webPath = Helper::options()->themeUrl . '/images/' . $filename;
        imagepng($targetImage, $savePath, 9); // 9是最高压缩质量
    } else {
        // 保存为webp
        imagewebp($targetImage, $savePath, 80); // 80是质量参数
    }
    
    // 释放资源
    imagedestroy($originalImage);
    imagedestroy($targetImage);
    
    return $webPath;
}

/**
 * Typecho后台附件增强：图片预览、批量插入、保留官方删除按钮与逻辑
 * @author 老孙博客
 * @date 2025-04-25
 */
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('AttachmentHelper', 'addEnhancedFeatures');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('AttachmentHelper', 'addEnhancedFeatures');

class AttachmentHelper {
    public static function addEnhancedFeatures() {
        ?>
        <style>
        #file-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px;padding:15px;list-style:none;margin:0;}
        #file-list li{position:relative;border:1px solid #e0e0e0;border-radius:4px;padding:10px;background:#fff;transition:all 0.3s ease;list-style:none;margin:0;}
        #file-list li:hover{box-shadow:0 2px 8px rgba(0,0,0,0.1);}
        #file-list li.loading{opacity:0.7;pointer-events:none;}
        .att-enhanced-thumb{position:relative;width:100%;height:150px;margin-bottom:8px;background:#f5f5f5;overflow:hidden;border-radius:3px;display:flex;align-items:center;justify-content:center;}
        .att-enhanced-thumb img{width:100%;height:100%;object-fit:contain;display:block;}
        .att-enhanced-thumb .file-icon{display:flex;align-items:center;justify-content:center;width:100%;height:100%;font-size:40px;color:#999;}
        .att-enhanced-finfo{padding:5px 0;}
        .att-enhanced-fname{font-size:13px;margin-bottom:5px;word-break:break-all;color:#333;}
        .att-enhanced-fsize{font-size:12px;color:#999;}
        .att-enhanced-factions{display:flex;justify-content:space-between;align-items:center;margin-top:8px;gap:8px;}
        .att-enhanced-factions button{flex:1;padding:4px 8px;border:none;border-radius:3px;background:#e0e0e0;color:#333;cursor:pointer;font-size:12px;transition:all 0.2s ease;}
        .att-enhanced-factions button:hover{background:#d0d0d0;}
        .att-enhanced-factions .btn-insert{background:#467B96;color:white;}
        .att-enhanced-factions .btn-insert:hover{background:#3c6a81;}
        .att-enhanced-checkbox{position:absolute;top:5px;right:5px;z-index:2;width:18px;height:18px;cursor:pointer;}
        .batch-actions{margin:15px;display:flex;gap:10px;align-items:center;}
        .btn-batch{padding:8px 15px;border-radius:4px;border:none;cursor:pointer;transition:all 0.3s ease;font-size:10px;display:inline-flex;align-items:center;justify-content:center;}
        .btn-batch.primary{background:#467B96;color:white;}
        .btn-batch.primary:hover{background:#3c6a81;}
        .btn-batch.secondary{background:#e0e0e0;color:#333;}
        .btn-batch.secondary:hover{background:#d0d0d0;}
        .upload-progress{position:absolute;bottom:0;left:0;width:100%;height:2px;background:#467B96;transition:width 0.3s ease;}
        </style>
        <script>
        $(document).ready(function() {
            // 批量操作UI按钮
            var $batchActions = $('<div class="batch-actions"></div>')
                .append('<button type="button" class="btn-batch primary" id="batch-insert">批量插入</button>')
                .append('<button type="button" class="btn-batch secondary" id="select-all">全选</button>')
                .append('<button type="button" class="btn-batch secondary" id="unselect-all">取消全选</button>');
            $('#file-list').before($batchActions);

            // 插入格式
            Typecho.insertFileToEditor = function(title, url, isImage) {
                var textarea = $('#text'), 
                    sel = textarea.getSelection(),
                    insertContent = isImage ? '![' + title + '](' + url + ')' : 
                                            '[' + title + '](' + url + ')';
                textarea.replaceSelection(insertContent + '\n');
                textarea.focus();
            };

            // 批量插入
            $('#batch-insert').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var content = '';
                $('#file-list li').each(function() {
                    if ($(this).find('.att-enhanced-checkbox').is(':checked')) {
                        var $li = $(this);
                        var title = $li.find('.att-enhanced-fname').text();
                        var url = $li.data('url');
                        var isImage = $li.data('image') == 1;
                        content += isImage ? '![' + title + '](' + url + ')\n' : '[' + title + '](' + url + ')\n';
                    }
                });
                if (content) {
                    var textarea = $('#text');
                    var pos = textarea.getSelection();
                    var newContent = textarea.val();
                    newContent = newContent.substring(0, pos.start) + content + newContent.substring(pos.end);
                    textarea.val(newContent);
                    textarea.focus();
                }
            });

            $('#select-all').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#file-list .att-enhanced-checkbox').prop('checked', true);
                return false;
            });
            $('#unselect-all').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#file-list .att-enhanced-checkbox').prop('checked', false);
                return false;
            });

            // 防止复选框冒泡
            $(document).on('click', '.att-enhanced-checkbox', function(e) {e.stopPropagation();});

            // 增强文件列表样式，但不破坏li原结构和官方按钮
            function enhanceFileList() {
                $('#file-list li').each(function() {
                    var $li = $(this);
                    if ($li.hasClass('att-enhanced')) return;
                    $li.addClass('att-enhanced');
                    // 只增强，不清空li
                    // 增加批量选择框
                    if ($li.find('.att-enhanced-checkbox').length === 0) {
                        $li.prepend('<input type="checkbox" class="att-enhanced-checkbox" />');
                    }
                    // 增加图片预览（如已有则不重复加）
                    if ($li.find('.att-enhanced-thumb').length === 0) {
                        var url = $li.data('url');
                        var isImage = $li.data('image') == 1;
                        var fileName = $li.find('.insert').text();
                        var $thumbContainer = $('<div class="att-enhanced-thumb"></div>');
                        if (isImage) {
                            var $img = $('<img src="' + url + '" alt="' + fileName + '" />');
                            $img.on('error', function() {
                                $(this).replaceWith('<div class="file-icon">🖼️</div>');
                            });
                            $thumbContainer.append($img);
                        } else {
                            $thumbContainer.append('<div class="file-icon">📄</div>');
                        }
                        // 插到插入按钮之前
                        $li.find('.insert').before($thumbContainer);
                    }

                });
            }

            // 插入按钮事件
            $(document).on('click', '.btn-insert', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $li = $(this).closest('li');
                var title = $li.find('.att-enhanced-fname').text();
                Typecho.insertFileToEditor(title, $li.data('url'), $li.data('image') == 1);
            });

            // 上传完成后增强新项
            var originalUploadComplete = Typecho.uploadComplete;
            Typecho.uploadComplete = function(attachment) {
                setTimeout(function() {
                    enhanceFileList();
                }, 200);
                if (typeof originalUploadComplete === 'function') {
                    originalUploadComplete(attachment);
                }
            };

            // 首次增强
            enhanceFileList();
        });
        </script>
        <?php
    }
}
?>
<?php 
/**
 * 友好时间显示函数
 * @param int $time 时间戳
 * @param int $threshold 阈值（秒），超过此值则显示标准日期格式（Y-m-d）
 * @return string
 */
function time_ago($time, $threshold = 31536000) { // 31536000秒 = 1年
    $now = time();
    $difference = $now - $time;
    
    // 如果时间差超过阈值（默认1年），则返回标准日期格式（不带时间）
    if ($difference >= $threshold) {
        return date('Y-m-d', $time);
    }
    
    // 1年以内的时间，返回友好格式（如 "3天前"）
    $periods = array("秒", "分钟", "小时", "天", "周", "个月", "年");
    $lengths = array("60", "60", "24", "7", "4.35", "12");
    
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths); $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    return $difference . $periods[$j] . "前";
}