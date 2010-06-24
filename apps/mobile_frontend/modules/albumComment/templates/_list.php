<?php include_partial('albumComment/listComment',array(
'pager' => $pager, 
'prevUrl' => url_for('@album_show?id='.$album->id.'&commentPage='.$pager->getPreviousPage()),
'nextUrl' => url_for('@album_show?id='.$album->id.'&commentPage='.$pager->getNextPage()),
'authorId' => $album->member_id,
'deleteConfirmUrl' => 'album_comment_delete_confirm'
))?>