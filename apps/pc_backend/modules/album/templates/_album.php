<tr><th><?php echo __('ID') ?></th><td><?php echo $album->id ?></td></tr>
<tr><th><?php echo __('Title') ?></th><td><?php echo $album->title ?></td></tr>
<tr><th><?php echo __('Author') ?></th><td><?php echo $album->Member->name ?></td></tr>
<tr><th><?php echo __('Created at') ?></th><td><?php echo op_format_date($album->created_at, 'XDateTimeJa') ?></td></tr>
<tr><th><?php echo __('Body') ?></th><td><?php echo nl2br($album->body) ?></td></tr>
<tr><th><?php echo __('CoverImage') ?></th><td><?php echo image_tag_sf_image($album->getFile(), array('size' => '120x120')) ?></td></tr>
