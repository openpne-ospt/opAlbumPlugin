<?php
  if (count($members) == 1) 
  {
    echo __('%user1% likes it', array(
    '%user1%' => link_to($members[0]->name, 'member/profile?id='.$members[0]->id)));
  }
  else if (count($members) == 2) 
  {
    echo __('%user1% and %user2% like it', array(
    '%user1%' => link_to($members[0]->name, 'member/profile?id='.$members[0]->id),
    '%user2%' => link_to($members[1]->name, 'member/profile?id='.$members[1]->id)));
  }
  else if (count($members) == 3) 
  {
    echo __('%user1%, %user2% and %user3% like it', array(
    '%user1%' => link_to($members[0]->name, 'member/profile?id='.$members[0]->id),
    '%user2%' => link_to($members[1]->name, 'member/profile?id='.$members[1]->id),
    '%user3%' => link_to($members[2]->name, 'member/profile?id='.$members[2]->id)));
  }
  else if (count($members) > 3) 
  {
    echo __('%user1%, %user2% and %others% like it', array(
    '%user1%' => link_to($members[0]->name, 'member/profile?id='.$members[0]->id),
    '%user2%' => link_to($members[1]->name, 'member/profile?id='.$members[1]->id),
    '%others%' => link_to('others('.(count($members)-2).')', '')));
  }
?>