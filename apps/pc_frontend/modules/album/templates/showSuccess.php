<?php decorate_with('layoutA') ?>
<?php slot('album_show') ?>

<?php end_slot('album_show') ?>
<?php op_include_box('album_show', get_slot('album_show'));
