<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2006, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

 Address Book module for Website Baker
 Copyright (C) 2007 by Hubert Winkel

*/

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
include('info.php');

//Delete Search table entries
$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE name = 'module' AND value = '$module_directory'");
$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE extra = '$module_directory'");

//Delete the modul tables
$database->query("DROP TABLE ".TABLE_PREFIX."mod_".$module_directory);
$database->query("DROP TABLE ".TABLE_PREFIX."mod_".$module_directory."_chapter");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_".$module_directory."_settings");

?>