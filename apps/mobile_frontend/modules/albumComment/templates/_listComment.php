<?php if ($pager->getNbResults()): ?>

<div class="comments">
<?php foreach ($pager->getResults() as $comment): ?>
<div class="comment" style="padding: 1px 0px 4px 0px; border-bottom: 1px solid #b3ceef;">

<div class="body">
<?php echo op_link_to_member($comment->getMemberId()) ?>&nbsp;
<?php echo op_auto_link_text($comment->body) ?>

<div class="info">
<small style="color: rgb(85, 85, 85);"><?php echo op_format_activity_time(strtotime($comment->getCreatedAt())) ?>
</small>
</div>

<?php if ($authorId === $sf_user->getMemberId() || $comment->member_id === $sf_user->getMemberId()): ?>
<div class="operation">
<small>
<?php echo link_to(__('Delete'), $deleteConfirmUrl, $comment) ?>
</small>
</div>
<?php endif; ?>
</div>

</div>
<?php endforeach;?>
</div>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagerRelative" style="padding: 4px 0px 4px 0px; text-align: center">
<small>
<?php if (!$pager->isFirstPage()): ?><span style="margin-right: 4px;"><?php echo link_to(__('Previous'), $prevUrl) ?></span><?php endif; ?>
<?php if (!$pager->isLastPage()): ?><?php echo link_to(__('Next'), $nextUrl) ?><?php endif; ?>
</small>
</div>
<?php endif; ?>

<?php endif; ?>