<?php include_partial('albumComment/listComment',array(
'pager' => $pager, 
'prevUrl' => url_for('@album_image_show?id='.$albumImage->id.'&commentPage='.$pager->getPreviousPage()),
'nextUrl' => url_for('@album_image_show?id='.$albumImage->id.'&commentPage='.$pager->getNextPage()),
'authorId' => $albumImage->member_id,
'deleteConfirmUrl' => 'album_image_comment_delete_confirm'
))?>