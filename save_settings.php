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

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// This code removes any php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');

// STEP 1: Retrieve settings from POST vars
if (isset($_POST['addresses_per_page']) AND is_numeric($_POST['addresses_per_page'])) {
    $addresses_per_page = $_POST['addresses_per_page'];
} else {
    $addresses_per_page = '0';
}

$header = addslashes(str_replace($friendly, $raw, $_POST['header']));
$address_loop = addslashes(str_replace($friendly, $raw, $_POST['address_loop']));
$footer = addslashes(str_replace($friendly, $raw, $_POST['footer']));
$address_header = addslashes(str_replace($friendly, $raw, $_POST['address_header']));
$address_footer = addslashes(str_replace($friendly, $raw, $_POST['address_footer']));

$query="UPDATE ".TABLE_PREFIX."mod_addressbook_settings SET
		header = '$header',
		address_loop = '$address_loop',
		footer = '$footer',
		addresses_per_page = '$addresses_per_page',
		address_header = '$address_header',
		address_footer = '$address_footer'
		WHERE section_id = '$section_id'";
//echo '<p>'.$query.'</p>';
$database->query($query);

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>