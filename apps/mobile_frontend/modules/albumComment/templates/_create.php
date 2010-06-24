<?php if ($sf_user->getMemberId()): ?>
<?php 
$form->getWidget('body')->setAttribute('rows', 5);
$form->getWidget('body')->setAttribute('cols', 25);

$title = __('Post a comment');
$options = array(
  'form' => $form,
  'url' => $url,
  'button' => __('Save'),
  'isMultipart' => true,
);
include_box($boxName, $title, '', $options);
?>
<?php endif; ?>