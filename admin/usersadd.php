<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "employeesinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$users_add = NULL; // Initialize page object first

class cusers_add extends cusers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{5101AD41-0E34-4393-9492-7002723D723A}";

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_add';

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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Table object (employees)
		if (!isset($GLOBALS['employees'])) $GLOBALS['employees'] = new cemployees();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("userslist.php"));
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
		global $EW_EXPORT, $users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($users);
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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "usersview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->companyname->CurrentValue = NULL;
		$this->companyname->OldValue = $this->companyname->CurrentValue;
		$this->servicetime->CurrentValue = NULL;
		$this->servicetime->OldValue = $this->servicetime->CurrentValue;
		$this->country->CurrentValue = NULL;
		$this->country->OldValue = $this->country->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->skype->CurrentValue = NULL;
		$this->skype->OldValue = $this->skype->CurrentValue;
		$this->website->CurrentValue = NULL;
		$this->website->OldValue = $this->website->CurrentValue;
		$this->linkedin->CurrentValue = NULL;
		$this->linkedin->OldValue = $this->linkedin->CurrentValue;
		$this->facebook->CurrentValue = NULL;
		$this->facebook->OldValue = $this->facebook->CurrentValue;
		$this->twitter->CurrentValue = NULL;
		$this->twitter->OldValue = $this->twitter->CurrentValue;
		$this->active_code->CurrentValue = NULL;
		$this->active_code->OldValue = $this->active_code->CurrentValue;
		$this->identification->CurrentValue = NULL;
		$this->identification->OldValue = $this->identification->CurrentValue;
		$this->link_expired->CurrentValue = NULL;
		$this->link_expired->OldValue = $this->link_expired->CurrentValue;
		$this->isactive->CurrentValue = 0;
		$this->pio->CurrentValue = NULL;
		$this->pio->OldValue = $this->pio->CurrentValue;
		$this->google->CurrentValue = NULL;
		$this->google->OldValue = $this->google->CurrentValue;
		$this->instagram->CurrentValue = NULL;
		$this->instagram->OldValue = $this->instagram->CurrentValue;
		$this->account_type->CurrentValue = NULL;
		$this->account_type->OldValue = $this->account_type->CurrentValue;
		$this->logo->CurrentValue = NULL;
		$this->logo->OldValue = $this->logo->CurrentValue;
		$this->profilepic->CurrentValue = NULL;
		$this->profilepic->OldValue = $this->profilepic->CurrentValue;
		$this->mailref->CurrentValue = NULL;
		$this->mailref->OldValue = $this->mailref->CurrentValue;
		$this->deleted->CurrentValue = 0;
		$this->deletefeedback->CurrentValue = NULL;
		$this->deletefeedback->OldValue = $this->deletefeedback->CurrentValue;
		$this->account_id->CurrentValue = 1;
		$this->start_date->CurrentValue = NULL;
		$this->start_date->OldValue = $this->start_date->CurrentValue;
		$this->end_date->CurrentValue = NULL;
		$this->end_date->OldValue = $this->end_date->CurrentValue;
		$this->year_moth->CurrentValue = NULL;
		$this->year_moth->OldValue = $this->year_moth->CurrentValue;
		$this->registerdate->CurrentValue = NULL;
		$this->registerdate->OldValue = $this->registerdate->CurrentValue;
		$this->login_type->CurrentValue = NULL;
		$this->login_type->OldValue = $this->login_type->CurrentValue;
		$this->accountstatus->CurrentValue = NULL;
		$this->accountstatus->OldValue = $this->accountstatus->CurrentValue;
		$this->ispay->CurrentValue = 0;
		$this->profilelink->CurrentValue = NULL;
		$this->profilelink->OldValue = $this->profilelink->CurrentValue;
		$this->source->CurrentValue = NULL;
		$this->source->OldValue = $this->source->CurrentValue;
		$this->agree->CurrentValue = NULL;
		$this->agree->OldValue = $this->agree->CurrentValue;
		$this->balance->CurrentValue = NULL;
		$this->balance->OldValue = $this->balance->CurrentValue;
		$this->job_title->CurrentValue = NULL;
		$this->job_title->OldValue = $this->job_title->CurrentValue;
		$this->projects->CurrentValue = NULL;
		$this->projects->OldValue = $this->projects->CurrentValue;
		$this->opportunities->CurrentValue = NULL;
		$this->opportunities->OldValue = $this->opportunities->CurrentValue;
		$this->isconsaltant->CurrentValue = 0;
		$this->isagent->CurrentValue = 0;
		$this->isinvestor->CurrentValue = 0;
		$this->isbusinessman->CurrentValue = 0;
		$this->isprovider->CurrentValue = 0;
		$this->isproductowner->CurrentValue = 0;
		$this->states->CurrentValue = NULL;
		$this->states->OldValue = $this->states->CurrentValue;
		$this->cities->CurrentValue = NULL;
		$this->cities->OldValue = $this->cities->CurrentValue;
		$this->offers->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->companyname->FldIsDetailKey) {
			$this->companyname->setFormValue($objForm->GetValue("x_companyname"));
		}
		if (!$this->servicetime->FldIsDetailKey) {
			$this->servicetime->setFormValue($objForm->GetValue("x_servicetime"));
		}
		if (!$this->country->FldIsDetailKey) {
			$this->country->setFormValue($objForm->GetValue("x_country"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->skype->FldIsDetailKey) {
			$this->skype->setFormValue($objForm->GetValue("x_skype"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->linkedin->FldIsDetailKey) {
			$this->linkedin->setFormValue($objForm->GetValue("x_linkedin"));
		}
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		if (!$this->twitter->FldIsDetailKey) {
			$this->twitter->setFormValue($objForm->GetValue("x_twitter"));
		}
		if (!$this->active_code->FldIsDetailKey) {
			$this->active_code->setFormValue($objForm->GetValue("x_active_code"));
		}
		if (!$this->identification->FldIsDetailKey) {
			$this->identification->setFormValue($objForm->GetValue("x_identification"));
		}
		if (!$this->link_expired->FldIsDetailKey) {
			$this->link_expired->setFormValue($objForm->GetValue("x_link_expired"));
			$this->link_expired->CurrentValue = ew_UnFormatDateTime($this->link_expired->CurrentValue, 5);
		}
		if (!$this->isactive->FldIsDetailKey) {
			$this->isactive->setFormValue($objForm->GetValue("x_isactive"));
		}
		if (!$this->pio->FldIsDetailKey) {
			$this->pio->setFormValue($objForm->GetValue("x_pio"));
		}
		if (!$this->google->FldIsDetailKey) {
			$this->google->setFormValue($objForm->GetValue("x_google"));
		}
		if (!$this->instagram->FldIsDetailKey) {
			$this->instagram->setFormValue($objForm->GetValue("x_instagram"));
		}
		if (!$this->account_type->FldIsDetailKey) {
			$this->account_type->setFormValue($objForm->GetValue("x_account_type"));
		}
		if (!$this->logo->FldIsDetailKey) {
			$this->logo->setFormValue($objForm->GetValue("x_logo"));
		}
		if (!$this->profilepic->FldIsDetailKey) {
			$this->profilepic->setFormValue($objForm->GetValue("x_profilepic"));
		}
		if (!$this->mailref->FldIsDetailKey) {
			$this->mailref->setFormValue($objForm->GetValue("x_mailref"));
		}
		if (!$this->deleted->FldIsDetailKey) {
			$this->deleted->setFormValue($objForm->GetValue("x_deleted"));
		}
		if (!$this->deletefeedback->FldIsDetailKey) {
			$this->deletefeedback->setFormValue($objForm->GetValue("x_deletefeedback"));
		}
		if (!$this->account_id->FldIsDetailKey) {
			$this->account_id->setFormValue($objForm->GetValue("x_account_id"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 5);
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
			$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 5);
		}
		if (!$this->year_moth->FldIsDetailKey) {
			$this->year_moth->setFormValue($objForm->GetValue("x_year_moth"));
		}
		if (!$this->registerdate->FldIsDetailKey) {
			$this->registerdate->setFormValue($objForm->GetValue("x_registerdate"));
			$this->registerdate->CurrentValue = ew_UnFormatDateTime($this->registerdate->CurrentValue, 5);
		}
		if (!$this->login_type->FldIsDetailKey) {
			$this->login_type->setFormValue($objForm->GetValue("x_login_type"));
		}
		if (!$this->accountstatus->FldIsDetailKey) {
			$this->accountstatus->setFormValue($objForm->GetValue("x_accountstatus"));
		}
		if (!$this->ispay->FldIsDetailKey) {
			$this->ispay->setFormValue($objForm->GetValue("x_ispay"));
		}
		if (!$this->profilelink->FldIsDetailKey) {
			$this->profilelink->setFormValue($objForm->GetValue("x_profilelink"));
		}
		if (!$this->source->FldIsDetailKey) {
			$this->source->setFormValue($objForm->GetValue("x_source"));
		}
		if (!$this->agree->FldIsDetailKey) {
			$this->agree->setFormValue($objForm->GetValue("x_agree"));
		}
		if (!$this->balance->FldIsDetailKey) {
			$this->balance->setFormValue($objForm->GetValue("x_balance"));
		}
		if (!$this->job_title->FldIsDetailKey) {
			$this->job_title->setFormValue($objForm->GetValue("x_job_title"));
		}
		if (!$this->projects->FldIsDetailKey) {
			$this->projects->setFormValue($objForm->GetValue("x_projects"));
		}
		if (!$this->opportunities->FldIsDetailKey) {
			$this->opportunities->setFormValue($objForm->GetValue("x_opportunities"));
		}
		if (!$this->isconsaltant->FldIsDetailKey) {
			$this->isconsaltant->setFormValue($objForm->GetValue("x_isconsaltant"));
		}
		if (!$this->isagent->FldIsDetailKey) {
			$this->isagent->setFormValue($objForm->GetValue("x_isagent"));
		}
		if (!$this->isinvestor->FldIsDetailKey) {
			$this->isinvestor->setFormValue($objForm->GetValue("x_isinvestor"));
		}
		if (!$this->isbusinessman->FldIsDetailKey) {
			$this->isbusinessman->setFormValue($objForm->GetValue("x_isbusinessman"));
		}
		if (!$this->isprovider->FldIsDetailKey) {
			$this->isprovider->setFormValue($objForm->GetValue("x_isprovider"));
		}
		if (!$this->isproductowner->FldIsDetailKey) {
			$this->isproductowner->setFormValue($objForm->GetValue("x_isproductowner"));
		}
		if (!$this->states->FldIsDetailKey) {
			$this->states->setFormValue($objForm->GetValue("x_states"));
		}
		if (!$this->cities->FldIsDetailKey) {
			$this->cities->setFormValue($objForm->GetValue("x_cities"));
		}
		if (!$this->offers->FldIsDetailKey) {
			$this->offers->setFormValue($objForm->GetValue("x_offers"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->name->CurrentValue = $this->name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->companyname->CurrentValue = $this->companyname->FormValue;
		$this->servicetime->CurrentValue = $this->servicetime->FormValue;
		$this->country->CurrentValue = $this->country->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->skype->CurrentValue = $this->skype->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->linkedin->CurrentValue = $this->linkedin->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->twitter->CurrentValue = $this->twitter->FormValue;
		$this->active_code->CurrentValue = $this->active_code->FormValue;
		$this->identification->CurrentValue = $this->identification->FormValue;
		$this->link_expired->CurrentValue = $this->link_expired->FormValue;
		$this->link_expired->CurrentValue = ew_UnFormatDateTime($this->link_expired->CurrentValue, 5);
		$this->isactive->CurrentValue = $this->isactive->FormValue;
		$this->pio->CurrentValue = $this->pio->FormValue;
		$this->google->CurrentValue = $this->google->FormValue;
		$this->instagram->CurrentValue = $this->instagram->FormValue;
		$this->account_type->CurrentValue = $this->account_type->FormValue;
		$this->logo->CurrentValue = $this->logo->FormValue;
		$this->profilepic->CurrentValue = $this->profilepic->FormValue;
		$this->mailref->CurrentValue = $this->mailref->FormValue;
		$this->deleted->CurrentValue = $this->deleted->FormValue;
		$this->deletefeedback->CurrentValue = $this->deletefeedback->FormValue;
		$this->account_id->CurrentValue = $this->account_id->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 5);
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 5);
		$this->year_moth->CurrentValue = $this->year_moth->FormValue;
		$this->registerdate->CurrentValue = $this->registerdate->FormValue;
		$this->registerdate->CurrentValue = ew_UnFormatDateTime($this->registerdate->CurrentValue, 5);
		$this->login_type->CurrentValue = $this->login_type->FormValue;
		$this->accountstatus->CurrentValue = $this->accountstatus->FormValue;
		$this->ispay->CurrentValue = $this->ispay->FormValue;
		$this->profilelink->CurrentValue = $this->profilelink->FormValue;
		$this->source->CurrentValue = $this->source->FormValue;
		$this->agree->CurrentValue = $this->agree->FormValue;
		$this->balance->CurrentValue = $this->balance->FormValue;
		$this->job_title->CurrentValue = $this->job_title->FormValue;
		$this->projects->CurrentValue = $this->projects->FormValue;
		$this->opportunities->CurrentValue = $this->opportunities->FormValue;
		$this->isconsaltant->CurrentValue = $this->isconsaltant->FormValue;
		$this->isagent->CurrentValue = $this->isagent->FormValue;
		$this->isinvestor->CurrentValue = $this->isinvestor->FormValue;
		$this->isbusinessman->CurrentValue = $this->isbusinessman->FormValue;
		$this->isprovider->CurrentValue = $this->isprovider->FormValue;
		$this->isproductowner->CurrentValue = $this->isproductowner->FormValue;
		$this->states->CurrentValue = $this->states->FormValue;
		$this->cities->CurrentValue = $this->cities->FormValue;
		$this->offers->CurrentValue = $this->offers->FormValue;
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
		$this->name->setDbValue($rs->fields('name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->companyname->setDbValue($rs->fields('companyname'));
		$this->servicetime->setDbValue($rs->fields('servicetime'));
		$this->country->setDbValue($rs->fields('country'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->skype->setDbValue($rs->fields('skype'));
		$this->website->setDbValue($rs->fields('website'));
		$this->linkedin->setDbValue($rs->fields('linkedin'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->active_code->setDbValue($rs->fields('active_code'));
		$this->identification->setDbValue($rs->fields('identification'));
		$this->link_expired->setDbValue($rs->fields('link_expired'));
		$this->isactive->setDbValue($rs->fields('isactive'));
		$this->pio->setDbValue($rs->fields('pio'));
		$this->google->setDbValue($rs->fields('google'));
		$this->instagram->setDbValue($rs->fields('instagram'));
		$this->account_type->setDbValue($rs->fields('account_type'));
		$this->logo->setDbValue($rs->fields('logo'));
		$this->profilepic->setDbValue($rs->fields('profilepic'));
		$this->mailref->setDbValue($rs->fields('mailref'));
		$this->deleted->setDbValue($rs->fields('deleted'));
		$this->deletefeedback->setDbValue($rs->fields('deletefeedback'));
		$this->account_id->setDbValue($rs->fields('account_id'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->year_moth->setDbValue($rs->fields('year_moth'));
		$this->registerdate->setDbValue($rs->fields('registerdate'));
		$this->login_type->setDbValue($rs->fields('login_type'));
		$this->accountstatus->setDbValue($rs->fields('accountstatus'));
		$this->ispay->setDbValue($rs->fields('ispay'));
		$this->profilelink->setDbValue($rs->fields('profilelink'));
		$this->source->setDbValue($rs->fields('source'));
		$this->agree->setDbValue($rs->fields('agree'));
		$this->balance->setDbValue($rs->fields('balance'));
		$this->job_title->setDbValue($rs->fields('job_title'));
		$this->projects->setDbValue($rs->fields('projects'));
		$this->opportunities->setDbValue($rs->fields('opportunities'));
		$this->isconsaltant->setDbValue($rs->fields('isconsaltant'));
		$this->isagent->setDbValue($rs->fields('isagent'));
		$this->isinvestor->setDbValue($rs->fields('isinvestor'));
		$this->isbusinessman->setDbValue($rs->fields('isbusinessman'));
		$this->isprovider->setDbValue($rs->fields('isprovider'));
		$this->isproductowner->setDbValue($rs->fields('isproductowner'));
		$this->states->setDbValue($rs->fields('states'));
		$this->cities->setDbValue($rs->fields('cities'));
		$this->offers->setDbValue($rs->fields('offers'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->companyname->DbValue = $row['companyname'];
		$this->servicetime->DbValue = $row['servicetime'];
		$this->country->DbValue = $row['country'];
		$this->phone->DbValue = $row['phone'];
		$this->skype->DbValue = $row['skype'];
		$this->website->DbValue = $row['website'];
		$this->linkedin->DbValue = $row['linkedin'];
		$this->facebook->DbValue = $row['facebook'];
		$this->twitter->DbValue = $row['twitter'];
		$this->active_code->DbValue = $row['active_code'];
		$this->identification->DbValue = $row['identification'];
		$this->link_expired->DbValue = $row['link_expired'];
		$this->isactive->DbValue = $row['isactive'];
		$this->pio->DbValue = $row['pio'];
		$this->google->DbValue = $row['google'];
		$this->instagram->DbValue = $row['instagram'];
		$this->account_type->DbValue = $row['account_type'];
		$this->logo->DbValue = $row['logo'];
		$this->profilepic->DbValue = $row['profilepic'];
		$this->mailref->DbValue = $row['mailref'];
		$this->deleted->DbValue = $row['deleted'];
		$this->deletefeedback->DbValue = $row['deletefeedback'];
		$this->account_id->DbValue = $row['account_id'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->year_moth->DbValue = $row['year_moth'];
		$this->registerdate->DbValue = $row['registerdate'];
		$this->login_type->DbValue = $row['login_type'];
		$this->accountstatus->DbValue = $row['accountstatus'];
		$this->ispay->DbValue = $row['ispay'];
		$this->profilelink->DbValue = $row['profilelink'];
		$this->source->DbValue = $row['source'];
		$this->agree->DbValue = $row['agree'];
		$this->balance->DbValue = $row['balance'];
		$this->job_title->DbValue = $row['job_title'];
		$this->projects->DbValue = $row['projects'];
		$this->opportunities->DbValue = $row['opportunities'];
		$this->isconsaltant->DbValue = $row['isconsaltant'];
		$this->isagent->DbValue = $row['isagent'];
		$this->isinvestor->DbValue = $row['isinvestor'];
		$this->isbusinessman->DbValue = $row['isbusinessman'];
		$this->isprovider->DbValue = $row['isprovider'];
		$this->isproductowner->DbValue = $row['isproductowner'];
		$this->states->DbValue = $row['states'];
		$this->cities->DbValue = $row['cities'];
		$this->offers->DbValue = $row['offers'];
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
		// name
		// email
		// password
		// companyname
		// servicetime
		// country
		// phone
		// skype
		// website
		// linkedin
		// facebook
		// twitter
		// active_code
		// identification
		// link_expired
		// isactive
		// pio
		// google
		// instagram
		// account_type
		// logo
		// profilepic
		// mailref
		// deleted
		// deletefeedback
		// account_id
		// start_date
		// end_date
		// year_moth
		// registerdate
		// login_type
		// accountstatus
		// ispay
		// profilelink
		// source
		// agree
		// balance
		// job_title
		// projects
		// opportunities
		// isconsaltant
		// isagent
		// isinvestor
		// isbusinessman
		// isprovider
		// isproductowner
		// states
		// cities
		// offers

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// companyname
		$this->companyname->ViewValue = $this->companyname->CurrentValue;
		$this->companyname->ViewCustomAttributes = "";

		// servicetime
		$this->servicetime->ViewValue = $this->servicetime->CurrentValue;
		$this->servicetime->ViewCustomAttributes = "";

		// country
		$this->country->ViewValue = $this->country->CurrentValue;
		$this->country->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// skype
		$this->skype->ViewValue = $this->skype->CurrentValue;
		$this->skype->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// linkedin
		$this->linkedin->ViewValue = $this->linkedin->CurrentValue;
		$this->linkedin->ViewCustomAttributes = "";

		// facebook
		$this->facebook->ViewValue = $this->facebook->CurrentValue;
		$this->facebook->ViewCustomAttributes = "";

		// twitter
		$this->twitter->ViewValue = $this->twitter->CurrentValue;
		$this->twitter->ViewCustomAttributes = "";

		// active_code
		$this->active_code->ViewValue = $this->active_code->CurrentValue;
		$this->active_code->ViewCustomAttributes = "";

		// identification
		$this->identification->ViewValue = $this->identification->CurrentValue;
		$this->identification->ViewCustomAttributes = "";

		// link_expired
		$this->link_expired->ViewValue = $this->link_expired->CurrentValue;
		$this->link_expired->ViewValue = ew_FormatDateTime($this->link_expired->ViewValue, 5);
		$this->link_expired->ViewCustomAttributes = "";

		// isactive
		$this->isactive->ViewValue = $this->isactive->CurrentValue;
		$this->isactive->ViewCustomAttributes = "";

		// pio
		$this->pio->ViewValue = $this->pio->CurrentValue;
		$this->pio->ViewCustomAttributes = "";

		// google
		$this->google->ViewValue = $this->google->CurrentValue;
		$this->google->ViewCustomAttributes = "";

		// instagram
		$this->instagram->ViewValue = $this->instagram->CurrentValue;
		$this->instagram->ViewCustomAttributes = "";

		// account_type
		$this->account_type->ViewValue = $this->account_type->CurrentValue;
		$this->account_type->ViewCustomAttributes = "";

		// logo
		$this->logo->ViewValue = $this->logo->CurrentValue;
		$this->logo->ViewCustomAttributes = "";

		// profilepic
		$this->profilepic->ViewValue = $this->profilepic->CurrentValue;
		$this->profilepic->ViewCustomAttributes = "";

		// mailref
		$this->mailref->ViewValue = $this->mailref->CurrentValue;
		$this->mailref->ViewCustomAttributes = "";

		// deleted
		$this->deleted->ViewValue = $this->deleted->CurrentValue;
		$this->deleted->ViewCustomAttributes = "";

		// deletefeedback
		$this->deletefeedback->ViewValue = $this->deletefeedback->CurrentValue;
		$this->deletefeedback->ViewCustomAttributes = "";

		// account_id
		$this->account_id->ViewValue = $this->account_id->CurrentValue;
		$this->account_id->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 5);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 5);
		$this->end_date->ViewCustomAttributes = "";

		// year_moth
		$this->year_moth->ViewValue = $this->year_moth->CurrentValue;
		$this->year_moth->ViewCustomAttributes = "";

		// registerdate
		$this->registerdate->ViewValue = $this->registerdate->CurrentValue;
		$this->registerdate->ViewValue = ew_FormatDateTime($this->registerdate->ViewValue, 5);
		$this->registerdate->ViewCustomAttributes = "";

		// login_type
		$this->login_type->ViewValue = $this->login_type->CurrentValue;
		$this->login_type->ViewCustomAttributes = "";

		// accountstatus
		$this->accountstatus->ViewValue = $this->accountstatus->CurrentValue;
		$this->accountstatus->ViewCustomAttributes = "";

		// ispay
		$this->ispay->ViewValue = $this->ispay->CurrentValue;
		$this->ispay->ViewCustomAttributes = "";

		// profilelink
		$this->profilelink->ViewValue = $this->profilelink->CurrentValue;
		$this->profilelink->ViewCustomAttributes = "";

		// source
		$this->source->ViewValue = $this->source->CurrentValue;
		$this->source->ViewCustomAttributes = "";

		// agree
		$this->agree->ViewValue = $this->agree->CurrentValue;
		$this->agree->ViewCustomAttributes = "";

		// balance
		$this->balance->ViewValue = $this->balance->CurrentValue;
		$this->balance->ViewCustomAttributes = "";

		// job_title
		$this->job_title->ViewValue = $this->job_title->CurrentValue;
		$this->job_title->ViewCustomAttributes = "";

		// projects
		$this->projects->ViewValue = $this->projects->CurrentValue;
		$this->projects->ViewCustomAttributes = "";

		// opportunities
		$this->opportunities->ViewValue = $this->opportunities->CurrentValue;
		$this->opportunities->ViewCustomAttributes = "";

		// isconsaltant
		$this->isconsaltant->ViewCustomAttributes = "";

		// isagent
		$this->isagent->ViewCustomAttributes = "";

		// isinvestor
		$this->isinvestor->ViewCustomAttributes = "";

		// isbusinessman
		$this->isbusinessman->ViewCustomAttributes = "";

		// isprovider
		$this->isprovider->ViewCustomAttributes = "";

		// isproductowner
		$this->isproductowner->ViewCustomAttributes = "";

		// states
		$this->states->ViewValue = $this->states->CurrentValue;
		$this->states->ViewCustomAttributes = "";

		// cities
		$this->cities->ViewValue = $this->cities->CurrentValue;
		$this->cities->ViewCustomAttributes = "";

		// offers
		$this->offers->ViewValue = $this->offers->CurrentValue;
		$this->offers->ViewCustomAttributes = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// companyname
			$this->companyname->LinkCustomAttributes = "";
			$this->companyname->HrefValue = "";
			$this->companyname->TooltipValue = "";

			// servicetime
			$this->servicetime->LinkCustomAttributes = "";
			$this->servicetime->HrefValue = "";
			$this->servicetime->TooltipValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";
			$this->country->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// skype
			$this->skype->LinkCustomAttributes = "";
			$this->skype->HrefValue = "";
			$this->skype->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// linkedin
			$this->linkedin->LinkCustomAttributes = "";
			$this->linkedin->HrefValue = "";
			$this->linkedin->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";
			$this->facebook->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";
			$this->twitter->TooltipValue = "";

			// active_code
			$this->active_code->LinkCustomAttributes = "";
			$this->active_code->HrefValue = "";
			$this->active_code->TooltipValue = "";

			// identification
			$this->identification->LinkCustomAttributes = "";
			$this->identification->HrefValue = "";
			$this->identification->TooltipValue = "";

			// link_expired
			$this->link_expired->LinkCustomAttributes = "";
			$this->link_expired->HrefValue = "";
			$this->link_expired->TooltipValue = "";

			// isactive
			$this->isactive->LinkCustomAttributes = "";
			$this->isactive->HrefValue = "";
			$this->isactive->TooltipValue = "";

			// pio
			$this->pio->LinkCustomAttributes = "";
			$this->pio->HrefValue = "";
			$this->pio->TooltipValue = "";

			// google
			$this->google->LinkCustomAttributes = "";
			$this->google->HrefValue = "";
			$this->google->TooltipValue = "";

			// instagram
			$this->instagram->LinkCustomAttributes = "";
			$this->instagram->HrefValue = "";
			$this->instagram->TooltipValue = "";

			// account_type
			$this->account_type->LinkCustomAttributes = "";
			$this->account_type->HrefValue = "";
			$this->account_type->TooltipValue = "";

			// logo
			$this->logo->LinkCustomAttributes = "";
			$this->logo->HrefValue = "";
			$this->logo->TooltipValue = "";

			// profilepic
			$this->profilepic->LinkCustomAttributes = "";
			$this->profilepic->HrefValue = "";
			$this->profilepic->TooltipValue = "";

			// mailref
			$this->mailref->LinkCustomAttributes = "";
			$this->mailref->HrefValue = "";
			$this->mailref->TooltipValue = "";

			// deleted
			$this->deleted->LinkCustomAttributes = "";
			$this->deleted->HrefValue = "";
			$this->deleted->TooltipValue = "";

			// deletefeedback
			$this->deletefeedback->LinkCustomAttributes = "";
			$this->deletefeedback->HrefValue = "";
			$this->deletefeedback->TooltipValue = "";

			// account_id
			$this->account_id->LinkCustomAttributes = "";
			$this->account_id->HrefValue = "";
			$this->account_id->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// year_moth
			$this->year_moth->LinkCustomAttributes = "";
			$this->year_moth->HrefValue = "";
			$this->year_moth->TooltipValue = "";

			// registerdate
			$this->registerdate->LinkCustomAttributes = "";
			$this->registerdate->HrefValue = "";
			$this->registerdate->TooltipValue = "";

			// login_type
			$this->login_type->LinkCustomAttributes = "";
			$this->login_type->HrefValue = "";
			$this->login_type->TooltipValue = "";

			// accountstatus
			$this->accountstatus->LinkCustomAttributes = "";
			$this->accountstatus->HrefValue = "";
			$this->accountstatus->TooltipValue = "";

			// ispay
			$this->ispay->LinkCustomAttributes = "";
			$this->ispay->HrefValue = "";
			$this->ispay->TooltipValue = "";

			// profilelink
			$this->profilelink->LinkCustomAttributes = "";
			$this->profilelink->HrefValue = "";
			$this->profilelink->TooltipValue = "";

			// source
			$this->source->LinkCustomAttributes = "";
			$this->source->HrefValue = "";
			$this->source->TooltipValue = "";

			// agree
			$this->agree->LinkCustomAttributes = "";
			$this->agree->HrefValue = "";
			$this->agree->TooltipValue = "";

			// balance
			$this->balance->LinkCustomAttributes = "";
			$this->balance->HrefValue = "";
			$this->balance->TooltipValue = "";

			// job_title
			$this->job_title->LinkCustomAttributes = "";
			$this->job_title->HrefValue = "";
			$this->job_title->TooltipValue = "";

			// projects
			$this->projects->LinkCustomAttributes = "";
			$this->projects->HrefValue = "";
			$this->projects->TooltipValue = "";

			// opportunities
			$this->opportunities->LinkCustomAttributes = "";
			$this->opportunities->HrefValue = "";
			$this->opportunities->TooltipValue = "";

			// isconsaltant
			$this->isconsaltant->LinkCustomAttributes = "";
			$this->isconsaltant->HrefValue = "";
			$this->isconsaltant->TooltipValue = "";

			// isagent
			$this->isagent->LinkCustomAttributes = "";
			$this->isagent->HrefValue = "";
			$this->isagent->TooltipValue = "";

			// isinvestor
			$this->isinvestor->LinkCustomAttributes = "";
			$this->isinvestor->HrefValue = "";
			$this->isinvestor->TooltipValue = "";

			// isbusinessman
			$this->isbusinessman->LinkCustomAttributes = "";
			$this->isbusinessman->HrefValue = "";
			$this->isbusinessman->TooltipValue = "";

			// isprovider
			$this->isprovider->LinkCustomAttributes = "";
			$this->isprovider->HrefValue = "";
			$this->isprovider->TooltipValue = "";

			// isproductowner
			$this->isproductowner->LinkCustomAttributes = "";
			$this->isproductowner->HrefValue = "";
			$this->isproductowner->TooltipValue = "";

			// states
			$this->states->LinkCustomAttributes = "";
			$this->states->HrefValue = "";
			$this->states->TooltipValue = "";

			// cities
			$this->cities->LinkCustomAttributes = "";
			$this->cities->HrefValue = "";
			$this->cities->TooltipValue = "";

			// offers
			$this->offers->LinkCustomAttributes = "";
			$this->offers->HrefValue = "";
			$this->offers->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// companyname
			$this->companyname->EditAttrs["class"] = "form-control";
			$this->companyname->EditCustomAttributes = "";
			$this->companyname->EditValue = ew_HtmlEncode($this->companyname->CurrentValue);
			$this->companyname->PlaceHolder = ew_RemoveHtml($this->companyname->FldCaption());

			// servicetime
			$this->servicetime->EditAttrs["class"] = "form-control";
			$this->servicetime->EditCustomAttributes = "";
			$this->servicetime->EditValue = ew_HtmlEncode($this->servicetime->CurrentValue);
			$this->servicetime->PlaceHolder = ew_RemoveHtml($this->servicetime->FldCaption());

			// country
			$this->country->EditAttrs["class"] = "form-control";
			$this->country->EditCustomAttributes = "";
			$this->country->EditValue = ew_HtmlEncode($this->country->CurrentValue);
			$this->country->PlaceHolder = ew_RemoveHtml($this->country->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// skype
			$this->skype->EditAttrs["class"] = "form-control";
			$this->skype->EditCustomAttributes = "";
			$this->skype->EditValue = ew_HtmlEncode($this->skype->CurrentValue);
			$this->skype->PlaceHolder = ew_RemoveHtml($this->skype->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// linkedin
			$this->linkedin->EditAttrs["class"] = "form-control";
			$this->linkedin->EditCustomAttributes = "";
			$this->linkedin->EditValue = ew_HtmlEncode($this->linkedin->CurrentValue);
			$this->linkedin->PlaceHolder = ew_RemoveHtml($this->linkedin->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// twitter
			$this->twitter->EditAttrs["class"] = "form-control";
			$this->twitter->EditCustomAttributes = "";
			$this->twitter->EditValue = ew_HtmlEncode($this->twitter->CurrentValue);
			$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

			// active_code
			$this->active_code->EditAttrs["class"] = "form-control";
			$this->active_code->EditCustomAttributes = "";
			$this->active_code->EditValue = ew_HtmlEncode($this->active_code->CurrentValue);
			$this->active_code->PlaceHolder = ew_RemoveHtml($this->active_code->FldCaption());

			// identification
			$this->identification->EditAttrs["class"] = "form-control";
			$this->identification->EditCustomAttributes = "";
			$this->identification->EditValue = ew_HtmlEncode($this->identification->CurrentValue);
			$this->identification->PlaceHolder = ew_RemoveHtml($this->identification->FldCaption());

			// link_expired
			$this->link_expired->EditAttrs["class"] = "form-control";
			$this->link_expired->EditCustomAttributes = "";
			$this->link_expired->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->link_expired->CurrentValue, 5));
			$this->link_expired->PlaceHolder = ew_RemoveHtml($this->link_expired->FldCaption());

			// isactive
			$this->isactive->EditAttrs["class"] = "form-control";
			$this->isactive->EditCustomAttributes = "";
			$this->isactive->CurrentValue = 0;

			// pio
			$this->pio->EditAttrs["class"] = "form-control";
			$this->pio->EditCustomAttributes = "";
			$this->pio->EditValue = ew_HtmlEncode($this->pio->CurrentValue);
			$this->pio->PlaceHolder = ew_RemoveHtml($this->pio->FldCaption());

			// google
			$this->google->EditAttrs["class"] = "form-control";
			$this->google->EditCustomAttributes = "";
			$this->google->EditValue = ew_HtmlEncode($this->google->CurrentValue);
			$this->google->PlaceHolder = ew_RemoveHtml($this->google->FldCaption());

			// instagram
			$this->instagram->EditAttrs["class"] = "form-control";
			$this->instagram->EditCustomAttributes = "";
			$this->instagram->EditValue = ew_HtmlEncode($this->instagram->CurrentValue);
			$this->instagram->PlaceHolder = ew_RemoveHtml($this->instagram->FldCaption());

			// account_type
			$this->account_type->EditAttrs["class"] = "form-control";
			$this->account_type->EditCustomAttributes = "";
			$this->account_type->EditValue = ew_HtmlEncode($this->account_type->CurrentValue);
			$this->account_type->PlaceHolder = ew_RemoveHtml($this->account_type->FldCaption());

			// logo
			$this->logo->EditAttrs["class"] = "form-control";
			$this->logo->EditCustomAttributes = "";
			$this->logo->EditValue = ew_HtmlEncode($this->logo->CurrentValue);
			$this->logo->PlaceHolder = ew_RemoveHtml($this->logo->FldCaption());

			// profilepic
			$this->profilepic->EditAttrs["class"] = "form-control";
			$this->profilepic->EditCustomAttributes = "";
			$this->profilepic->EditValue = ew_HtmlEncode($this->profilepic->CurrentValue);
			$this->profilepic->PlaceHolder = ew_RemoveHtml($this->profilepic->FldCaption());

			// mailref
			$this->mailref->EditAttrs["class"] = "form-control";
			$this->mailref->EditCustomAttributes = "";
			$this->mailref->EditValue = ew_HtmlEncode($this->mailref->CurrentValue);
			$this->mailref->PlaceHolder = ew_RemoveHtml($this->mailref->FldCaption());

			// deleted
			$this->deleted->EditAttrs["class"] = "form-control";
			$this->deleted->EditCustomAttributes = "";
			$this->deleted->CurrentValue = 0;

			// deletefeedback
			$this->deletefeedback->EditAttrs["class"] = "form-control";
			$this->deletefeedback->EditCustomAttributes = "";
			$this->deletefeedback->EditValue = ew_HtmlEncode($this->deletefeedback->CurrentValue);
			$this->deletefeedback->PlaceHolder = ew_RemoveHtml($this->deletefeedback->FldCaption());

			// account_id
			$this->account_id->EditAttrs["class"] = "form-control";
			$this->account_id->EditCustomAttributes = "";
			$this->account_id->CurrentValue = 1;

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 5));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->end_date->CurrentValue, 5));
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// year_moth
			$this->year_moth->EditAttrs["class"] = "form-control";
			$this->year_moth->EditCustomAttributes = "";
			$this->year_moth->EditValue = ew_HtmlEncode($this->year_moth->CurrentValue);
			$this->year_moth->PlaceHolder = ew_RemoveHtml($this->year_moth->FldCaption());

			// registerdate
			$this->registerdate->EditAttrs["class"] = "form-control";
			$this->registerdate->EditCustomAttributes = "";
			$this->registerdate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->registerdate->CurrentValue, 5));
			$this->registerdate->PlaceHolder = ew_RemoveHtml($this->registerdate->FldCaption());

			// login_type
			$this->login_type->EditAttrs["class"] = "form-control";
			$this->login_type->EditCustomAttributes = "";
			$this->login_type->EditValue = ew_HtmlEncode($this->login_type->CurrentValue);
			$this->login_type->PlaceHolder = ew_RemoveHtml($this->login_type->FldCaption());

			// accountstatus
			$this->accountstatus->EditAttrs["class"] = "form-control";
			$this->accountstatus->EditCustomAttributes = "";
			$this->accountstatus->EditValue = ew_HtmlEncode($this->accountstatus->CurrentValue);
			$this->accountstatus->PlaceHolder = ew_RemoveHtml($this->accountstatus->FldCaption());

			// ispay
			$this->ispay->EditAttrs["class"] = "form-control";
			$this->ispay->EditCustomAttributes = "";
			$this->ispay->CurrentValue = 0;

			// profilelink
			$this->profilelink->EditAttrs["class"] = "form-control";
			$this->profilelink->EditCustomAttributes = "";
			$this->profilelink->EditValue = ew_HtmlEncode($this->profilelink->CurrentValue);
			$this->profilelink->PlaceHolder = ew_RemoveHtml($this->profilelink->FldCaption());

			// source
			$this->source->EditAttrs["class"] = "form-control";
			$this->source->EditCustomAttributes = "";
			$this->source->EditValue = ew_HtmlEncode($this->source->CurrentValue);
			$this->source->PlaceHolder = ew_RemoveHtml($this->source->FldCaption());

			// agree
			$this->agree->EditAttrs["class"] = "form-control";
			$this->agree->EditCustomAttributes = "";
			$this->agree->EditValue = ew_HtmlEncode($this->agree->CurrentValue);
			$this->agree->PlaceHolder = ew_RemoveHtml($this->agree->FldCaption());

			// balance
			$this->balance->EditAttrs["class"] = "form-control";
			$this->balance->EditCustomAttributes = "";
			$this->balance->EditValue = ew_HtmlEncode($this->balance->CurrentValue);
			$this->balance->PlaceHolder = ew_RemoveHtml($this->balance->FldCaption());

			// job_title
			$this->job_title->EditAttrs["class"] = "form-control";
			$this->job_title->EditCustomAttributes = "";
			$this->job_title->EditValue = ew_HtmlEncode($this->job_title->CurrentValue);
			$this->job_title->PlaceHolder = ew_RemoveHtml($this->job_title->FldCaption());

			// projects
			$this->projects->EditAttrs["class"] = "form-control";
			$this->projects->EditCustomAttributes = "";
			$this->projects->EditValue = ew_HtmlEncode($this->projects->CurrentValue);
			$this->projects->PlaceHolder = ew_RemoveHtml($this->projects->FldCaption());

			// opportunities
			$this->opportunities->EditAttrs["class"] = "form-control";
			$this->opportunities->EditCustomAttributes = "";
			$this->opportunities->EditValue = ew_HtmlEncode($this->opportunities->CurrentValue);
			$this->opportunities->PlaceHolder = ew_RemoveHtml($this->opportunities->FldCaption());

			// isconsaltant
			$this->isconsaltant->EditCustomAttributes = "";

			// isagent
			$this->isagent->EditCustomAttributes = "";

			// isinvestor
			$this->isinvestor->EditCustomAttributes = "";

			// isbusinessman
			$this->isbusinessman->EditCustomAttributes = "";

			// isprovider
			$this->isprovider->EditCustomAttributes = "";

			// isproductowner
			$this->isproductowner->EditCustomAttributes = "";

			// states
			$this->states->EditAttrs["class"] = "form-control";
			$this->states->EditCustomAttributes = "";
			$this->states->EditValue = ew_HtmlEncode($this->states->CurrentValue);
			$this->states->PlaceHolder = ew_RemoveHtml($this->states->FldCaption());

			// cities
			$this->cities->EditAttrs["class"] = "form-control";
			$this->cities->EditCustomAttributes = "";
			$this->cities->EditValue = ew_HtmlEncode($this->cities->CurrentValue);
			$this->cities->PlaceHolder = ew_RemoveHtml($this->cities->FldCaption());

			// offers
			$this->offers->EditAttrs["class"] = "form-control";
			$this->offers->EditCustomAttributes = "";
			$this->offers->EditValue = ew_HtmlEncode($this->offers->CurrentValue);
			$this->offers->PlaceHolder = ew_RemoveHtml($this->offers->FldCaption());

			// Edit refer script
			// name

			$this->name->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// password
			$this->password->HrefValue = "";

			// companyname
			$this->companyname->HrefValue = "";

			// servicetime
			$this->servicetime->HrefValue = "";

			// country
			$this->country->HrefValue = "";

			// phone
			$this->phone->HrefValue = "";

			// skype
			$this->skype->HrefValue = "";

			// website
			$this->website->HrefValue = "";

			// linkedin
			$this->linkedin->HrefValue = "";

			// facebook
			$this->facebook->HrefValue = "";

			// twitter
			$this->twitter->HrefValue = "";

			// active_code
			$this->active_code->HrefValue = "";

			// identification
			$this->identification->HrefValue = "";

			// link_expired
			$this->link_expired->HrefValue = "";

			// isactive
			$this->isactive->HrefValue = "";

			// pio
			$this->pio->HrefValue = "";

			// google
			$this->google->HrefValue = "";

			// instagram
			$this->instagram->HrefValue = "";

			// account_type
			$this->account_type->HrefValue = "";

			// logo
			$this->logo->HrefValue = "";

			// profilepic
			$this->profilepic->HrefValue = "";

			// mailref
			$this->mailref->HrefValue = "";

			// deleted
			$this->deleted->HrefValue = "";

			// deletefeedback
			$this->deletefeedback->HrefValue = "";

			// account_id
			$this->account_id->HrefValue = "";

			// start_date
			$this->start_date->HrefValue = "";

			// end_date
			$this->end_date->HrefValue = "";

			// year_moth
			$this->year_moth->HrefValue = "";

			// registerdate
			$this->registerdate->HrefValue = "";

			// login_type
			$this->login_type->HrefValue = "";

			// accountstatus
			$this->accountstatus->HrefValue = "";

			// ispay
			$this->ispay->HrefValue = "";

			// profilelink
			$this->profilelink->HrefValue = "";

			// source
			$this->source->HrefValue = "";

			// agree
			$this->agree->HrefValue = "";

			// balance
			$this->balance->HrefValue = "";

			// job_title
			$this->job_title->HrefValue = "";

			// projects
			$this->projects->HrefValue = "";

			// opportunities
			$this->opportunities->HrefValue = "";

			// isconsaltant
			$this->isconsaltant->HrefValue = "";

			// isagent
			$this->isagent->HrefValue = "";

			// isinvestor
			$this->isinvestor->HrefValue = "";

			// isbusinessman
			$this->isbusinessman->HrefValue = "";

			// isprovider
			$this->isprovider->HrefValue = "";

			// isproductowner
			$this->isproductowner->HrefValue = "";

			// states
			$this->states->HrefValue = "";

			// cities
			$this->cities->HrefValue = "";

			// offers
			$this->offers->HrefValue = "";
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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!ew_CheckDate($this->registerdate->FormValue)) {
			ew_AddMessage($gsFormError, $this->registerdate->FldErrMsg());
		}
		if (!$this->profilelink->FldIsDetailKey && !is_null($this->profilelink->FormValue) && $this->profilelink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->profilelink->FldCaption(), $this->profilelink->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->balance->FormValue)) {
			ew_AddMessage($gsFormError, $this->balance->FldErrMsg());
		}
		if (!$this->job_title->FldIsDetailKey && !is_null($this->job_title->FormValue) && $this->job_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->job_title->FldCaption(), $this->job_title->ReqErrMsg));
		}
		if (!$this->projects->FldIsDetailKey && !is_null($this->projects->FormValue) && $this->projects->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->projects->FldCaption(), $this->projects->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->projects->FormValue)) {
			ew_AddMessage($gsFormError, $this->projects->FldErrMsg());
		}
		if (!$this->opportunities->FldIsDetailKey && !is_null($this->opportunities->FormValue) && $this->opportunities->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->opportunities->FldCaption(), $this->opportunities->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->opportunities->FormValue)) {
			ew_AddMessage($gsFormError, $this->opportunities->FldErrMsg());
		}
		if (!$this->states->FldIsDetailKey && !is_null($this->states->FormValue) && $this->states->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->states->FldCaption(), $this->states->ReqErrMsg));
		}
		if (!$this->cities->FldIsDetailKey && !is_null($this->cities->FormValue) && $this->cities->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cities->FldCaption(), $this->cities->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->offers->FormValue)) {
			ew_AddMessage($gsFormError, $this->offers->FldErrMsg());
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

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, FALSE);

		// companyname
		$this->companyname->SetDbValueDef($rsnew, $this->companyname->CurrentValue, NULL, FALSE);

		// servicetime
		$this->servicetime->SetDbValueDef($rsnew, $this->servicetime->CurrentValue, NULL, FALSE);

		// country
		$this->country->SetDbValueDef($rsnew, $this->country->CurrentValue, NULL, FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, FALSE);

		// skype
		$this->skype->SetDbValueDef($rsnew, $this->skype->CurrentValue, NULL, FALSE);

		// website
		$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, NULL, FALSE);

		// linkedin
		$this->linkedin->SetDbValueDef($rsnew, $this->linkedin->CurrentValue, NULL, FALSE);

		// facebook
		$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, FALSE);

		// twitter
		$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, NULL, FALSE);

		// active_code
		$this->active_code->SetDbValueDef($rsnew, $this->active_code->CurrentValue, NULL, FALSE);

		// identification
		$this->identification->SetDbValueDef($rsnew, $this->identification->CurrentValue, NULL, FALSE);

		// link_expired
		$this->link_expired->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->link_expired->CurrentValue, 5), NULL, FALSE);

		// isactive
		$this->isactive->SetDbValueDef($rsnew, $this->isactive->CurrentValue, NULL, strval($this->isactive->CurrentValue) == "");

		// pio
		$this->pio->SetDbValueDef($rsnew, $this->pio->CurrentValue, NULL, FALSE);

		// google
		$this->google->SetDbValueDef($rsnew, $this->google->CurrentValue, NULL, FALSE);

		// instagram
		$this->instagram->SetDbValueDef($rsnew, $this->instagram->CurrentValue, NULL, FALSE);

		// account_type
		$this->account_type->SetDbValueDef($rsnew, $this->account_type->CurrentValue, NULL, FALSE);

		// logo
		$this->logo->SetDbValueDef($rsnew, $this->logo->CurrentValue, NULL, FALSE);

		// profilepic
		$this->profilepic->SetDbValueDef($rsnew, $this->profilepic->CurrentValue, NULL, FALSE);

		// mailref
		$this->mailref->SetDbValueDef($rsnew, $this->mailref->CurrentValue, NULL, FALSE);

		// deleted
		$this->deleted->SetDbValueDef($rsnew, $this->deleted->CurrentValue, NULL, strval($this->deleted->CurrentValue) == "");

		// deletefeedback
		$this->deletefeedback->SetDbValueDef($rsnew, $this->deletefeedback->CurrentValue, NULL, FALSE);

		// account_id
		$this->account_id->SetDbValueDef($rsnew, $this->account_id->CurrentValue, NULL, strval($this->account_id->CurrentValue) == "");

		// start_date
		$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 5), NULL, FALSE);

		// end_date
		$this->end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->end_date->CurrentValue, 5), NULL, FALSE);

		// year_moth
		$this->year_moth->SetDbValueDef($rsnew, $this->year_moth->CurrentValue, NULL, FALSE);

		// registerdate
		$this->registerdate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->registerdate->CurrentValue, 5), NULL, FALSE);

		// login_type
		$this->login_type->SetDbValueDef($rsnew, $this->login_type->CurrentValue, NULL, FALSE);

		// accountstatus
		$this->accountstatus->SetDbValueDef($rsnew, $this->accountstatus->CurrentValue, NULL, FALSE);

		// ispay
		$this->ispay->SetDbValueDef($rsnew, $this->ispay->CurrentValue, NULL, strval($this->ispay->CurrentValue) == "");

		// profilelink
		$this->profilelink->SetDbValueDef($rsnew, $this->profilelink->CurrentValue, "", FALSE);

		// source
		$this->source->SetDbValueDef($rsnew, $this->source->CurrentValue, "", FALSE);

		// agree
		$this->agree->SetDbValueDef($rsnew, $this->agree->CurrentValue, NULL, FALSE);

		// balance
		$this->balance->SetDbValueDef($rsnew, $this->balance->CurrentValue, NULL, FALSE);

		// job_title
		$this->job_title->SetDbValueDef($rsnew, $this->job_title->CurrentValue, "", FALSE);

		// projects
		$this->projects->SetDbValueDef($rsnew, $this->projects->CurrentValue, 0, FALSE);

		// opportunities
		$this->opportunities->SetDbValueDef($rsnew, $this->opportunities->CurrentValue, 0, FALSE);

		// isconsaltant
		$this->isconsaltant->SetDbValueDef($rsnew, $this->isconsaltant->CurrentValue, 0, strval($this->isconsaltant->CurrentValue) == "");

		// isagent
		$this->isagent->SetDbValueDef($rsnew, $this->isagent->CurrentValue, 0, strval($this->isagent->CurrentValue) == "");

		// isinvestor
		$this->isinvestor->SetDbValueDef($rsnew, $this->isinvestor->CurrentValue, 0, strval($this->isinvestor->CurrentValue) == "");

		// isbusinessman
		$this->isbusinessman->SetDbValueDef($rsnew, $this->isbusinessman->CurrentValue, 0, strval($this->isbusinessman->CurrentValue) == "");

		// isprovider
		$this->isprovider->SetDbValueDef($rsnew, $this->isprovider->CurrentValue, 0, strval($this->isprovider->CurrentValue) == "");

		// isproductowner
		$this->isproductowner->SetDbValueDef($rsnew, $this->isproductowner->CurrentValue, 0, strval($this->isproductowner->CurrentValue) == "");

		// states
		$this->states->SetDbValueDef($rsnew, $this->states->CurrentValue, "", FALSE);

		// cities
		$this->cities->SetDbValueDef($rsnew, $this->cities->CurrentValue, "", FALSE);

		// offers
		$this->offers->SetDbValueDef($rsnew, $this->offers->CurrentValue, NULL, strval($this->offers->CurrentValue) == "");

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
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "userslist.php", "", $this->TableVar, TRUE);
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
if (!isset($users_add)) $users_add = new cusers_add();

// Page init
$users_add->Page_Init();

// Page main
$users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fusersadd = new ew_Form("fusersadd", "add");

// Validate form
fusersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->name->FldCaption(), $users->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->_email->FldCaption(), $users->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->password->FldCaption(), $users->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_registerdate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->registerdate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_profilelink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->profilelink->FldCaption(), $users->profilelink->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_balance");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->balance->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_job_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->job_title->FldCaption(), $users->job_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_projects");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->projects->FldCaption(), $users->projects->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_projects");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->projects->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_opportunities");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->opportunities->FldCaption(), $users->opportunities->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_opportunities");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->opportunities->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_states");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->states->FldCaption(), $users->states->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cities");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->cities->FldCaption(), $users->cities->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_offers");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->offers->FldErrMsg()) ?>");

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
fusersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusersadd.ValidateRequired = true;
<?php } else { ?>
fusersadd.ValidateRequired = false; 
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
<?php $users_add->ShowPageHeader(); ?>
<?php
$users_add->ShowMessage();
?>
<form name="fusersadd" id="fusersadd" class="<?php echo $users_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($users->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_users_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $users->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->name->CellAttributes() ?>>
<span id="el_users_name">
<input type="text" data-table="users" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->name->getPlaceHolder()) ?>" value="<?php echo $users->name->EditValue ?>"<?php echo $users->name->EditAttributes() ?>>
</span>
<?php echo $users->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_users__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $users->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
<?php echo $users->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_users_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $users->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->password->CellAttributes() ?>>
<span id="el_users_password">
<input type="text" data-table="users" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->password->getPlaceHolder()) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
<?php echo $users->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->companyname->Visible) { // companyname ?>
	<div id="r_companyname" class="form-group">
		<label id="elh_users_companyname" class="col-sm-2 control-label ewLabel"><?php echo $users->companyname->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->companyname->CellAttributes() ?>>
<span id="el_users_companyname">
<input type="text" data-table="users" data-field="x_companyname" name="x_companyname" id="x_companyname" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->companyname->getPlaceHolder()) ?>" value="<?php echo $users->companyname->EditValue ?>"<?php echo $users->companyname->EditAttributes() ?>>
</span>
<?php echo $users->companyname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->servicetime->Visible) { // servicetime ?>
	<div id="r_servicetime" class="form-group">
		<label id="elh_users_servicetime" class="col-sm-2 control-label ewLabel"><?php echo $users->servicetime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->servicetime->CellAttributes() ?>>
<span id="el_users_servicetime">
<input type="text" data-table="users" data-field="x_servicetime" name="x_servicetime" id="x_servicetime" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($users->servicetime->getPlaceHolder()) ?>" value="<?php echo $users->servicetime->EditValue ?>"<?php echo $users->servicetime->EditAttributes() ?>>
</span>
<?php echo $users->servicetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->country->Visible) { // country ?>
	<div id="r_country" class="form-group">
		<label id="elh_users_country" for="x_country" class="col-sm-2 control-label ewLabel"><?php echo $users->country->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->country->CellAttributes() ?>>
<span id="el_users_country">
<input type="text" data-table="users" data-field="x_country" name="x_country" id="x_country" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($users->country->getPlaceHolder()) ?>" value="<?php echo $users->country->EditValue ?>"<?php echo $users->country->EditAttributes() ?>>
</span>
<?php echo $users->country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_users_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $users->phone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->phone->CellAttributes() ?>>
<span id="el_users_phone">
<input type="text" data-table="users" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->phone->getPlaceHolder()) ?>" value="<?php echo $users->phone->EditValue ?>"<?php echo $users->phone->EditAttributes() ?>>
</span>
<?php echo $users->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->skype->Visible) { // skype ?>
	<div id="r_skype" class="form-group">
		<label id="elh_users_skype" for="x_skype" class="col-sm-2 control-label ewLabel"><?php echo $users->skype->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->skype->CellAttributes() ?>>
<span id="el_users_skype">
<input type="text" data-table="users" data-field="x_skype" name="x_skype" id="x_skype" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->skype->getPlaceHolder()) ?>" value="<?php echo $users->skype->EditValue ?>"<?php echo $users->skype->EditAttributes() ?>>
</span>
<?php echo $users->skype->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_users_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $users->website->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->website->CellAttributes() ?>>
<span id="el_users_website">
<input type="text" data-table="users" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->website->getPlaceHolder()) ?>" value="<?php echo $users->website->EditValue ?>"<?php echo $users->website->EditAttributes() ?>>
</span>
<?php echo $users->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->linkedin->Visible) { // linkedin ?>
	<div id="r_linkedin" class="form-group">
		<label id="elh_users_linkedin" for="x_linkedin" class="col-sm-2 control-label ewLabel"><?php echo $users->linkedin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->linkedin->CellAttributes() ?>>
<span id="el_users_linkedin">
<input type="text" data-table="users" data-field="x_linkedin" name="x_linkedin" id="x_linkedin" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->linkedin->getPlaceHolder()) ?>" value="<?php echo $users->linkedin->EditValue ?>"<?php echo $users->linkedin->EditAttributes() ?>>
</span>
<?php echo $users->linkedin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->facebook->Visible) { // facebook ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_users_facebook" for="x_facebook" class="col-sm-2 control-label ewLabel"><?php echo $users->facebook->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->facebook->CellAttributes() ?>>
<span id="el_users_facebook">
<input type="text" data-table="users" data-field="x_facebook" name="x_facebook" id="x_facebook" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->facebook->getPlaceHolder()) ?>" value="<?php echo $users->facebook->EditValue ?>"<?php echo $users->facebook->EditAttributes() ?>>
</span>
<?php echo $users->facebook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->twitter->Visible) { // twitter ?>
	<div id="r_twitter" class="form-group">
		<label id="elh_users_twitter" for="x_twitter" class="col-sm-2 control-label ewLabel"><?php echo $users->twitter->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->twitter->CellAttributes() ?>>
<span id="el_users_twitter">
<input type="text" data-table="users" data-field="x_twitter" name="x_twitter" id="x_twitter" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->twitter->getPlaceHolder()) ?>" value="<?php echo $users->twitter->EditValue ?>"<?php echo $users->twitter->EditAttributes() ?>>
</span>
<?php echo $users->twitter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->active_code->Visible) { // active_code ?>
	<div id="r_active_code" class="form-group">
		<label id="elh_users_active_code" class="col-sm-2 control-label ewLabel"><?php echo $users->active_code->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->active_code->CellAttributes() ?>>
<span id="el_users_active_code">
<input type="text" data-table="users" data-field="x_active_code" name="x_active_code" id="x_active_code" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($users->active_code->getPlaceHolder()) ?>" value="<?php echo $users->active_code->EditValue ?>"<?php echo $users->active_code->EditAttributes() ?>>
</span>
<?php echo $users->active_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->identification->Visible) { // identification ?>
	<div id="r_identification" class="form-group">
		<label id="elh_users_identification" class="col-sm-2 control-label ewLabel"><?php echo $users->identification->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->identification->CellAttributes() ?>>
<span id="el_users_identification">
<input type="text" data-table="users" data-field="x_identification" name="x_identification" id="x_identification" size="30" placeholder="<?php echo ew_HtmlEncode($users->identification->getPlaceHolder()) ?>" value="<?php echo $users->identification->EditValue ?>"<?php echo $users->identification->EditAttributes() ?>>
</span>
<?php echo $users->identification->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->link_expired->Visible) { // link_expired ?>
	<div id="r_link_expired" class="form-group">
		<label id="elh_users_link_expired" class="col-sm-2 control-label ewLabel"><?php echo $users->link_expired->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->link_expired->CellAttributes() ?>>
<span id="el_users_link_expired">
<input type="text" data-table="users" data-field="x_link_expired" data-format="5" name="x_link_expired" id="x_link_expired" placeholder="<?php echo ew_HtmlEncode($users->link_expired->getPlaceHolder()) ?>" value="<?php echo $users->link_expired->EditValue ?>"<?php echo $users->link_expired->EditAttributes() ?>>
</span>
<?php echo $users->link_expired->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_users_isactive">
<input type="hidden" data-table="users" data-field="x_isactive" name="x_isactive" id="x_isactive" value="<?php echo ew_HtmlEncode($users->isactive->CurrentValue) ?>">
</span>
<?php if ($users->pio->Visible) { // pio ?>
	<div id="r_pio" class="form-group">
		<label id="elh_users_pio" class="col-sm-2 control-label ewLabel"><?php echo $users->pio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->pio->CellAttributes() ?>>
<span id="el_users_pio">
<input type="text" data-table="users" data-field="x_pio" name="x_pio" id="x_pio" placeholder="<?php echo ew_HtmlEncode($users->pio->getPlaceHolder()) ?>" value="<?php echo $users->pio->EditValue ?>"<?php echo $users->pio->EditAttributes() ?>>
</span>
<?php echo $users->pio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->google->Visible) { // google ?>
	<div id="r_google" class="form-group">
		<label id="elh_users_google" for="x_google" class="col-sm-2 control-label ewLabel"><?php echo $users->google->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->google->CellAttributes() ?>>
<span id="el_users_google">
<input type="text" data-table="users" data-field="x_google" name="x_google" id="x_google" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->google->getPlaceHolder()) ?>" value="<?php echo $users->google->EditValue ?>"<?php echo $users->google->EditAttributes() ?>>
</span>
<?php echo $users->google->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->instagram->Visible) { // instagram ?>
	<div id="r_instagram" class="form-group">
		<label id="elh_users_instagram" for="x_instagram" class="col-sm-2 control-label ewLabel"><?php echo $users->instagram->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->instagram->CellAttributes() ?>>
<span id="el_users_instagram">
<input type="text" data-table="users" data-field="x_instagram" name="x_instagram" id="x_instagram" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->instagram->getPlaceHolder()) ?>" value="<?php echo $users->instagram->EditValue ?>"<?php echo $users->instagram->EditAttributes() ?>>
</span>
<?php echo $users->instagram->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->account_type->Visible) { // account_type ?>
	<div id="r_account_type" class="form-group">
		<label id="elh_users_account_type" for="x_account_type" class="col-sm-2 control-label ewLabel"><?php echo $users->account_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->account_type->CellAttributes() ?>>
<span id="el_users_account_type">
<input type="text" data-table="users" data-field="x_account_type" name="x_account_type" id="x_account_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->account_type->getPlaceHolder()) ?>" value="<?php echo $users->account_type->EditValue ?>"<?php echo $users->account_type->EditAttributes() ?>>
</span>
<?php echo $users->account_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->logo->Visible) { // logo ?>
	<div id="r_logo" class="form-group">
		<label id="elh_users_logo" class="col-sm-2 control-label ewLabel"><?php echo $users->logo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->logo->CellAttributes() ?>>
<span id="el_users_logo">
<input type="text" data-table="users" data-field="x_logo" name="x_logo" id="x_logo" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->logo->getPlaceHolder()) ?>" value="<?php echo $users->logo->EditValue ?>"<?php echo $users->logo->EditAttributes() ?>>
</span>
<?php echo $users->logo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->profilepic->Visible) { // profilepic ?>
	<div id="r_profilepic" class="form-group">
		<label id="elh_users_profilepic" class="col-sm-2 control-label ewLabel"><?php echo $users->profilepic->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->profilepic->CellAttributes() ?>>
<span id="el_users_profilepic">
<input type="text" data-table="users" data-field="x_profilepic" name="x_profilepic" id="x_profilepic" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($users->profilepic->getPlaceHolder()) ?>" value="<?php echo $users->profilepic->EditValue ?>"<?php echo $users->profilepic->EditAttributes() ?>>
</span>
<?php echo $users->profilepic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->mailref->Visible) { // mailref ?>
	<div id="r_mailref" class="form-group">
		<label id="elh_users_mailref" class="col-sm-2 control-label ewLabel"><?php echo $users->mailref->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->mailref->CellAttributes() ?>>
<span id="el_users_mailref">
<input type="text" data-table="users" data-field="x_mailref" name="x_mailref" id="x_mailref" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($users->mailref->getPlaceHolder()) ?>" value="<?php echo $users->mailref->EditValue ?>"<?php echo $users->mailref->EditAttributes() ?>>
</span>
<?php echo $users->mailref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_users_deleted">
<input type="hidden" data-table="users" data-field="x_deleted" name="x_deleted" id="x_deleted" value="<?php echo ew_HtmlEncode($users->deleted->CurrentValue) ?>">
</span>
<?php if ($users->deletefeedback->Visible) { // deletefeedback ?>
	<div id="r_deletefeedback" class="form-group">
		<label id="elh_users_deletefeedback" class="col-sm-2 control-label ewLabel"><?php echo $users->deletefeedback->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->deletefeedback->CellAttributes() ?>>
<span id="el_users_deletefeedback">
<input type="text" data-table="users" data-field="x_deletefeedback" name="x_deletefeedback" id="x_deletefeedback" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->deletefeedback->getPlaceHolder()) ?>" value="<?php echo $users->deletefeedback->EditValue ?>"<?php echo $users->deletefeedback->EditAttributes() ?>>
</span>
<?php echo $users->deletefeedback->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_users_account_id">
<input type="hidden" data-table="users" data-field="x_account_id" name="x_account_id" id="x_account_id" value="<?php echo ew_HtmlEncode($users->account_id->CurrentValue) ?>">
</span>
<?php if ($users->start_date->Visible) { // start_date ?>
	<div id="r_start_date" class="form-group">
		<label id="elh_users_start_date" class="col-sm-2 control-label ewLabel"><?php echo $users->start_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->start_date->CellAttributes() ?>>
<span id="el_users_start_date">
<input type="text" data-table="users" data-field="x_start_date" data-format="5" name="x_start_date" id="x_start_date" placeholder="<?php echo ew_HtmlEncode($users->start_date->getPlaceHolder()) ?>" value="<?php echo $users->start_date->EditValue ?>"<?php echo $users->start_date->EditAttributes() ?>>
</span>
<?php echo $users->start_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label id="elh_users_end_date" class="col-sm-2 control-label ewLabel"><?php echo $users->end_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->end_date->CellAttributes() ?>>
<span id="el_users_end_date">
<input type="text" data-table="users" data-field="x_end_date" data-format="5" name="x_end_date" id="x_end_date" placeholder="<?php echo ew_HtmlEncode($users->end_date->getPlaceHolder()) ?>" value="<?php echo $users->end_date->EditValue ?>"<?php echo $users->end_date->EditAttributes() ?>>
</span>
<?php echo $users->end_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->year_moth->Visible) { // year_moth ?>
	<div id="r_year_moth" class="form-group">
		<label id="elh_users_year_moth" class="col-sm-2 control-label ewLabel"><?php echo $users->year_moth->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->year_moth->CellAttributes() ?>>
<span id="el_users_year_moth">
<input type="text" data-table="users" data-field="x_year_moth" name="x_year_moth" id="x_year_moth" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($users->year_moth->getPlaceHolder()) ?>" value="<?php echo $users->year_moth->EditValue ?>"<?php echo $users->year_moth->EditAttributes() ?>>
</span>
<?php echo $users->year_moth->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->registerdate->Visible) { // registerdate ?>
	<div id="r_registerdate" class="form-group">
		<label id="elh_users_registerdate" for="x_registerdate" class="col-sm-2 control-label ewLabel"><?php echo $users->registerdate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->registerdate->CellAttributes() ?>>
<span id="el_users_registerdate">
<input type="text" data-table="users" data-field="x_registerdate" data-format="5" name="x_registerdate" id="x_registerdate" placeholder="<?php echo ew_HtmlEncode($users->registerdate->getPlaceHolder()) ?>" value="<?php echo $users->registerdate->EditValue ?>"<?php echo $users->registerdate->EditAttributes() ?>>
<?php if (!$users->registerdate->ReadOnly && !$users->registerdate->Disabled && !isset($users->registerdate->EditAttrs["readonly"]) && !isset($users->registerdate->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fusersadd", "x_registerdate", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $users->registerdate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->login_type->Visible) { // login_type ?>
	<div id="r_login_type" class="form-group">
		<label id="elh_users_login_type" for="x_login_type" class="col-sm-2 control-label ewLabel"><?php echo $users->login_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->login_type->CellAttributes() ?>>
<span id="el_users_login_type">
<input type="text" data-table="users" data-field="x_login_type" name="x_login_type" id="x_login_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->login_type->getPlaceHolder()) ?>" value="<?php echo $users->login_type->EditValue ?>"<?php echo $users->login_type->EditAttributes() ?>>
</span>
<?php echo $users->login_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->accountstatus->Visible) { // accountstatus ?>
	<div id="r_accountstatus" class="form-group">
		<label id="elh_users_accountstatus" for="x_accountstatus" class="col-sm-2 control-label ewLabel"><?php echo $users->accountstatus->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->accountstatus->CellAttributes() ?>>
<span id="el_users_accountstatus">
<input type="text" data-table="users" data-field="x_accountstatus" name="x_accountstatus" id="x_accountstatus" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($users->accountstatus->getPlaceHolder()) ?>" value="<?php echo $users->accountstatus->EditValue ?>"<?php echo $users->accountstatus->EditAttributes() ?>>
</span>
<?php echo $users->accountstatus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_users_ispay">
<input type="hidden" data-table="users" data-field="x_ispay" name="x_ispay" id="x_ispay" value="<?php echo ew_HtmlEncode($users->ispay->CurrentValue) ?>">
</span>
<?php if ($users->profilelink->Visible) { // profilelink ?>
	<div id="r_profilelink" class="form-group">
		<label id="elh_users_profilelink" for="x_profilelink" class="col-sm-2 control-label ewLabel"><?php echo $users->profilelink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->profilelink->CellAttributes() ?>>
<span id="el_users_profilelink">
<input type="text" data-table="users" data-field="x_profilelink" name="x_profilelink" id="x_profilelink" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($users->profilelink->getPlaceHolder()) ?>" value="<?php echo $users->profilelink->EditValue ?>"<?php echo $users->profilelink->EditAttributes() ?>>
</span>
<?php echo $users->profilelink->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->source->Visible) { // source ?>
	<div id="r_source" class="form-group">
		<label id="elh_users_source" class="col-sm-2 control-label ewLabel"><?php echo $users->source->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->source->CellAttributes() ?>>
<span id="el_users_source">
<input type="text" data-table="users" data-field="x_source" name="x_source" id="x_source" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($users->source->getPlaceHolder()) ?>" value="<?php echo $users->source->EditValue ?>"<?php echo $users->source->EditAttributes() ?>>
</span>
<?php echo $users->source->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->agree->Visible) { // agree ?>
	<div id="r_agree" class="form-group">
		<label id="elh_users_agree" class="col-sm-2 control-label ewLabel"><?php echo $users->agree->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->agree->CellAttributes() ?>>
<span id="el_users_agree">
<input type="text" data-table="users" data-field="x_agree" name="x_agree" id="x_agree" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($users->agree->getPlaceHolder()) ?>" value="<?php echo $users->agree->EditValue ?>"<?php echo $users->agree->EditAttributes() ?>>
</span>
<?php echo $users->agree->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->balance->Visible) { // balance ?>
	<div id="r_balance" class="form-group">
		<label id="elh_users_balance" for="x_balance" class="col-sm-2 control-label ewLabel"><?php echo $users->balance->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->balance->CellAttributes() ?>>
<span id="el_users_balance">
<input type="text" data-table="users" data-field="x_balance" name="x_balance" id="x_balance" size="30" placeholder="<?php echo ew_HtmlEncode($users->balance->getPlaceHolder()) ?>" value="<?php echo $users->balance->EditValue ?>"<?php echo $users->balance->EditAttributes() ?>>
</span>
<?php echo $users->balance->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->job_title->Visible) { // job_title ?>
	<div id="r_job_title" class="form-group">
		<label id="elh_users_job_title" for="x_job_title" class="col-sm-2 control-label ewLabel"><?php echo $users->job_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->job_title->CellAttributes() ?>>
<span id="el_users_job_title">
<input type="text" data-table="users" data-field="x_job_title" name="x_job_title" id="x_job_title" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->job_title->getPlaceHolder()) ?>" value="<?php echo $users->job_title->EditValue ?>"<?php echo $users->job_title->EditAttributes() ?>>
</span>
<?php echo $users->job_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->projects->Visible) { // projects ?>
	<div id="r_projects" class="form-group">
		<label id="elh_users_projects" for="x_projects" class="col-sm-2 control-label ewLabel"><?php echo $users->projects->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->projects->CellAttributes() ?>>
<span id="el_users_projects">
<input type="text" data-table="users" data-field="x_projects" name="x_projects" id="x_projects" size="30" placeholder="<?php echo ew_HtmlEncode($users->projects->getPlaceHolder()) ?>" value="<?php echo $users->projects->EditValue ?>"<?php echo $users->projects->EditAttributes() ?>>
</span>
<?php echo $users->projects->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->opportunities->Visible) { // opportunities ?>
	<div id="r_opportunities" class="form-group">
		<label id="elh_users_opportunities" for="x_opportunities" class="col-sm-2 control-label ewLabel"><?php echo $users->opportunities->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->opportunities->CellAttributes() ?>>
<span id="el_users_opportunities">
<input type="text" data-table="users" data-field="x_opportunities" name="x_opportunities" id="x_opportunities" size="30" placeholder="<?php echo ew_HtmlEncode($users->opportunities->getPlaceHolder()) ?>" value="<?php echo $users->opportunities->EditValue ?>"<?php echo $users->opportunities->EditAttributes() ?>>
</span>
<?php echo $users->opportunities->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isconsaltant->Visible) { // isconsaltant ?>
	<div id="r_isconsaltant" class="form-group">
		<label id="elh_users_isconsaltant" class="col-sm-2 control-label ewLabel"><?php echo $users->isconsaltant->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isconsaltant->CellAttributes() ?>>
<span id="el_users_isconsaltant">
<div id="tp_x_isconsaltant" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isconsaltant" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isconsaltant->DisplayValueSeparator) ? json_encode($users->isconsaltant->DisplayValueSeparator) : $users->isconsaltant->DisplayValueSeparator) ?>" name="x_isconsaltant[]" id="x_isconsaltant[]" value="{value}"<?php echo $users->isconsaltant->EditAttributes() ?>></div>
<div id="dsl_x_isconsaltant" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isconsaltant->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isconsaltant->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isconsaltant" name="x_isconsaltant[]" id="x_isconsaltant_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isconsaltant->EditAttributes() ?>><?php echo $users->isconsaltant->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isconsaltant" name="x_isconsaltant[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isconsaltant->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isconsaltant->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isagent->Visible) { // isagent ?>
	<div id="r_isagent" class="form-group">
		<label id="elh_users_isagent" class="col-sm-2 control-label ewLabel"><?php echo $users->isagent->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isagent->CellAttributes() ?>>
<span id="el_users_isagent">
<div id="tp_x_isagent" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isagent" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isagent->DisplayValueSeparator) ? json_encode($users->isagent->DisplayValueSeparator) : $users->isagent->DisplayValueSeparator) ?>" name="x_isagent[]" id="x_isagent[]" value="{value}"<?php echo $users->isagent->EditAttributes() ?>></div>
<div id="dsl_x_isagent" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isagent->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isagent->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isagent" name="x_isagent[]" id="x_isagent_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isagent->EditAttributes() ?>><?php echo $users->isagent->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isagent" name="x_isagent[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isagent->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isagent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isinvestor->Visible) { // isinvestor ?>
	<div id="r_isinvestor" class="form-group">
		<label id="elh_users_isinvestor" class="col-sm-2 control-label ewLabel"><?php echo $users->isinvestor->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isinvestor->CellAttributes() ?>>
<span id="el_users_isinvestor">
<div id="tp_x_isinvestor" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isinvestor" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isinvestor->DisplayValueSeparator) ? json_encode($users->isinvestor->DisplayValueSeparator) : $users->isinvestor->DisplayValueSeparator) ?>" name="x_isinvestor[]" id="x_isinvestor[]" value="{value}"<?php echo $users->isinvestor->EditAttributes() ?>></div>
<div id="dsl_x_isinvestor" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isinvestor->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isinvestor->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isinvestor" name="x_isinvestor[]" id="x_isinvestor_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isinvestor->EditAttributes() ?>><?php echo $users->isinvestor->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isinvestor" name="x_isinvestor[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isinvestor->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isinvestor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isbusinessman->Visible) { // isbusinessman ?>
	<div id="r_isbusinessman" class="form-group">
		<label id="elh_users_isbusinessman" class="col-sm-2 control-label ewLabel"><?php echo $users->isbusinessman->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isbusinessman->CellAttributes() ?>>
<span id="el_users_isbusinessman">
<div id="tp_x_isbusinessman" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isbusinessman" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isbusinessman->DisplayValueSeparator) ? json_encode($users->isbusinessman->DisplayValueSeparator) : $users->isbusinessman->DisplayValueSeparator) ?>" name="x_isbusinessman[]" id="x_isbusinessman[]" value="{value}"<?php echo $users->isbusinessman->EditAttributes() ?>></div>
<div id="dsl_x_isbusinessman" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isbusinessman->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isbusinessman->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isbusinessman" name="x_isbusinessman[]" id="x_isbusinessman_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isbusinessman->EditAttributes() ?>><?php echo $users->isbusinessman->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isbusinessman" name="x_isbusinessman[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isbusinessman->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isbusinessman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isprovider->Visible) { // isprovider ?>
	<div id="r_isprovider" class="form-group">
		<label id="elh_users_isprovider" class="col-sm-2 control-label ewLabel"><?php echo $users->isprovider->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isprovider->CellAttributes() ?>>
<span id="el_users_isprovider">
<div id="tp_x_isprovider" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isprovider" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isprovider->DisplayValueSeparator) ? json_encode($users->isprovider->DisplayValueSeparator) : $users->isprovider->DisplayValueSeparator) ?>" name="x_isprovider[]" id="x_isprovider[]" value="{value}"<?php echo $users->isprovider->EditAttributes() ?>></div>
<div id="dsl_x_isprovider" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isprovider->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isprovider->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isprovider" name="x_isprovider[]" id="x_isprovider_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isprovider->EditAttributes() ?>><?php echo $users->isprovider->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isprovider" name="x_isprovider[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isprovider->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isprovider->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->isproductowner->Visible) { // isproductowner ?>
	<div id="r_isproductowner" class="form-group">
		<label id="elh_users_isproductowner" class="col-sm-2 control-label ewLabel"><?php echo $users->isproductowner->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->isproductowner->CellAttributes() ?>>
<span id="el_users_isproductowner">
<div id="tp_x_isproductowner" class="ewTemplate"><input type="checkbox" data-table="users" data-field="x_isproductowner" data-value-separator="<?php echo ew_HtmlEncode(is_array($users->isproductowner->DisplayValueSeparator) ? json_encode($users->isproductowner->DisplayValueSeparator) : $users->isproductowner->DisplayValueSeparator) ?>" name="x_isproductowner[]" id="x_isproductowner[]" value="{value}"<?php echo $users->isproductowner->EditAttributes() ?>></div>
<div id="dsl_x_isproductowner" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $users->isproductowner->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($users->isproductowner->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				unset($armultiwrk[$ari]); // Remove already matched item
				$selwrk = " checked";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isproductowner" name="x_isproductowner[]" id="x_isproductowner_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $users->isproductowner->EditAttributes() ?>><?php echo $users->isproductowner->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	$rowswrk = count($armultiwrk);
	if ($rowswrk > 0) {
		for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
?>
<label class="checkbox-inline"><input type="checkbox" data-table="users" data-field="x_isproductowner" name="x_isproductowner[]" value="<?php echo ew_HtmlEncode($armultiwrk[$rowcntwrk]) ?>" checked<?php echo $users->isproductowner->EditAttributes() ?>><?php echo $armultiwrk[$rowcntwrk] ?></label>
<?php
		}
	}
}
?>
</div></div>
</span>
<?php echo $users->isproductowner->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->states->Visible) { // states ?>
	<div id="r_states" class="form-group">
		<label id="elh_users_states" for="x_states" class="col-sm-2 control-label ewLabel"><?php echo $users->states->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->states->CellAttributes() ?>>
<span id="el_users_states">
<input type="text" data-table="users" data-field="x_states" name="x_states" id="x_states" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($users->states->getPlaceHolder()) ?>" value="<?php echo $users->states->EditValue ?>"<?php echo $users->states->EditAttributes() ?>>
</span>
<?php echo $users->states->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->cities->Visible) { // cities ?>
	<div id="r_cities" class="form-group">
		<label id="elh_users_cities" for="x_cities" class="col-sm-2 control-label ewLabel"><?php echo $users->cities->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $users->cities->CellAttributes() ?>>
<span id="el_users_cities">
<input type="text" data-table="users" data-field="x_cities" name="x_cities" id="x_cities" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($users->cities->getPlaceHolder()) ?>" value="<?php echo $users->cities->EditValue ?>"<?php echo $users->cities->EditAttributes() ?>>
</span>
<?php echo $users->cities->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->offers->Visible) { // offers ?>
	<div id="r_offers" class="form-group">
		<label id="elh_users_offers" for="x_offers" class="col-sm-2 control-label ewLabel"><?php echo $users->offers->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $users->offers->CellAttributes() ?>>
<span id="el_users_offers">
<input type="text" data-table="users" data-field="x_offers" name="x_offers" id="x_offers" size="30" placeholder="<?php echo ew_HtmlEncode($users->offers->getPlaceHolder()) ?>" value="<?php echo $users->offers->EditValue ?>"<?php echo $users->offers->EditAttributes() ?>>
</span>
<?php echo $users->offers->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fusersadd.Init();
</script>
<?php
$users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_add->Page_Terminate();
?>
