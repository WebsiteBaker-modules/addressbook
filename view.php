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

if(LANGUAGE_LOADED) {
	require_once(WB_PATH.'/modules/addressbook/languages/EN.php');
	if(!file_exists(WB_PATH.'/modules/addressbook/languages/'.LANGUAGE.'.php')) {
		} else {
		require_once(WB_PATH.'/modules/addressbook/languages/'.LANGUAGE.'.php');
	}
}

//get content from database
//$query_result = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_$module_directory WHERE section_id = '$section_id'");


// Check if there is a start point defined
if(isset($_GET['dgp']) AND is_numeric($_GET['dgp']) AND $_GET['dgp'] >= 0) {
	$position = $_GET['dgp'];
} else {
	$position = 0;
}

// Get settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_addressbook_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() > 0) {
	$fetch_settings = $query_settings->fetchRow();
	$setting_header = stripslashes($fetch_settings['header']);
	$setting_footer = stripslashes($fetch_settings['footer']);
	$setting_address_header = stripslashes($fetch_settings['address_header']);
	$setting_address_loop = stripslashes($fetch_settings['address_loop']);
	$setting_address_footer = stripslashes($fetch_settings['address_footer']);
	$setting_chapter = stripslashes($fetch_settings['chapter_loop']);
	$setting_addresses_per_page = $fetch_settings['addresses_per_page'];
} else {
	$setting_header = '';
	$setting_address_loop = '';
	$setting_footer = '';
	$setting_addresses_per_page = '';
}

// Get total number of addresses
$query_total_num = $database->query("SELECT address_id FROM ".TABLE_PREFIX."mod_addressbook WHERE section_id = '$section_id' AND active = '1' AND nachname != ''");
$total_num = $query_total_num->numRows();

// Work-out if we need to add limit code to sql
if($setting_addresses_per_page != 0) {
	$limit_sql = " LIMIT $position,$setting_addresses_per_page";
} else {
	$limit_sql = "";
}

// Query adresses (for this page)
$dlsearch ='';
$query_addresses = $database->query("SELECT address_id, nachname, vorname, geburtstag, strasse, plz, ort, tel, mobile, fax, email, land, info, ".TABLE_PREFIX."mod_addressbook.chapter_id
		FROM ".TABLE_PREFIX."mod_addressbook
		LEFT JOIN ".TABLE_PREFIX."mod_addressbook_chapter
		ON (".TABLE_PREFIX."mod_addressbook.chapter_id = ".TABLE_PREFIX."mod_addressbook_chapter.chapter_id)
		WHERE  ".TABLE_PREFIX."mod_addressbook.section_id = '$section_id'
		AND  ".TABLE_PREFIX."mod_addressbook.active = '1'
		AND  ".TABLE_PREFIX."mod_addressbook.nachname != ''
		".$dlsearch."
		ORDER BY ".TABLE_PREFIX."mod_addressbook_chapter.position, nachname " . $limit_sql);

$num_addresses = $query_addresses->numRows();

// Create previous and next links
if($setting_addresses_per_page != 0) {
	if($position > 0) {
		if(isset($_GET['dgg']) AND is_numeric($_GET['dgg'])) {
			$pl_prepend = '<a href="?dgp='.($position-$setting_addresses_per_page).'&dgg='.$_GET['dgg'].'"><< ';
		} else {
			$pl_prepend = '<a href="?dgp='.($position-$setting_addresses_per_page).'"><< ';
		}
		$pl_append = '</a>';
		$previous_link = $pl_prepend.$TEXT['PREVIOUS'].$pl_append;
		$previous_page_link = $pl_prepend.$TEXT['PREVIOUS_PAGE'].$pl_append;
	} else {
		$previous_link = '';
		$previous_page_link = '';
	}
	if($position+$setting_addresses_per_page >= $total_num) {
		$next_link = '';
		$next_page_link = '';
	} else {
		if(isset($_GET['dgg']) AND is_numeric($_GET['dgg'])) {
			$nl_prepend = '<a href="?p='.($position+$setting_addresses_per_page).'&dgg='.$_GET['dgg'].'"> ';
		} else {
			$nl_prepend = '<a href="?dgp='.($position+$setting_addresses_per_page).'"> ';
		}
		$nl_append = ' >></a>';
		$next_link = $nl_prepend.$TEXT['NEXT'].$nl_append;
		$next_page_link = $nl_prepend.$TEXT['NEXT_PAGE'].$nl_append;
	}
	if($position+$setting_addresses_per_page > $total_num) {
		$num_of = $position+$num_addresses;
	} else {
		$num_of = $position+$setting_addresses_per_page;
	}
	$out_of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
	$of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OF']).' '.$total_num;
	$display_previous_next_links = '';
} else {
	$display_previous_next_links = 'none';
}

// Print header
if($display_previous_next_links == 'none') {
	echo  str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'), array('','','','','','', $display_previous_next_links), $setting_header);
} else {
	echo str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]'), array($next_page_link, $next_link, $previous_page_link, $previous_link, $out_of, $of, $display_previous_next_links), $setting_header);
}

// Loop through and show addresses
if($num_addresses > 0) {
	$counter = 0; $prechapter = '';
	echo $setting_address_header;
	while($address = $query_addresses->fetchRow()) {

		//$setting_chapter
		if($address['chapter_id']!=$prechapter){
			$chapter_id=$address['chapter_id'];

			$query_chapter = $database->query("SELECT chapter_id, title FROM ".TABLE_PREFIX."mod_addressbook_chapter WHERE section_id = '$section_id' and chapter_id='$chapter_id'");
			$chapter=$query_chapter->fetchRow();


			if($chapter['title'] != "") {
				$chapter_title=$chapter['title'];
			}else{
				$chapter_title=$ABTEXT['NOGROUP'];
			}

			$chvars = array('[CHAPTERTITLE]');
			$chvalues=array($chapter_title);
//			echo $settings_chapterheader;
			echo str_replace($chvars, $chvalues, $setting_chapter);
//			echo $settings_chapterfooter;
		}


		// Replace vars with values
		$vars = array('[NACHNAME]', '[VORNAME]', '[STRASSE]', '[PLZ]', '[ORT]', '[LAND]', '[TEL]', '[MOBILE]', '[FAX]', '[EMAIL]', '[GEBURTSTAG]', '[INFO]');

		$values = array(stripslashes($address['nachname']), stripslashes($address['vorname']), stripslashes($address['strasse']), $address['plz'], stripslashes($address['ort']), $address['land'], $address['tel'], $address['mobile'], $address['fax'], $address['email'], $address['geburtstag'], $address['info'] );

		echo str_replace($vars, $values, $setting_address_loop);
		// Increment counter
		$counter = $counter+1;
		$prechapter=$address['chapter_id'];
	}
	echo $setting_address_footer;
} // end if num_addresses

// Print footer
$search = ''; // not used yet
echo "<br>\n";
if($display_previous_next_links == 'none') {
	echo  str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]','[SEARCH]'), array('','','','','','', $display_previous_next_links,$search), $setting_footer);
} else {
	echo str_replace(array('[NEXT_PAGE_LINK]','[NEXT_LINK]','[PREVIOUS_PAGE_LINK]','[PREVIOUS_LINK]','[OUT_OF]','[OF]','[DISPLAY_PREVIOUS_NEXT_LINKS]','[SEARCH]'), array($next_page_link, $next_link, $previous_page_link, $previous_link, $out_of, $of, $display_previous_next_links,$search), $setting_footer);
}

//-------------------------------------------------------------------------------
//echo displayAddressRecords($query_result);


/******************************************************************************
 * FUNCTIONS
 *****************************************************************************/
function displayAddressRecords($query_result) {

  $numrows = $query_result->numRows();
//  echo "<P>$query_result, $numrows</P>";

  $content = "<P><B>$numrows</B> Treffer gefunden.</P>\n";

  $content .= '<TABLE BGCOLOR="#CCCCCC" BORDER="1" CELLSPACING="0" CELLPADDING="3">' . "\n";
  if ($numrows >0) {
	  $content .= '<TR>'
			   . '<TH>Name</TH>'
			   . '<TH>Vorname</TH>'
			   . '<TH>E-Mail</TH>'
			   . '<TH>Telefon</TH>'
			   . '<TH>Land</TH>'
			   . "</TR>\n";
  }

  $color = array("#FFFFCC", "#FFFFFF");
  for ($i=0; $i<$numrows; $i++)
  {
    $row   = $query_result->fetchRow();

	$pk       = $row["address_id"];
	$nachname = $row["nachname"];
	// leere Felder ergeben haessliches Tabellen Layout, daher wenigstens &nbsp; ausgeben
	$vorname  = (empty($row["vorname"])) ? '&nbsp;' : $row["vorname"];
	$email    = $row["email"];
	$tel      = $row["tel"];
	$land     = $row["land"];
	$info     = $row["info"];
	// strip slashes entfernt die "escape slashes" aus den MySQL strings
	$row["info"] = stripslashes($row["info"]);
	$info     = (empty($row["info"])) ? '-' : $row["info"];

	$j = $i%2;
	$content .= '<TR BGCOLOR="' . $color[$j] . '">'
			 . '<TD VALIGN="top">' . $nachname . '</TD>'
			 . '<TD VALIGN="top">' . $vorname . '</TD>'
			 . '<TD VALIGN="top">' . $email . '</TD>'
			 . '<TD VALIGN="top">' . $tel . '</TD>'
			 . '<TD VALIGN="top">' . $land . '</TD>'
			 . "</TR>\n";
	$content .= '<TR BGCOLOR="' . $color[$j] . '">'
			 . '<TD VALIGN="top" COLSPAN="5">' . $info . '</TD>'
			 . "</TR>\n";
  }
  $content .= "</TABLE>\n";

  return $content;

} // end funtion displayAddressRecords()

?>