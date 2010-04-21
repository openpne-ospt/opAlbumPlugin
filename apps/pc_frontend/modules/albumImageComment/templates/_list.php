<?php include_partial('albumComment/listComment',array(
'pager' => $pager, 
'prevUrl' => '@album_image_show?id='.$albumImage->id.'&commentPage='.$pager->getPreviousPage(),
'nextUrl' => '@album_image_show?id='.$albumImage->id.'&commentPage='.$pager->getNextPage(),
'authorId' => $albumImage->member_id,
'deleteConfirmUrl' => 'album_image_comment_delete_confirm'
))?>
