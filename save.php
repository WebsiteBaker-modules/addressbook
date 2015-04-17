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
if(!isset($_POST['address_id']) OR !is_numeric($_POST['address_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$address_id = $_POST['address_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

//Look for changes
$anrede     = $admin->get_post('anrede');
$nachname   = $admin->get_post('nachname');
$vorname    = $admin->get_post('vorname');
$geburtstag = $admin->get_post('geburtstag');
$email      = $admin->get_post('email');
$tel        = $admin->get_post('tel');
$mobile     = $admin->get_post('mobile');
$fax        = $admin->get_post('fax');
$plz        = $admin->get_post('plz');
$ort        = $admin->get_post('ort');
$strasse    = $admin->get_post('strasse');
$land       = $admin->get_post('land');
$info       = $admin->get_post('info');
$chapter_id = $admin->get_post('chapter_id');
$active     = $admin->get_post('active');

//die ("chapterid = $chapter_id");

//Save changes from modify settings in database
$query = "UPDATE ".TABLE_PREFIX."mod_addressbook SET "
	." chapter_id = '$chapter_id',"
	." active = '$active',"
	." anrede = '$anrede',"
	." nachname = '$nachname',"
	." vorname = '$vorname',"
	." geburtstag = '$geburtstag',"
	." email = '$email',"
	." tel = '$tel',"
	." fax = '$fax',"
	." mobile = '$mobile',"
	." strasse = '$strasse',"
	." plz = '$plz',"
	." ort = '$ort',"
	." land = '$land',"
	." info = '$info'"
	;
$query .= " WHERE address_id = '$address_id'";
$database->query($query);

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer()

?>