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
if(!isset($_GET['address_id']) OR !is_numeric($_GET['address_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$address_id = $_GET['address_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
require(WB_PATH.'/framework/functions.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH."/modules/addressbook/languages/".LANGUAGE.'.php')) {
		require_once(WB_PATH."/modules/addressbook/languages/EN.php");
	} else {
		require_once(WB_PATH."/modules/addressbook/languages/".LANGUAGE.'.php');
	}
}

//get infos from the Database
$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$fetch_page_content = $query_page_content->fetchRow();
$page_created = $fetch_page_content['link'];

$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_addressbook WHERE address_id = '$address_id'");
$fetch_page_content = $query_page_content->fetchRow();

//echo $module_directory;
//In the lines below you can define which settings could be made to your modul
?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/addressbook/save.php" method="post" style="margin: 0;">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="address_id" value="<?php echo $address_id; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="2"><strong><?php echo $TEXT['MODIFY'].' '.$ABTEXT['ADDRESS']; ?></strong></td>
	</tr>
	<tr>
		<td class="setting_name" width="25%"><?php echo $ABTEXT['SALUTAION']; ?>:</td>
		<td class="setting_name">
<?php
				$herr ="";
				$frau ="";
				$firma="";
			switch ($fetch_page_content['anrede']) {
				case 'Herr':
					$herr="checked";
					break;
				case 'Frau':
					$frau="checked";
					break;
				case 'Firma':
					$firma="checked";
					break;
			} // end switch
?>
			<input type="radio" name="anrede" id="anrede" value="Herr" <?php echo $herr; ?>/><?php echo $ABTEXT['HERR']; ?>
			<input type="radio" name="anrede" id="anrede" value="Frau" <?php echo $frau; ?>/><?php echo $ABTEXT['FRAU']; ?>
			<input type="radio" name="anrede" id="anrede" value="Firma" <?php echo $firma; ?>/><?php echo $ABTEXT['FIRMA']; ?>
		</td>
	</tr>
	<tr
		<td valign="top">
			<?php echo $ABTEXT['LASTNAME']; ?>:
		</td>
		<td valign="top">
			<input name="nachname" type="text" value="<?php echo $fetch_page_content['nachname']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['FIRSTNAME']; ?>:
		</td>
		<td valign="top">
			<input name="vorname" type="text" value="<?php echo $fetch_page_content['vorname']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['BIRTHDAY']; ?>:
		</td>
		<td valign="top">
			<input name="geburtstag" type="text" value="<?php echo $fetch_page_content['geburtstag']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['EMAIL']; ?>:
		</td>
		<td valign="top">
			<input name="email" type="text" value="<?php echo $fetch_page_content['email']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['TEL']; ?>:
		</td>
		<td valign="top">
			<input name="tel" type="text" value="<?php echo $fetch_page_content['tel']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['MOBILE']; ?>:
		</td>
		<td valign="top">
			<input name="mobile" type="text" value="<?php echo $fetch_page_content['mobile']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['FAX']; ?>:
		</td>
		<td valign="top">
			<input name="fax" type="text" value="<?php echo $fetch_page_content['fax']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['STREET']; ?>:
		</td>
		<td valign="top">
			<input name="strasse" type="text" value="<?php echo $fetch_page_content['strasse']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['ZIP']; ?>:
		</td>
		<td valign="top">
			<input name="plz" type="text" value="<?php echo $fetch_page_content['plz']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['CITY']; ?>:
		</td>
		<td valign="top">
			<input name="ort" type="text" value="<?php echo $fetch_page_content['ort']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['COUNTRY']; ?>:
		</td>
		<td valign="top">
			<input name="land" type="text" value="<?php echo $fetch_page_content['land']; ?>" style="width: 200px;">
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php echo $ABTEXT['INFO']; ?>:
		</td>
		<td valign="top">
			<textarea name="info" rows="5" wrap="soft" cols="50"><?php echo $fetch_page_content['info']; ?></textarea>
		</td>
	</tr>
	<tr>
		<td width="80"><?php echo $ABTEXT['CHAPTER']; ?>:</td>
		<td>
			<select name="chapter_id" style="width: 25%;">
			<option value="0"><?php echo $TEXT['NONE']; ?></option>
			<?php
			$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_addressbook_chapter WHERE section_id = '$section_id' ORDER BY position ASC");
			if($query->numRows() > 0) {
				// Loop through chapters
				while($chapter = $query->fetchRow()) {
				  ?>
				  <option value="<?php echo $chapter['chapter_id']; ?>"<?php if($fetch_page_content['chapter_id'] == $chapter['chapter_id']) { echo ' selected'; } ?>><?php echo $chapter['title']; ?></option>
				  <?php
				}
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top" width="25%"><?php echo $TEXT['ACTIVE']; ?>:</td>
		<td valign="top">
			<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_page_content['active'] == 1) { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;"><?php echo $TEXT['YES']; ?></a>
			&nbsp;
			<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_page_content['active'] == 0) { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;"><?php echo $TEXT['NO']; ?></a>
		</td>
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