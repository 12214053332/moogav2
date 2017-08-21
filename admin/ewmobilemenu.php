<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(46, "mmci_Items", $Language->MenuPhrase("46", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(36, "mmi_item_brand", $Language->MenuPhrase("36", "MenuText"), "item_brandlist.php", 46, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}item_brand'), FALSE);
$RootMenu->AddMenuItem(38, "mmi_item_names", $Language->MenuPhrase("38", "MenuText"), "item_nameslist.php", 46, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}item_names'), FALSE);
$RootMenu->AddMenuItem(39, "mmi_item_type", $Language->MenuPhrase("39", "MenuText"), "item_typelist.php", 46, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}item_type'), FALSE);
$RootMenu->AddMenuItem(101, "mmci_Region", $Language->MenuPhrase("101", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(49, "mmi_country", $Language->MenuPhrase("49", "MenuText"), "countrylist.php", 101, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}country'), FALSE);
$RootMenu->AddMenuItem(50, "mmi_cities", $Language->MenuPhrase("50", "MenuText"), "citieslist.php", 101, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}cities'), FALSE);
$RootMenu->AddMenuItem(51, "mmi_states", $Language->MenuPhrase("51", "MenuText"), "stateslist.php", 101, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}states'), FALSE);
$RootMenu->AddMenuItem(102, "mmci_Projects", $Language->MenuPhrase("102", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(13, "mmi_project_field", $Language->MenuPhrase("13", "MenuText"), "project_fieldlist.php", 102, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}project_field'), FALSE);
$RootMenu->AddMenuItem(14, "mmi_project_stage", $Language->MenuPhrase("14", "MenuText"), "project_stagelist.php", 102, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}project_stage'), FALSE);
$RootMenu->AddMenuItem(15, "mmi_project_type", $Language->MenuPhrase("15", "MenuText"), "project_typelist.php", 102, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}project_type'), FALSE);
$RootMenu->AddMenuItem(16, "mmi_projects", $Language->MenuPhrase("16", "MenuText"), "projectslist.php", 102, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}projects'), FALSE);
$RootMenu->AddMenuItem(104, "mmci_Offers", $Language->MenuPhrase("104", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(41, "mmi_offers", $Language->MenuPhrase("41", "MenuText"), "offerslist.php", 104, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}offers'), FALSE);
$RootMenu->AddMenuItem(103, "mmci_Articales", $Language->MenuPhrase("103", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_articles", $Language->MenuPhrase("2", "MenuText"), "articleslist.php", 103, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}articles'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_articles_category", $Language->MenuPhrase("3", "MenuText"), "articles_categorylist.php", 103, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}articles_category'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_author", $Language->MenuPhrase("4", "MenuText"), "authorlist.php", 103, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}author'), FALSE);
$RootMenu->AddMenuItem(19, "mmi_users", $Language->MenuPhrase("19", "MenuText"), "userslist.php", -1, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}users'), FALSE);
$RootMenu->AddMenuItem(21, "mmi_employees", $Language->MenuPhrase("21", "MenuText"), "employeeslist.php", -1, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}employees'), FALSE);
$RootMenu->AddMenuItem(25, "mmi_contactus", $Language->MenuPhrase("25", "MenuText"), "contactuslist.php", -1, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}contactus'), FALSE);
$RootMenu->AddMenuItem(105, "mmci_Reports", $Language->MenuPhrase("105", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(106, "mmi_offers_view_report", $Language->MenuPhrase("106", "MenuText"), "offers_view_reportlist.php", 105, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}offers_view_report'), FALSE);
$RootMenu->AddMenuItem(59, "mmi_projects_view_report", $Language->MenuPhrase("59", "MenuText"), "projects_view_reportlist.php", 105, "", AllowListMenu('{5101AD41-0E34-4393-9492-7002723D723A}projects_view_report'), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
