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

//this adds a new line in the database when you add your modul to a page
$database->query("INSERT INTO ".TABLE_PREFIX."mod_$module_directory (page_id,section_id) VALUES ('$page_id','$section_id')");

$header = addslashes('<style type="text/css">
.line, .line_rightalign {
	border-bottom: 1px solid #DDDDDD;
}
.line_rightalign {
	text-align: right;
	white-space: nowrap;
	font-size: 10px;
}
.line_text {
	padding: 0px 0px 0px 0px;
}
.abheader {
	font-size: 14px;
	font-weight: bold;
	padding: 5px 0px 10px 0px;
}
</style>');

$footer = addslashes('<table cellpadding="0" cellspacing="0" border="0" width="98%" style="display: [DISPLAY_PREVIOUS_NEXT_LINKS]">
<tr>
<td width="35%" align="left">[PREVIOUS_PAGE_LINK]</td>
<td width="30%" align="center">[OF]</td>
<td width="35%" align="right">[NEXT_PAGE_LINK]</td>
</tr>
</table>');

$address_header = addslashes('<table cellpadding="0" cellspacing="0" border="0" width="98%">');

$address_loop = addslashes('<tr>
<td class="line_text"><b>[NACHNAME]</b>, [VORNAME]<br>[STRASSE]<br>[PLZ]&nbsp;[ORT]&nbsp;([LAND])<br><br>tel: [TEL]<br>mobil: [MOBILE]<br>fax: [FAX]<br>email: <a href="mailto:[EMAIL]">[EMAIL]</a><br><br>geb: [GEBURTSTAG]</td>
</tr>
<tr>
<td class="line">info: <i>[INFO]</i><br>&nbsp;</td>
</tr>');

$address_footer = addslashes('</table>');

$chapter_loop = addslashes('<tr>
<td class="abheader">[CHAPTERTITLE]</td>
</tr>');

$database->query("INSERT INTO ".TABLE_PREFIX."mod_addressbook_settings (section_id,page_id,header,address_loop,footer,address_header,address_footer,chapter_loop) VALUES ('$section_id','$page_id','$header','$address_loop','$footer','$address_header','$address_footer','$chapter_loop')");

?>