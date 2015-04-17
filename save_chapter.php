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

require('../../config.php');

// Get id
if(!isset($_POST['chapter_id']) OR !is_numeric($_POST['chapter_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$chapter_id = $_POST['chapter_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Validate all fields
if($admin->get_post('title') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/addressbook/modify_chapter.php?page_id='.$page_id.'&section_id='.$section_id.'&chapter_id='.$chapter_id);
} else {
	$title = addslashes($admin->get_post('title'));
	$active = $admin->get_post('active');
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_addressbook_chapter SET title = '$title', active = '$active' WHERE chapter_id = '$chapter_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/addressbook/modify_chapter.php?page_id='.$page_id.'&section_id='.$section_id.'&chapter_id='.$chapter_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>