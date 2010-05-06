<?php if ($pager->getNbResults()): ?>
<?php /* {{{ commentList */ ?>
<div class="dparts commentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Comments') ?></h3></div>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagerRelative">
<?php if (!$pager->isFirstPage()): ?><p class="prev"><?php echo link_to(__('Older'), $prevUrl) ?></p><?php endif; ?>
<?php if (!$pager->isLastPage()): ?><p class="next"><?php echo link_to(__('Newer'), $nextUrl) ?></p><?php endif; ?>
</div>
<?php endif; ?>

<ol class="comments">
<?php foreach ($pager->getResults() as $comment): ?>
<li class="comment">
<div class="memberImage">
<?php echo link_to(image_tag_sf_image($comment->Member->getImageFileName(), array('size' => '76x76')), 'member/profile?id='.$comment->getMemberId()) ?>
</div>

<div class="body">
<?php echo op_link_to_member($comment->getMemberId()) ?>&nbsp;
<?php echo op_auto_link_text($comment->body) ?>

<div class="info">
<span class="time"><?php echo op_format_activity_time(strtotime($comment->getCreatedAt())) ?>
</span>
</div>

<?php if ($authorId === $sf_user->getMemberId() || $comment->member_id === $sf_user->getMemberId()): ?>
<div class="operation">
<?php echo link_to(__('Delete'), $deleteConfirmUrl, $comment) ?>
</div>
<?php endif; ?>
</div>

</li>
<?php endforeach; ?>
</ol>

</div></div>
<?php /* }}} */ ?>
<?php endif; ?>