<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "articlesinfo.php" ?>
<?php include_once "employeesinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$articles_add = NULL; // Initialize page object first

class carticles_add extends carticles {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{5101AD41-0E34-4393-9492-7002723D723A}";

	// Table name
	var $TableName = 'articles';

	// Page object name
	var $PageObjName = 'articles_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (articles)
		if (!isset($GLOBALS["articles"]) || get_class($GLOBALS["articles"]) == "carticles") {
			$GLOBALS["articles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articles"];
		}

		// Table object (employees)
		if (!isset($GLOBALS['employees'])) $GLOBALS['employees'] = new cemployees();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'articles', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (employees)
		if (!isset($UserTable)) {
			$UserTable = new cemployees();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("articleslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $articles;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($articles);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("articleslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "articlesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->picpath->Upload->Index = $objForm->Index;
		$this->picpath->Upload->UploadFile();
		$this->picpath->CurrentValue = $this->picpath->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->category_id->CurrentValue = NULL;
		$this->category_id->OldValue = $this->category_id->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->picpath->Upload->DbValue = NULL;
		$this->picpath->OldValue = $this->picpath->Upload->DbValue;
		$this->picpath->CurrentValue = NULL; // Clear file related field
		$this->article_date->CurrentValue = NULL;
		$this->article_date->OldValue = $this->article_date->CurrentValue;
		$this->author_id->CurrentValue = NULL;
		$this->author_id->OldValue = $this->author_id->CurrentValue;
		$this->views->CurrentValue = 0;
		$this->public->CurrentValue = NULL;
		$this->public->OldValue = $this->public->CurrentValue;
		$this->public_title->CurrentValue = NULL;
		$this->public_title->OldValue = $this->public_title->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->category_id->FldIsDetailKey) {
			$this->category_id->setFormValue($objForm->GetValue("x_category_id"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->article_date->FldIsDetailKey) {
			$this->article_date->setFormValue($objForm->GetValue("x_article_date"));
			$this->article_date->CurrentValue = ew_UnFormatDateTime($this->article_date->CurrentValue, 5);
		}
		if (!$this->author_id->FldIsDetailKey) {
			$this->author_id->setFormValue($objForm->GetValue("x_author_id"));
		}
		if (!$this->views->FldIsDetailKey) {
			$this->views->setFormValue($objForm->GetValue("x_views"));
		}
		if (!$this->public->FldIsDetailKey) {
			$this->public->setFormValue($objForm->GetValue("x_public"));
		}
		if (!$this->public_title->FldIsDetailKey) {
			$this->public_title->setFormValue($objForm->GetValue("x_public_title"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->category_id->CurrentValue = $this->category_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->article_date->CurrentValue = $this->article_date->FormValue;
		$this->article_date->CurrentValue = ew_UnFormatDateTime($this->article_date->CurrentValue, 5);
		$this->author_id->CurrentValue = $this->author_id->FormValue;
		$this->views->CurrentValue = $this->views->FormValue;
		$this->public->CurrentValue = $this->public->FormValue;
		$this->public_title->CurrentValue = $this->public_title->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->picpath->Upload->DbValue = $rs->fields('picpath');
		$this->picpath->CurrentValue = $this->picpath->Upload->DbValue;
		$this->article_date->setDbValue($rs->fields('article_date'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->views->setDbValue($rs->fields('views'));
		$this->public->setDbValue($rs->fields('public'));
		$this->public_title->setDbValue($rs->fields('public_title'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->category_id->DbValue = $row['category_id'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->picpath->Upload->DbValue = $row['picpath'];
		$this->article_date->DbValue = $row['article_date'];
		$this->author_id->DbValue = $row['author_id'];
		$this->views->DbValue = $row['views'];
		$this->public->DbValue = $row['public'];
		$this->public_title->DbValue = $row['public_title'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// category_id
		// name
		// description
		// picpath
		// article_date
		// author_id
		// views
		// public
		// public_title

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// category_id
		if (strval($this->category_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->category_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articles_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category_id->ViewValue = $this->category_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category_id->ViewValue = $this->category_id->CurrentValue;
			}
		} else {
			$this->category_id->ViewValue = NULL;
		}
		$this->category_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// picpath
		if (!ew_Empty($this->picpath->Upload->DbValue)) {
			$this->picpath->ImageAlt = $this->picpath->FldAlt();
			$this->picpath->ViewValue = $this->picpath->Upload->DbValue;
		} else {
			$this->picpath->ViewValue = "";
		}
		$this->picpath->ViewCustomAttributes = "";

		// article_date
		$this->article_date->ViewValue = $this->article_date->CurrentValue;
		$this->article_date->ViewValue = ew_FormatDateTime($this->article_date->ViewValue, 5);
		$this->article_date->ViewCustomAttributes = "";

		// author_id
		if (strval($this->author_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->author_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `author`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->author_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->author_id->ViewValue = $this->author_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->author_id->ViewValue = $this->author_id->CurrentValue;
			}
		} else {
			$this->author_id->ViewValue = NULL;
		}
		$this->author_id->ViewCustomAttributes = "";

		// views
		$this->views->ViewValue = $this->views->CurrentValue;
		$this->views->ViewCustomAttributes = "";

		// public
		$this->public->ViewValue = $this->public->CurrentValue;
		$this->public->ViewCustomAttributes = "";

		// public_title
		$this->public_title->ViewValue = $this->public_title->CurrentValue;
		$this->public_title->ViewCustomAttributes = "";

			// category_id
			$this->category_id->LinkCustomAttributes = "";
			$this->category_id->HrefValue = "";
			$this->category_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// picpath
			$this->picpath->LinkCustomAttributes = "";
			if (!ew_Empty($this->picpath->Upload->DbValue)) {
				$this->picpath->HrefValue = ew_GetFileUploadUrl($this->picpath, $this->picpath->Upload->DbValue); // Add prefix/suffix
				$this->picpath->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picpath->HrefValue = ew_ConvertFullUrl($this->picpath->HrefValue);
			} else {
				$this->picpath->HrefValue = "";
			}
			$this->picpath->HrefValue2 = $this->picpath->UploadPath . $this->picpath->Upload->DbValue;
			$this->picpath->TooltipValue = "";
			if ($this->picpath->UseColorbox) {
				$this->picpath->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->picpath->LinkAttrs["data-rel"] = "articles_x_picpath";

				//$this->picpath->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->picpath->LinkAttrs["data-placement"] = "bottom";
				//$this->picpath->LinkAttrs["data-container"] = "body";

				$this->picpath->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// article_date
			$this->article_date->LinkCustomAttributes = "";
			$this->article_date->HrefValue = "";
			$this->article_date->TooltipValue = "";

			// author_id
			$this->author_id->LinkCustomAttributes = "";
			$this->author_id->HrefValue = "";
			$this->author_id->TooltipValue = "";

			// views
			$this->views->LinkCustomAttributes = "";
			$this->views->HrefValue = "";
			$this->views->TooltipValue = "";

			// public
			$this->public->LinkCustomAttributes = "";
			$this->public->HrefValue = "";
			$this->public->TooltipValue = "";

			// public_title
			$this->public_title->LinkCustomAttributes = "";
			$this->public_title->HrefValue = "";
			$this->public_title->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// category_id
			$this->category_id->EditAttrs["class"] = "form-control";
			$this->category_id->EditCustomAttributes = "";
			if (trim(strval($this->category_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->category_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `articles_category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->category_id->EditValue = $arwrk;

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// picpath
			$this->picpath->EditAttrs["class"] = "form-control";
			$this->picpath->EditCustomAttributes = "";
			if (!ew_Empty($this->picpath->Upload->DbValue)) {
				$this->picpath->ImageAlt = $this->picpath->FldAlt();
				$this->picpath->EditValue = $this->picpath->Upload->DbValue;
			} else {
				$this->picpath->EditValue = "";
			}
			if (!ew_Empty($this->picpath->CurrentValue))
				$this->picpath->Upload->FileName = $this->picpath->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->picpath);

			// article_date
			$this->article_date->EditAttrs["class"] = "form-control";
			$this->article_date->EditCustomAttributes = "";
			$this->article_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->article_date->CurrentValue, 5));
			$this->article_date->PlaceHolder = ew_RemoveHtml($this->article_date->FldCaption());

			// author_id
			$this->author_id->EditAttrs["class"] = "form-control";
			$this->author_id->EditCustomAttributes = "";
			if (trim(strval($this->author_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->author_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `author`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->author_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->author_id->EditValue = $arwrk;

			// views
			$this->views->EditAttrs["class"] = "form-control";
			$this->views->EditCustomAttributes = "";
			$this->views->EditValue = ew_HtmlEncode($this->views->CurrentValue);
			$this->views->PlaceHolder = ew_RemoveHtml($this->views->FldCaption());

			// public
			$this->public->EditAttrs["class"] = "form-control";
			$this->public->EditCustomAttributes = "";
			$this->public->EditValue = ew_HtmlEncode($this->public->CurrentValue);
			$this->public->PlaceHolder = ew_RemoveHtml($this->public->FldCaption());

			// public_title
			$this->public_title->EditAttrs["class"] = "form-control";
			$this->public_title->EditCustomAttributes = "";
			$this->public_title->EditValue = ew_HtmlEncode($this->public_title->CurrentValue);
			$this->public_title->PlaceHolder = ew_RemoveHtml($this->public_title->FldCaption());

			// Edit refer script
			// category_id

			$this->category_id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// picpath
			if (!ew_Empty($this->picpath->Upload->DbValue)) {
				$this->picpath->HrefValue = ew_GetFileUploadUrl($this->picpath, $this->picpath->Upload->DbValue); // Add prefix/suffix
				$this->picpath->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picpath->HrefValue = ew_ConvertFullUrl($this->picpath->HrefValue);
			} else {
				$this->picpath->HrefValue = "";
			}
			$this->picpath->HrefValue2 = $this->picpath->UploadPath . $this->picpath->Upload->DbValue;

			// article_date
			$this->article_date->HrefValue = "";

			// author_id
			$this->author_id->HrefValue = "";

			// views
			$this->views->HrefValue = "";

			// public
			$this->public->HrefValue = "";

			// public_title
			$this->public_title->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->category_id->FldIsDetailKey && !is_null($this->category_id->FormValue) && $this->category_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->category_id->FldCaption(), $this->category_id->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if ($this->picpath->Upload->FileName == "" && !$this->picpath->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->picpath->FldCaption(), $this->picpath->ReqErrMsg));
		}
		if (!$this->article_date->FldIsDetailKey && !is_null($this->article_date->FormValue) && $this->article_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->article_date->FldCaption(), $this->article_date->ReqErrMsg));
		}
		if (!ew_CheckDate($this->article_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->article_date->FldErrMsg());
		}
		if (!$this->author_id->FldIsDetailKey && !is_null($this->author_id->FormValue) && $this->author_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->author_id->FldCaption(), $this->author_id->ReqErrMsg));
		}
		if (!$this->views->FldIsDetailKey && !is_null($this->views->FormValue) && $this->views->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->views->FldCaption(), $this->views->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->views->FormValue)) {
			ew_AddMessage($gsFormError, $this->views->FldErrMsg());
		}
		if (!$this->public->FldIsDetailKey && !is_null($this->public->FormValue) && $this->public->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->public->FldCaption(), $this->public->ReqErrMsg));
		}
		if (!$this->public_title->FldIsDetailKey && !is_null($this->public_title->FormValue) && $this->public_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->public_title->FldCaption(), $this->public_title->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// category_id
		$this->category_id->SetDbValueDef($rsnew, $this->category_id->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// picpath
		if (!$this->picpath->Upload->KeepFile) {
			$this->picpath->Upload->DbValue = ""; // No need to delete old file
			if ($this->picpath->Upload->FileName == "") {
				$rsnew['picpath'] = NULL;
			} else {
				$rsnew['picpath'] = $this->picpath->Upload->FileName;
			}
		}

		// article_date
		$this->article_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->article_date->CurrentValue, 5), NULL, FALSE);

		// author_id
		$this->author_id->SetDbValueDef($rsnew, $this->author_id->CurrentValue, NULL, FALSE);

		// views
		$this->views->SetDbValueDef($rsnew, $this->views->CurrentValue, 0, strval($this->views->CurrentValue) == "");

		// public
		$this->public->SetDbValueDef($rsnew, $this->public->CurrentValue, "", FALSE);

		// public_title
		$this->public_title->SetDbValueDef($rsnew, $this->public_title->CurrentValue, "", FALSE);
		if (!$this->picpath->Upload->KeepFile) {
			if (!ew_Empty($this->picpath->Upload->Value)) {
				$rsnew['picpath'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->picpath->UploadPath), $rsnew['picpath']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
				if (!$this->picpath->Upload->KeepFile) {
					if (!ew_Empty($this->picpath->Upload->Value)) {
						$this->picpath->Upload->SaveToFile($this->picpath->UploadPath, $rsnew['picpath'], TRUE);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// picpath
		ew_CleanUploadTempPath($this->picpath, $this->picpath->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "articleslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($articles_add)) $articles_add = new carticles_add();

// Page init
$articles_add->Page_Init();

// Page main
$articles_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articles_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = farticlesadd = new ew_Form("farticlesadd", "add");

// Validate form
farticlesadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_category_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->category_id->FldCaption(), $articles->category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->name->FldCaption(), $articles->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->description->FldCaption(), $articles->description->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_picpath");
			elm = this.GetElements("fn_x" + infix + "_picpath");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->picpath->FldCaption(), $articles->picpath->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_article_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->article_date->FldCaption(), $articles->article_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_article_date");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articles->article_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_author_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->author_id->FldCaption(), $articles->author_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_views");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->views->FldCaption(), $articles->views->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_views");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articles->views->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_public");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->public->FldCaption(), $articles->public->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_public_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articles->public_title->FldCaption(), $articles->public_title->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
farticlesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticlesadd.ValidateRequired = true;
<?php } else { ?>
farticlesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticlesadd.Lists["x_category_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
farticlesadd.Lists["x_author_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $articles_add->ShowPageHeader(); ?>
<?php
$articles_add->ShowMessage();
?>
<form name="farticlesadd" id="farticlesadd" class="<?php echo $articles_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articles_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articles_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articles">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($articles->category_id->Visible) { // category_id ?>
	<div id="r_category_id" class="form-group">
		<label id="elh_articles_category_id" for="x_category_id" class="col-sm-2 control-label ewLabel"><?php echo $articles->category_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->category_id->CellAttributes() ?>>
<span id="el_articles_category_id">
<select data-table="articles" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($articles->category_id->DisplayValueSeparator) ? json_encode($articles->category_id->DisplayValueSeparator) : $articles->category_id->DisplayValueSeparator) ?>" id="x_category_id" name="x_category_id"<?php echo $articles->category_id->EditAttributes() ?>>
<?php
if (is_array($articles->category_id->EditValue)) {
	$arwrk = $articles->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($articles->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $articles->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($articles->category_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($articles->category_id->CurrentValue) ?>" selected><?php echo $articles->category_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articles_category`";
$sWhereWrk = "";
$articles->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$articles->category_id->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "20", "fn0" => "");
$sSqlWrk = "";
$articles->Lookup_Selecting($articles->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $articles->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_category_id" id="s_x_category_id" value="<?php echo $articles->category_id->LookupFilterQuery() ?>">
</span>
<?php echo $articles->category_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_articles_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $articles->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->name->CellAttributes() ?>>
<span id="el_articles_name">
<input type="text" data-table="articles" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($articles->name->getPlaceHolder()) ?>" value="<?php echo $articles->name->EditValue ?>"<?php echo $articles->name->EditAttributes() ?>>
</span>
<?php echo $articles->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_articles_description" class="col-sm-2 control-label ewLabel"><?php echo $articles->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->description->CellAttributes() ?>>
<span id="el_articles_description">
<?php ew_AppendClass($articles->description->EditAttrs["class"], "editor"); ?>
<textarea data-table="articles" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($articles->description->getPlaceHolder()) ?>"<?php echo $articles->description->EditAttributes() ?>><?php echo $articles->description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("farticlesadd", "x_description", 0, 0, <?php echo ($articles->description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $articles->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->picpath->Visible) { // picpath ?>
	<div id="r_picpath" class="form-group">
		<label id="elh_articles_picpath" class="col-sm-2 control-label ewLabel"><?php echo $articles->picpath->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->picpath->CellAttributes() ?>>
<span id="el_articles_picpath">
<div id="fd_x_picpath">
<span title="<?php echo $articles->picpath->FldTitle() ? $articles->picpath->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($articles->picpath->ReadOnly || $articles->picpath->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="articles" data-field="x_picpath" name="x_picpath" id="x_picpath"<?php echo $articles->picpath->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_picpath" id= "fn_x_picpath" value="<?php echo $articles->picpath->Upload->FileName ?>">
<input type="hidden" name="fa_x_picpath" id= "fa_x_picpath" value="0">
<input type="hidden" name="fs_x_picpath" id= "fs_x_picpath" value="255">
<input type="hidden" name="fx_x_picpath" id= "fx_x_picpath" value="<?php echo $articles->picpath->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_picpath" id= "fm_x_picpath" value="<?php echo $articles->picpath->UploadMaxFileSize ?>">
</div>
<table id="ft_x_picpath" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $articles->picpath->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->article_date->Visible) { // article_date ?>
	<div id="r_article_date" class="form-group">
		<label id="elh_articles_article_date" for="x_article_date" class="col-sm-2 control-label ewLabel"><?php echo $articles->article_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->article_date->CellAttributes() ?>>
<span id="el_articles_article_date">
<input type="text" data-table="articles" data-field="x_article_date" data-format="5" name="x_article_date" id="x_article_date" placeholder="<?php echo ew_HtmlEncode($articles->article_date->getPlaceHolder()) ?>" value="<?php echo $articles->article_date->EditValue ?>"<?php echo $articles->article_date->EditAttributes() ?>>
<?php if (!$articles->article_date->ReadOnly && !$articles->article_date->Disabled && !isset($articles->article_date->EditAttrs["readonly"]) && !isset($articles->article_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("farticlesadd", "x_article_date", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $articles->article_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->author_id->Visible) { // author_id ?>
	<div id="r_author_id" class="form-group">
		<label id="elh_articles_author_id" for="x_author_id" class="col-sm-2 control-label ewLabel"><?php echo $articles->author_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->author_id->CellAttributes() ?>>
<span id="el_articles_author_id">
<select data-table="articles" data-field="x_author_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($articles->author_id->DisplayValueSeparator) ? json_encode($articles->author_id->DisplayValueSeparator) : $articles->author_id->DisplayValueSeparator) ?>" id="x_author_id" name="x_author_id"<?php echo $articles->author_id->EditAttributes() ?>>
<?php
if (is_array($articles->author_id->EditValue)) {
	$arwrk = $articles->author_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($articles->author_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $articles->author_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($articles->author_id->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($articles->author_id->CurrentValue) ?>" selected><?php echo $articles->author_id->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `author`";
$sWhereWrk = "";
$articles->author_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$articles->author_id->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "20", "fn0" => "");
$sSqlWrk = "";
$articles->Lookup_Selecting($articles->author_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $articles->author_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_author_id" id="s_x_author_id" value="<?php echo $articles->author_id->LookupFilterQuery() ?>">
</span>
<?php echo $articles->author_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->views->Visible) { // views ?>
	<div id="r_views" class="form-group">
		<label id="elh_articles_views" for="x_views" class="col-sm-2 control-label ewLabel"><?php echo $articles->views->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->views->CellAttributes() ?>>
<span id="el_articles_views">
<input type="text" data-table="articles" data-field="x_views" name="x_views" id="x_views" size="30" placeholder="<?php echo ew_HtmlEncode($articles->views->getPlaceHolder()) ?>" value="<?php echo $articles->views->EditValue ?>"<?php echo $articles->views->EditAttributes() ?>>
</span>
<?php echo $articles->views->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->public->Visible) { // public ?>
	<div id="r_public" class="form-group">
		<label id="elh_articles_public" class="col-sm-2 control-label ewLabel"><?php echo $articles->public->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->public->CellAttributes() ?>>
<span id="el_articles_public">
<?php ew_AppendClass($articles->public->EditAttrs["class"], "editor"); ?>
<textarea data-table="articles" data-field="x_public" name="x_public" id="x_public" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($articles->public->getPlaceHolder()) ?>"<?php echo $articles->public->EditAttributes() ?>><?php echo $articles->public->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("farticlesadd", "x_public", 35, 4, <?php echo ($articles->public->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $articles->public->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articles->public_title->Visible) { // public_title ?>
	<div id="r_public_title" class="form-group">
		<label id="elh_articles_public_title" for="x_public_title" class="col-sm-2 control-label ewLabel"><?php echo $articles->public_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articles->public_title->CellAttributes() ?>>
<span id="el_articles_public_title">
<input type="text" data-table="articles" data-field="x_public_title" name="x_public_title" id="x_public_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($articles->public_title->getPlaceHolder()) ?>" value="<?php echo $articles->public_title->EditValue ?>"<?php echo $articles->public_title->EditAttributes() ?>>
</span>
<?php echo $articles->public_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articles_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
farticlesadd.Init();
</script>
<?php
$articles_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$articles_add->Page_Terminate();
?>
