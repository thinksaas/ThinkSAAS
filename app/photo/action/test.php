<?php 

$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo");

include template("photo");