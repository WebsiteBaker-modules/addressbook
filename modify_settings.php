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
require(WB_PATH.'/modules/admin.php');							// Include WB admin wrapper script

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/addressbook/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/addressbook/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/addressbook/languages/'.LANGUAGE.'.php');
	}
}

// Get General Settings
$query = "SELECT * FROM ".TABLE_PREFIX."mod_addressbook_settings WHERE section_id = '$section_id'";
//echo '<p>'.$query.'</p>';
$query_content = $database->query($query);
$fetch_content = $query_content->fetchRow();

// !! TODO !! Remove below $raw and $friendly. See flickr

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

?>

<style type="text/css">
.newsection {
	border-top: 1px dashed #fff;
}
</style>

<form name="modify" action="<?php echo WB_URL; ?>/modules/addressbook/save_settings.php" method="post" style="margin: 0;">

	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">

	<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2"><strong><?php echo $ABTEXT['MAIN_SETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td valign="top" width="25%"><?php echo $ABTEXT['ADDRESSES_PER_PAGE']; ?>:</td>
			<td valign="top"><input type="text" name="addresses_per_page" value="<? echo $fetch_content['addresses_per_page']; ?>" style="width: 30px" > 0 = <?php echo $TEXT['UNLIMITED']; ?></td>
		</tr>
	</table>
	
	<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 5px;">
		<tr>
			<td colspan="2"><strong><?php echo $ABTEXT['LAYOUT_SETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td valign="top" width="25%"><?php echo $TEXT['HEADER']; ?>:</td>
			<td valign="top"><textarea name="header" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($fetch_content['header'])); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top" width="25%"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td valign="top"><textarea name="footer" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($fetch_content['footer'])); ?></textarea></td>
		</tr>
		<tr>
			<td class="newsection" valign="top" width="25%"><?php echo $TEXT['HEADER'].' '.$ABTEXT['ADDRESS']; ?>:</td>
			<td class="newsection" valign="top"><textarea name="address_header" style="width: 98%; height: 60px;"><?php echo stripslashes(htmlspecialchars($fetch_content['address_header'])); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top" width="25%"><?php echo $TEXT['LOOP'].' '.$ABTEXT['ADDRESS']; ?>:</td>
			<td valign="top"><textarea name="address_loop" style="width: 98%; height: 60px;"><?php echo stripslashes(htmlspecialchars($fetch_content['address_loop'])); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top"width="25%"><?php echo $TEXT['FOOTER'].' '.$ABTEXT['ADDRESS']; ?>:</td>
			<td valign="top"><textarea name="address_footer" style="width: 98%; height: 60px;"><?php echo stripslashes(htmlspecialchars($fetch_content['address_footer'])); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top"width="25%"><?php echo $TEXT['LOOP'].' '.$ABTEXT['CHAPTER']; ?></td>
			<td valign="top"><textarea name="chapter_loop" style="width:98%; height: 60px;"><?php echo stripslashes(htmlspecialchars($fetch_content['chapter_loop'])); ?></textarea></td>
		</tr>
	</table>
	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;">
			</td>
			<td align="right">
                <input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
</form>

<?php
// Print admin footer
$admin->print_footer();

?>