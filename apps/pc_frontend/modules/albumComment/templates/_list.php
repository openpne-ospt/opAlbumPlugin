<?php if ($pager->getNbResults()): ?>
<?php /* {{{ commentList */ ?>
<div class="dparts commentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Comments') ?></h3></div>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagerRelative">
<?php if (!$pager->isFirstPage()): ?><p class="prev"><?php echo link_to(__('Older'), '@album_show?id='.$album->id.'&commentPage='.$pager->getPreviousPage()) ?></p><?php endif; ?>
<?php if (!$pager->isLastPage()): ?><p class="next"><?php echo link_to(__('Newer'), '@album_show?id='.$album->id.'&commentPage='.$pager->getNextPage()) ?></p><?php endif; ?>
</div>
<?php endif; ?>

<?php foreach ($pager->getResults() as $comment): ?>
<dl>
<dt><?php echo nl2br(op_format_date($comment->created_at, 'XDateTimeJaBr')) ?></dt>
<dd>
<div class="title">
<p class="heading">
<?php if ($_member = $comment->Member): ?> <?php echo link_to($_member->name, 'member/profile?id='.$_member->id) ?><?php endif; ?>
<?php if ($album->member_id === $sf_user->getMemberId() || $comment->member_id === $sf_user->getMemberId()): ?>
<?php echo link_to(__('Delete'), 'album_comment_delete_confirm', $comment) ?>
<?php endif; ?>
</p>
</div>
<div class="body">
<p class="text">
<?php echo op_url_cmd(nl2br($comment->body)) ?>
</p>
</div>
</dd>
</dl>
<?php endforeach; ?>
</div></div>
<?php /* }}} */ ?>
<?php endif; ?>
