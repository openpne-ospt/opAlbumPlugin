<?php decorate_with('layoutA') ?>
<?php slot('album_show') ?>
<?php echo $this->album; ?>
<?php end_slot('album_show') ?>
<?php op_include_box('album_show', get_slot('album_show'));
