<?php
$users = '';
for ($i=0;$i<count($members);$i++)
{
  if (0 < $i) 
  { 
    $users = $users.', ';
  }
  $users = $users.link_to($members[$i]->name, '@member_profile?id='.$members[$i]->id);
}

if (0 < count($members))
{
  echo __('%users% like it', array('%users%' => $users));
}
?>