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

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH."/modules/$module_directory/languages/".LANGUAGE.'.php')) {
		require_once(WB_PATH."/modules/$module_directory/languages/EN.php");
	} else {
		require_once(WB_PATH."/modules/$module_directory/languages/".LANGUAGE.'.php');
	}
}

//delete empty records
$database->query("DELETE FROM ".TABLE_PREFIX."mod_addressbook WHERE page_id = '$page_id' and section_id = '$section_id' and nachname = '' and vorname = ''");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_addressbook_chapter WHERE page_id = '$page_id' and section_id = '$section_id' and title = ''");

//In the lines below you can define wich settings could be made to your modul
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" width="33%">
		<input type="button" value="<?php echo $TEXT['ADD'].' '.$ABTEXT['ADDRESS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/addressbook/add_address.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="left" width="33%">
		<input type="button" value="<?php echo $TEXT['ADD'].' '.$ABTEXT['CHAPTER']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/addressbook/add_chapter.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="right" width="33%">
		<input type="button" value="<?php echo $TEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/addressbook/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$ABTEXT['ADDRESS']; ?></h2>

<?php
// Loop through existing addresses
$query_addresses = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_addressbook` WHERE section_id = '$section_id' ORDER BY position ASC");

if($query_addresses->numRows() > 0) {
	$num_addresses = $query_addresses->numRows();
	$row = 'a';
?>

	<table cellpadding="2" cellspacing="0" border="0" width="100%">

<?php
	$settings['ordering'] = '0'; // ordering not yet supported
	while($address = $query_addresses->fetchRow()) {
		$position=$address['position'];
		if ($settings['ordering'] == '0') {
			;
		} else {
			$position = $num_addresses-$position+1;
		};
?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="20" style="padding-left: 5px;">
				<a href="<?php echo WB_URL; ?>/modules/addressbook/modify_address.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&address_id=<?php echo $address['address_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="Modify - " />
				</a>
			</td>
			<td>
				<a href="<?php echo WB_URL; ?>/modules/addressbook/modify_address.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&address_id=<?php echo $address['address_id']; ?>">
					<?php echo stripslashes($address['nachname']).', '.stripslashes($address['vorname']); ?>
				</a>
			</td>
			<td width="150">
				<?php echo $ABTEXT['CHAPTER'].': ';
				// Get address book chapter
				$query_title = $database->query("SELECT title FROM ".TABLE_PREFIX."mod_addressbook_chapter WHERE chapter_id = '".$address['chapter_id']."'");
				if($query_title->numRows() > 0) {
					$fetch_title = $query_title->fetchRow();
					echo $fetch_title['title'];
				} else {
					echo $TEXT['NONE'];
				}
				?>
			</td>
			<td width="80">
				<?php echo $TEXT['ACTIVE'].': '; if($address['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>
			</td>
			<td width="20">
				<?php if($position != 1) { ?>
					<a href="<?php echo WB_URL; ?>/modules/addressbook//move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&address_id=<?php echo $address['address_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
					</a>
				<?php } ?>
			</td>
			<td width="20">
				<?php if($position != $num_addresses) { ?>
					<a href="<?php echo WB_URL; ?>/modules/addressbook//move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&address_id=<?php echo $address['address_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
					</a>
				<?php } ?>
			</td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/addressbook/delete_address.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&address_id=<?php echo $address['address_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	} // end while address
?>
	</table>

<?php
} else {
	echo $TEXT['NONE_FOUND'];
}
?>
<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$ABTEXT['CHAPTER']; ?></h2>

<?php
echo mysql_error();
// Loop through existing chapters
$query_chapters = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_addressbook_chapter` WHERE section_id = '$section_id' ORDER BY position ASC");
if($query_chapters->numRows() > 0) {
	$num_chapters = $query_chapters->numRows();
	$row = 'a';
?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
<?php
		while($chapter = $query_chapters->fetchRow()) {
			$position=$chapter['position'];
			if ($settings['ordering'] == '0') {
				;
			} else {
				$position = $num_chapters-$position+1;
			};
?>
			<tr class="row_<?php echo $row; ?>" height="20">
				<td width="20" style="padding-left: 5px;">
					<a href="<?php echo WB_URL; ?>/modules/addressbook/modify_chapter.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&chapter_id=<?php echo $chapter['chapter_id']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="Modify - " />
					</a>
				</td>
				<td>
					<a href="<?php echo WB_URL; ?>/modules/addressbook/modify_chapter.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&chapter_id=<?php echo $chapter['chapter_id']; ?>">
						<?php echo $chapter['title']; ?>
					</a>
				</td>
				<td width="80">
					<?php echo $TEXT['ACTIVE'].': '; if($chapter['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>
				</td>
				<td width="20">
				<?php if($position != 1) { ?>
					<a href="<?php echo WB_URL; ?>/modules/addressbook/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&chapter_id=<?php echo $chapter['chapter_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
					</a>
				<?php } ?>
				</td>
				<td width="20">
				<?php if($position != $num_chapters) { ?>
					<a href="<?php echo WB_URL; ?>/modules/addressbook/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&chapter_id=<?php echo $chapter['chapter_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
					</a>
				<?php } ?>
				</td>
				<td width="20">
					<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/addressbook/delete_chapter.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&chapter_id=<?php echo $chapter['chapter_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
				</td>
			</tr>
<?php
			// Alternate row color
			if($row == 'a') {
				$row = 'b';
			} else {
				$row = 'a';
			}
		} // end while chapter
?>
		</table>
<?php
} else {
	echo $TEXT['NONE_FOUND'];
}
?>
