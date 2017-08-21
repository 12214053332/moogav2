<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "country__info.php" ?>
<?php include_once "employeesinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$country___edit = NULL; // Initialize page object first

class ccountry___edit extends ccountry__ {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{5101AD41-0E34-4393-9492-7002723D723A}";

	// Table name
	var $TableName = 'country__';

	// Page object name
	var $PageObjName = 'country___edit';

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

		// Table object (country__)
		if (!isset($GLOBALS["country__"]) || get_class($GLOBALS["country__"]) == "ccountry__") {
			$GLOBALS["country__"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["country__"];
		}

		// Table object (employees)
		if (!isset($GLOBALS['employees'])) $GLOBALS['employees'] = new cemployees();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'country__', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("country__list.php"));
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
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $country__;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($country__);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("country__list.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("country__list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->iso->FldIsDetailKey) {
			$this->iso->setFormValue($objForm->GetValue("x_iso"));
		}
		if (!$this->nicename->FldIsDetailKey) {
			$this->nicename->setFormValue($objForm->GetValue("x_nicename"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->iso3->FldIsDetailKey) {
			$this->iso3->setFormValue($objForm->GetValue("x_iso3"));
		}
		if (!$this->numcode->FldIsDetailKey) {
			$this->numcode->setFormValue($objForm->GetValue("x_numcode"));
		}
		if (!$this->code->FldIsDetailKey) {
			$this->code->setFormValue($objForm->GetValue("x_code"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->iso->CurrentValue = $this->iso->FormValue;
		$this->nicename->CurrentValue = $this->nicename->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->iso3->CurrentValue = $this->iso3->FormValue;
		$this->numcode->CurrentValue = $this->numcode->FormValue;
		$this->code->CurrentValue = $this->code->FormValue;
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
		$this->iso->setDbValue($rs->fields('iso'));
		$this->nicename->setDbValue($rs->fields('nicename'));
		$this->name->setDbValue($rs->fields('name'));
		$this->iso3->setDbValue($rs->fields('iso3'));
		$this->numcode->setDbValue($rs->fields('numcode'));
		$this->code->setDbValue($rs->fields('code'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->iso->DbValue = $row['iso'];
		$this->nicename->DbValue = $row['nicename'];
		$this->name->DbValue = $row['name'];
		$this->iso3->DbValue = $row['iso3'];
		$this->numcode->DbValue = $row['numcode'];
		$this->code->DbValue = $row['code'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// iso
		// nicename
		// name
		// iso3
		// numcode
		// code

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// iso
		$this->iso->ViewValue = $this->iso->CurrentValue;
		$this->iso->ViewCustomAttributes = "";

		// nicename
		$this->nicename->ViewValue = $this->nicename->CurrentValue;
		$this->nicename->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// iso3
		$this->iso3->ViewValue = $this->iso3->CurrentValue;
		$this->iso3->ViewCustomAttributes = "";

		// numcode
		$this->numcode->ViewValue = $this->numcode->CurrentValue;
		$this->numcode->ViewCustomAttributes = "";

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// iso
			$this->iso->LinkCustomAttributes = "";
			$this->iso->HrefValue = "";
			$this->iso->TooltipValue = "";

			// nicename
			$this->nicename->LinkCustomAttributes = "";
			$this->nicename->HrefValue = "";
			$this->nicename->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// iso3
			$this->iso3->LinkCustomAttributes = "";
			$this->iso3->HrefValue = "";
			$this->iso3->TooltipValue = "";

			// numcode
			$this->numcode->LinkCustomAttributes = "";
			$this->numcode->HrefValue = "";
			$this->numcode->TooltipValue = "";

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// iso
			$this->iso->EditAttrs["class"] = "form-control";
			$this->iso->EditCustomAttributes = "";
			$this->iso->EditValue = ew_HtmlEncode($this->iso->CurrentValue);
			$this->iso->PlaceHolder = ew_RemoveHtml($this->iso->FldCaption());

			// nicename
			$this->nicename->EditAttrs["class"] = "form-control";
			$this->nicename->EditCustomAttributes = "";
			$this->nicename->EditValue = ew_HtmlEncode($this->nicename->CurrentValue);
			$this->nicename->PlaceHolder = ew_RemoveHtml($this->nicename->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// iso3
			$this->iso3->EditAttrs["class"] = "form-control";
			$this->iso3->EditCustomAttributes = "";
			$this->iso3->EditValue = ew_HtmlEncode($this->iso3->CurrentValue);
			$this->iso3->PlaceHolder = ew_RemoveHtml($this->iso3->FldCaption());

			// numcode
			$this->numcode->EditAttrs["class"] = "form-control";
			$this->numcode->EditCustomAttributes = "";
			$this->numcode->EditValue = ew_HtmlEncode($this->numcode->CurrentValue);
			$this->numcode->PlaceHolder = ew_RemoveHtml($this->numcode->FldCaption());

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->CurrentValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// iso
			$this->iso->HrefValue = "";

			// nicename
			$this->nicename->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// iso3
			$this->iso3->HrefValue = "";

			// numcode
			$this->numcode->HrefValue = "";

			// code
			$this->code->HrefValue = "";
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
		if (!$this->iso->FldIsDetailKey && !is_null($this->iso->FormValue) && $this->iso->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->iso->FldCaption(), $this->iso->ReqErrMsg));
		}
		if (!$this->nicename->FldIsDetailKey && !is_null($this->nicename->FormValue) && $this->nicename->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nicename->FldCaption(), $this->nicename->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->numcode->FormValue)) {
			ew_AddMessage($gsFormError, $this->numcode->FldErrMsg());
		}
		if (!$this->code->FldIsDetailKey && !is_null($this->code->FormValue) && $this->code->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->code->FldCaption(), $this->code->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->code->FormValue)) {
			ew_AddMessage($gsFormError, $this->code->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// iso
			$this->iso->SetDbValueDef($rsnew, $this->iso->CurrentValue, "", $this->iso->ReadOnly);

			// nicename
			$this->nicename->SetDbValueDef($rsnew, $this->nicename->CurrentValue, "", $this->nicename->ReadOnly);

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", $this->name->ReadOnly);

			// iso3
			$this->iso3->SetDbValueDef($rsnew, $this->iso3->CurrentValue, NULL, $this->iso3->ReadOnly);

			// numcode
			$this->numcode->SetDbValueDef($rsnew, $this->numcode->CurrentValue, NULL, $this->numcode->ReadOnly);

			// code
			$this->code->SetDbValueDef($rsnew, $this->code->CurrentValue, 0, $this->code->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "country__list.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($country___edit)) $country___edit = new ccountry___edit();

// Page init
$country___edit->Page_Init();

// Page main
$country___edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$country___edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcountry__edit = new ew_Form("fcountry__edit", "edit");

// Validate form
fcountry__edit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_iso");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $country__->iso->FldCaption(), $country__->iso->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nicename");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $country__->nicename->FldCaption(), $country__->nicename->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $country__->name->FldCaption(), $country__->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numcode");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($country__->numcode->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_code");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $country__->code->FldCaption(), $country__->code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_code");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($country__->code->FldErrMsg()) ?>");

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
fcountry__edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcountry__edit.ValidateRequired = true;
<?php } else { ?>
fcountry__edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $country___edit->ShowPageHeader(); ?>
<?php
$country___edit->ShowMessage();
?>
<form name="fcountry__edit" id="fcountry__edit" class="<?php echo $country___edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($country___edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $country___edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="country__">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($country__->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_country___id" class="col-sm-2 control-label ewLabel"><?php echo $country__->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $country__->id->CellAttributes() ?>>
<span id="el_country___id">
<span<?php echo $country__->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $country__->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="country__" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($country__->id->CurrentValue) ?>">
<?php echo $country__->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->iso->Visible) { // iso ?>
	<div id="r_iso" class="form-group">
		<label id="elh_country___iso" for="x_iso" class="col-sm-2 control-label ewLabel"><?php echo $country__->iso->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $country__->iso->CellAttributes() ?>>
<span id="el_country___iso">
<input type="text" data-table="country__" data-field="x_iso" name="x_iso" id="x_iso" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($country__->iso->getPlaceHolder()) ?>" value="<?php echo $country__->iso->EditValue ?>"<?php echo $country__->iso->EditAttributes() ?>>
</span>
<?php echo $country__->iso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->nicename->Visible) { // nicename ?>
	<div id="r_nicename" class="form-group">
		<label id="elh_country___nicename" for="x_nicename" class="col-sm-2 control-label ewLabel"><?php echo $country__->nicename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $country__->nicename->CellAttributes() ?>>
<span id="el_country___nicename">
<input type="text" data-table="country__" data-field="x_nicename" name="x_nicename" id="x_nicename" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($country__->nicename->getPlaceHolder()) ?>" value="<?php echo $country__->nicename->EditValue ?>"<?php echo $country__->nicename->EditAttributes() ?>>
</span>
<?php echo $country__->nicename->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_country___name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $country__->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $country__->name->CellAttributes() ?>>
<span id="el_country___name">
<input type="text" data-table="country__" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($country__->name->getPlaceHolder()) ?>" value="<?php echo $country__->name->EditValue ?>"<?php echo $country__->name->EditAttributes() ?>>
</span>
<?php echo $country__->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->iso3->Visible) { // iso3 ?>
	<div id="r_iso3" class="form-group">
		<label id="elh_country___iso3" for="x_iso3" class="col-sm-2 control-label ewLabel"><?php echo $country__->iso3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $country__->iso3->CellAttributes() ?>>
<span id="el_country___iso3">
<input type="text" data-table="country__" data-field="x_iso3" name="x_iso3" id="x_iso3" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($country__->iso3->getPlaceHolder()) ?>" value="<?php echo $country__->iso3->EditValue ?>"<?php echo $country__->iso3->EditAttributes() ?>>
</span>
<?php echo $country__->iso3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->numcode->Visible) { // numcode ?>
	<div id="r_numcode" class="form-group">
		<label id="elh_country___numcode" for="x_numcode" class="col-sm-2 control-label ewLabel"><?php echo $country__->numcode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $country__->numcode->CellAttributes() ?>>
<span id="el_country___numcode">
<input type="text" data-table="country__" data-field="x_numcode" name="x_numcode" id="x_numcode" size="30" placeholder="<?php echo ew_HtmlEncode($country__->numcode->getPlaceHolder()) ?>" value="<?php echo $country__->numcode->EditValue ?>"<?php echo $country__->numcode->EditAttributes() ?>>
</span>
<?php echo $country__->numcode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($country__->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label id="elh_country___code" for="x_code" class="col-sm-2 control-label ewLabel"><?php echo $country__->code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $country__->code->CellAttributes() ?>>
<span id="el_country___code">
<input type="text" data-table="country__" data-field="x_code" name="x_code" id="x_code" size="30" placeholder="<?php echo ew_HtmlEncode($country__->code->getPlaceHolder()) ?>" value="<?php echo $country__->code->EditValue ?>"<?php echo $country__->code->EditAttributes() ?>>
</span>
<?php echo $country__->code->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $country___edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcountry__edit.Init();
</script>
<?php
$country___edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$country___edit->Page_Terminate();
?>
