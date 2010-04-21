<?php include_partial('albumComment/listComment',array(
'pager' => $pager, 
'prevUrl' => '@album_show?id='.$album->id.'&commentPage='.$pager->getPreviousPage(),
'nextUrl' => '@album_show?id='.$album->id.'&commentPage='.$pager->getNextPage(),
'authorId' => $album->member_id,
'deleteConfirmUrl' => 'album_comment_delete_confirm'
))?>