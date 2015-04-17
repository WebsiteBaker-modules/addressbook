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

if(defined('WB_URL')) {

	// Create table
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$module_directory."`");
	$mod_create_table = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$module_directory.'` ( '
				. '`address_id` int(10) unsigned NOT NULL auto_increment,'
				. '`section_id` INT NOT NULL DEFAULT \'0\' ,'
				. '`page_id` INT NOT NULl DEFAULT \'0\','
				. '`chapter_id` INT NOT NULl DEFAULT \'0\','
				. '`active` INT NOT NULl DEFAULT \'0\','
				. '`position` INT NOT NULl DEFAULT \'0\','
				. '`anrede` enum(\'Herr\',\'Frau\',\'Firma\') NOT NULL default \'Herr\','
				. '`nachname` varchar(30) NOT NULL default \'\' ,'
				. '`vorname` varchar(30) NOT NULL default \'\' ,'
				. '`geburtstag` date NOT NULL default \'0000-00-00\' ,'
				. '`email` varchar(60) NOT NULL default \'\' ,'
				. '`tel` varchar(20) NOT NULL default \'\' ,'
				. '`mobile` varchar(20) NOT NULL default \'\' ,'
				. '`fax` varchar(20) NOT NULL default \'\' ,'
				. '`plz` varchar(5) NOT NULL default \'\' ,'
				. '`ort` varchar(40) NOT NULL default \'\' ,'
				. '`strasse` varchar(40) NOT NULL default \'\' ,'
				. '`land` char(2) NOT NULL default \'\' ,'
				. '`info` text ,'
				. 'PRIMARY KEY (address_id)'
				. ' )';
	$database->query($mod_create_table);

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$module_directory."_chapter`");
	$mod_create_table = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$module_directory.'_chapter` ( '
				. '`chapter_id` INT NOT NULL AUTO_INCREMENT,'
				. '`section_id` INT NOT NULL DEFAULT \'0\','
				. '`page_id` INT NOT NULL DEFAULT \'0\','
				. '`position` INT NOT NULL DEFAULT \'0\','
				. '`active` INT NOT NULL DEFAULT \'0\','
				. '`title` VARCHAR(255) NOT NULL DEFAULT \'\','
				. 'PRIMARY KEY (chapter_id)'
				. ' )';
	$database->query($mod_create_table);

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$module_directory."_settings`");
	$mod_create_table = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$module_directory.'_settings` ( '
				. '`section_id` INT NOT NULL DEFAULT \'0\','
				. '`page_id` INT NOT NULL DEFAULT \'0\','
				. '`header` TEXT NOT NULL ,'
				. '`address_loop` TEXT NOT NULL ,'
				. '`footer` TEXT NOT NULL ,'
				. '`addresses_per_page` INT NOT NULL DEFAULT \'0\','
				. '`address_header` TEXT NOT NULL ,'
				. '`address_footer` TEXT NOT NULL ,'
				. '`chapter_loop` TEXT NOT NULL ,'
				. 'PRIMARY KEY (section_id)'
				. ' )';
	$database->query($mod_create_table);

	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info['description'] = 'description';
	$field_info['modified_when'] = 'modified_when';
	$field_info['modified_by'] = 'modified_by';
	$field_info = serialize($field_info);

	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', '$module_directory', '$field_info')");

	// Search query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_$module_directory, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', '$module_directory')");

	// Search query body
	$query_body_code = "
	[TP]pages.page_id = [TP]mod_$module_directory.page_id AND
	[TP]mod_$module_directory.nachname [O] \'[W][STRING][W]\' AND
	[TP]pages.searching = \'1\' OR
	[TP]pages.page_id = [TP]mod_$module_directory.page_id AND
	[TP]mod_$module_directory.info [O] \'[W][STRING][W]\' AND
	[TP]pages.searching = \'1\'
	";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', '$module_directory')");

	// Search query end
	$query_end_code = "";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', '$module_directory')");

	// Insert blank row (there needs to be at least on row for the search to work)
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_".$module_directory." (page_id,section_id) VALUES ('0','0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_".$module_directory."_chapter (section_id,page_id) VALUES ('0', '0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_".$module_directory."_settings (section_id,page_id) VALUES ('0', '0')");
}

?>