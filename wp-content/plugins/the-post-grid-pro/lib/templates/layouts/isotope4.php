<?php
/**
 * @var string $grid
 * @var int $pID
 * @var string $isoFilter
 * @var string $imgSrc
 * @var array $items
 * @var string $pLink
 * @var string $anchorClass
 * @var string $link_target
 * @var string $date
 * @var string $author
 * @var string $categories
 * @var string $read_more_text
 */

$html = $metaHtml = null;

$html .= "<div class='{$grid} {$class} {$isoFilter}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
				if($imgSrc) {
					$html .= "<img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'>";
				}
                $html .= '<div class="overlay">';
                    if(in_array('title', $items)){
                        $html .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
                    }
                    if(in_array('post_date', $items) && $date){
                        $metaHtml .= "<span class='date-meta'><i class='fa fa-calendar'></i> {$date}</span>";
                    }
                    if(in_array('author', $items)){
                        $metaHtml .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
                     }
                    if(in_array('categories', $items) && $categories){
                        $metaHtml .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
                    }
                    if(in_array('tags', $items) && $tags){
                        $metaHtml .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
                    }
                    if(in_array('comment_count', $items)){
                        $metaHtml .= '<span class="comment-count"><i class="fa fa-comments-o"></i> '. $comment.'</span>';
                    }
                    if(!empty($metaHtml)){
                        $html .= "<div class='post-meta-user'><p><span class='meta-data'>{$metaHtml}</span></p></div>";
                    }
                    if(in_array('excerpt', $items)){
                        $html .= "<div class='tpg-excerpt'>{$excerpt}</div>";
                    }
                    $postMetaBottom = null;
                    if(in_array('social_share', $items)){
                        $postMetaBottom .= rtTPG()->rtShare($pID);
                    }
                    if(in_array('read_more', $items)){
                        $postMetaBottom .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$read_more_text}</a></span>";
                    }
                    if(!empty($postMetaBottom)){
                        $html .= "<p><div class='post-meta'>$postMetaBottom</div></p>";
                    }
            $html .= '</div>';
    $html .= '</div>';
$html .='</div>';

echo $html;