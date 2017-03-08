<?php


class FoodServicePage extends Page{
	
	private static $allowed_children = array(
		'BurgerBuilderPage'
	);
	
	private static $has_many = array(
		'Burgers' => 'BurgerComponents'
	);
	
	
	public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.BurgerComponents', GridField::create(
            'Burgers',
            'Burger Components List',
            $this->Burgers(),
            GridFieldConfig_RecordEditor::create()
        ));		
        return $fields;
    }
	
}

//===================
//===================
//===================
//===================
//===================
//===================
//===================

class FoodServicePage_Controller extends Page_Controller{
	
		private static $allowed_actions = array(
			'OrderOnAccountForm',
			'NeedAnAccountForm',
			'FoodServiceDiscountForm',
			'logout'
		);
		
		public function init(){
			parent::init();
						
			//== SENDING EMAIL FROM CMS CUSTOMERS AND CODE SECTION
			//== SENDING EMAIL FROM CMS CUSTOMERS AND CODE SECTION
			//== SENDING EMAIL FROM CMS CUSTOMERS AND CODE SECTION
			if(isset($_GET['v'])){
				if($_GET['v']== 'dr891a5'){
				$error = '';										
						if(!$_GET['message']){
							$error = 'Error: Enter a message and save, please';
						}								
						if(!$_GET['email']){
							$error = 'Error: Enter email address and save, please ';
						}
						if(!$_GET['subject']){
							$error = 'Error: Enter a subject and save, please';
						}
						
						if (!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) === false) {								  
						} else {
						  $error = 'Error: enter a valid email and save, please';
						}
						
						
						
						
						if($error == ''){	// all good send email							
							// GET PDF INFO
							// GET PDF INFO
							// GET PDF INFO
							$pdfsID = AdminProperties::get()->where('InUse = 1');						
								$registerPdfID = $pdfsID[0]->AccountApplicationFormID;
								$debitPdfID = $pdfsID[0]->DirectDebitFormID;
								
									$registerFormPath = File::get()->where('ID = '.$registerPdfID);
									$registerFormPath = $registerFormPath[0]->Filename; // path to register pdf
									
									$debitFormPath = File::get()->where('ID = '.$debitPdfID);
									$debitFormPath = $debitFormPath[0]->Filename; // path to direct debit pdf
							// END GET PDF INFO
							// END GET PDF INFO
							// END GET PDF INFO
							if($_SERVER['HTTP_HOST'] != 'localhost'){
								date_default_timezone_set('NZ');
								$email = new Email();
								$email->setTo($_GET['email'].','.$_GET['member']); 
								$email->setFrom('info@rocketproducts.co.nz');  
								$email->setSubject($_GET['subject']); 
								$messageBody = $_GET['message']; 
								$email->setBody($messageBody); 											
								$email->attachFile($registerFormPath);
								$email->attachFile($debitFormPath);
								$email->send(); // to customer
							}
						}
							
					
					if($error != ''){
						$out = $error;
					}else{
						$out = 'OK<br /><br />Email was sent to the < '.$_GET['email'].' > and CC to < '.$_GET['member'].' >';
					}
					echo '<!DOCTYPE html>
					  <html lang="en">
					  <style>
					  body		{ margin-top:200px;font-family:arial;font-weight:bold;background-color:#ccc }
					  div		{ background-color:#ff9933;width:800px;padding:50px 0 30px 0;border:2px dashed black; }
					  button	{ display:block; padding:10px;margin-top:20px;cursor:pointer}
					  </style>
					  <body>
					  <center><div>'.$out.'
					  <button type="button" onclick="window.open(\'\', \'_self\', \'\'); window.close();">Discard</button>
					  </div>
					  </center>
					  </body>
					  </htnl>
					  ';						
					
					exit;
				}// END GET
			}
			// === END SENDING EMAIL FROM CMS
			
			
			
			
			Requirements::css("{$this->ThemeDir()}/css/foodService.css");
			Requirements::css("http://vjs.zencdn.net/5.0.2/video-js.css");
			Requirements::javascript("http://vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js");
			Requirements::javascript("http://vjs.zencdn.net/5.0.2/video.js");
			Requirements::javascript("{$this->ThemeDir()}/js/foodService.js");
			Requirements::javascript("{$this->ThemeDir()}/js/jquery.appear.js");
			
		
			
			//===============================================
			//=== PAGES ACCESS WITH "ACTION" TYPED WILL BE REDIRECTED
			//=== PAGES ACCESS WITH "ACTION" TYPED WILL BE REDIRECTED
			//=== PAGES ACCESS WITH "ACTION" TYPED WILL BE REDIRECTED
			
			$theAction = $this->urlParams['Action'];
			if(!$_POST){
				if($theAction == 'OrderOnAccountForm' 
				|| $theAction == 'NeedAnAccountForm' 
				|| $theAction == 'FoodServiceDiscountForm' 
				|| $theAction == 'logout'){
				$this->redirect($this->URLSegment.'/');
				}				
			}
			
			
			//=======================================
			//== 	LOGOUT DESTROY FOOD SERVICE SESSION
			//== 	LOGOUT DESTROY FOOD SERVICE SESSION
			//== 	LOGOUT DESTROY FOOD SERVICE SESSION
			
			if($theAction == 'logout'){
				Session::clear('FoodServiceSesssion');
				Session::clear('DiscountCode');
			}		
			
		} // END init()
		
		
		
		//============================
		//==== GET ALL CUSTOMERS INFO -- LOCAL
		//==== GET ALL CUSTOMERS INFO
		//==== GET ALL CUSTOMERS INFO		
		public function AllCustomersInfo() {			
				return CustomersAndCode::get();	
		}
		
		//==============================
		//==== GET CURRENT CUSTOMER INFO -- LOCAL & GLOBAL
		//==== GET CURRENT CUSTOMER INFO
		//==== GET CURRENT CUSTOMER INFO
		//==== GET CURRENT CUSTOMER INFO	
		public function CurrentCustomerInfo() {		
				return CustomersAndCode::get()->filter(array(
				'Code'=> Session::get('FoodServiceSesssion')));
		}
		
		//======================
		//=== GET CODES INFO
		//=== GET CODES INFO
		//=== GET CODES INFO
		//=== GET CODES INFO		
		public function PromotionalCodes() {			
				return PromotionalCodes::get();	
		}
		
		//====================
		//== CURRENT CODE INFO
		//== CURRENT CODE INFO
		//== CURRENT CODE INFO
		public function CurrentPromotionalCode() {		
			if(Session::get('DiscountCode')){
				return PromotionalCodes::get()->filter(array(
				'Code'=> Session::get('DiscountCode')));
			}
		}
		
		//===========================================================
		// ONLY DISCONT VALUE % FOR FOOD-SERVICE PAGE PRODUCTS ROLLING
		// ONLY DISCONT VALUE % FOR FOOD-SERVICE PAGE PRODUCTS ROLLING
		// ONLY DISCONT VALUE % FOR FOOD-SERVICE PAGE PRODUCTS ROLLING
		public function DiscountValue() {		
			if(Session::get('DiscountCode')){
				$out = PromotionalCodes::get()->filter(array(
				'Code'=> Session::get('DiscountCode')));
				return $out[0]->CodeDiscount;
			}
		}
		
		
		//========================================================!!!
		//==== GET PRUDUCTS INFO + BURGER RECIPES INFO		
		//==== GET PRUDUCTS INFO + BURGER RECIPES INFO		
		//==== GET PRUDUCTS INFO + BURGER RECIPES INFO		
		//==== GET PRUDUCTS INFO + BURGER RECIPES INFO		
		public function FoodProducts() {
				$howManyElements = count(FoodServiceProducts::get());
				$burgerBuilderInfo = $this->Children();
				
				$mainObject = FoodServiceProducts::get();
				$out = ArrayList::create();
				// ADD ANY VALUE (HERE BURGER BUILDER INFO) AT THE MAIN OBJECT TO LOOP!!!!!!!!!!!!! YES YES YES
				// IF AMOUNT OF BURGRER RECIPES MORE THEN PRODUCTS -- THEY WILL NOT BE DISPLAYED
				foreach($mainObject as $key => $value){					
					if(isset($burgerBuilderInfo[$key]->ID)){
						$value->BurgerTitle = $burgerBuilderInfo[$key]->Title;
						$value->BurgerURL = $burgerBuilderInfo[$key]->URLSegment;
							if(null !== $this->DiscountValue()){
								$value->Discount = $this->DiscountValue();
								$value->OriginalPrice = $value->ProductPrice;
								$value->ProductPrice = round($value->ProductPrice-($value->ProductPrice*$this->DiscountValue())/100,2);
							}
						$out->push($value);
					}else{ // if burger doesnot exists push emtpy
						$value->BurgerTitle = '';
						$value->BurgerURL = '';
							if(null !== $this->DiscountValue()){
									$value->Discount = $this->DiscountValue();
									$value->OriginalPrice = $value->ProductPrice;
									$value->ProductPrice = round($value->ProductPrice-($value->ProductPrice*$this->DiscountValue())/100,2);
							}
						$out->push($value);
					}
				}				
				return $out;			
		} //== END
		
		//===============================
		//==== ROOL FOOD SERVICE CONTENT
		//==== ROOL FOOD SERVICE CONTENT
		//==== ROOL FOOD SERVICE CONTENT
		//==== ROOL FOOD SERVICE CONTENT		
		public function DisplayFoodServiceTable(){		

			if($this->CurrentCustomerInfo()->exists()) {
				foreach($this->CurrentCustomerInfo() as $value){
						$code = $value->Code;
					if(Session::get('FoodServiceSesssion') == $code){
						return $this->renderWith("FoodServiceTemplate_1");
					}				
				}
			}	
			
		}// END
		
		//=== RETURN CURRENT MESSAGE FOR PROMOTIONAL CODES SECTION
		//=== RETURN CURRENT MESSAGE FOR PROMOTIONAL CODES SECTION
		//=== RETURN CURRENT MESSAGE FOR PROMOTIONAL CODES SECTION		
		public function DisplayFoodServiceMassage(){
			foreach($this->PromotionalCodes() as $value){
				if(Session::get('FoodServiceSesssion') == $value->Code){
					return $value->Description;
				}				
			}
		}
		
		
		
		
		
		//============================================================================================
		//============================================================================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
		//================================= FORMS FORMS FORMS ========================================
	
		
		
		
		
		//==========================
		//===== ORDER ON ACCONT FORM 11111111111111111111111
		//===== ORDER ON ACCONT FORM
		//===== ORDER ON ACCONT FORM
		//===== ORDER ON ACCONT FORM		
		public function OrderOnAccountForm() { 
			$fields = new FieldList( 
				//new LabelField('xxxxxxxxxxx'),				
				new EmailField('CustomerEmail','Enter customer Email:'),
				new HiddenField (
				$name = "checker"
				)
			); 
			$actions = new FieldList( 
				new FormAction('OrderOnAccountSubmit', 'Submit') 
			); 
			$validator = new RequiredFields('CustomerEmail');				
			$form = new Form($this, 'OrderOnAccountForm', $fields, $actions, $validator);
				return $form;		
		}// END
		//== SUBMIT
		//== SUBMIT
		//== SUBMIT
		//== SUBMIT		
		public function OrderOnAccountSubmit($data, $form) {
			if($_POST['checker'] == ''){ // spam protection
				$mark = 1;
				foreach($this->AllCustomersInfo() as $value){				
					$code = $value->Code;
					if($data["CustomerEmail"] == $code){ // CODE IS IN DATA BASE OK .. NEXT APPROVED OR NOT ?						
						if($value->Approved == 1){ // APPROVED ALL GOOD == LOGIN
							$this->deleteIpFromDB();
							Session::set('FoodServiceSesssion', $code);
							$data = array('yes1' => 'yes1');
							return json_encode($data);						
							$mark = 0;  
						}else{ // NOT APPROVED WAIT MESSAGE
							$data = array('no1' => 'not_approved');
							return json_encode($data);
						}
					}				
				}
				
				if($mark == 1){						
					return $this->lockedOutWait('no1');				
				}
			}// END $_POST['checker']
		}//==END
		
		//====== END ORDER ON ACCONT FORM
		//============================================================================================
		//============================================================================================
		//============================================================================================
		//============================================================================================
		
		
		
		
		
		
		
		//=========================
		//=== NEED AN ACCOUNT FORM 22222222222222222222222222222222
		//=== NEED AN ACCOUNT FORM
		//=== NEED AN ACCOUNT FORM
		//=== NEED AN ACCOUNT FORM		
		public function NeedAnAccountForm() { 
			$fields = new FieldList( 
				new EmailField('NewEmail','Enter Email to register:'),
				new HiddenField (
				$name = "checker",
				$title= "test",
				$value = ""
				)
			); 
			$actions = new FieldList( 
				new FormAction('NeedAnAccountFormSubmit','Submit')
			); 
			$validator = new RequiredFields('NewEmail');	
			return new Form($this, 'NeedAnAccountForm', $fields, $actions, $validator);
		}
		//== SUBMIT
		//== SUBMIT
		//== SUBMIT
		//== SUBMIT
		public function NeedAnAccountFormSubmit($data, $form) {	
						if($_POST['checker'] == ''){ // spam protection
							
						// GET PDF INFO
						// GET PDF INFO
						// GET PDF INFO
						$pdfsID = AdminProperties::get()->where('InUse = 1');						
							$registerPdfID = $pdfsID[0]->AccountApplicationFormID;
							$debitPdfID = $pdfsID[0]->DirectDebitFormID;
							
								$registerFormPath = File::get()->where('ID = '.$registerPdfID);
								$registerFormPath = $registerFormPath[0]->Filename; // path to register pdf
								
								$debitFormPath = File::get()->where('ID = '.$debitPdfID);
								$debitFormPath = $debitFormPath[0]->Filename; // path to direct debit pdf
						// END GET PDF INFO
						// END GET PDF INFO
						// END GET PDF INFO
						
						
						
						if($_SERVER['HTTP_HOST'] != 'localhost'){
							date_default_timezone_set('NZ');
							$email = new Email();
							$email->setTo($data["NewEmail"]); 
							$email->setFrom('info@rocketproducts.co.nz');  
							$email->setSubject('Rocketproducts Food Service registration'); 
							$messageBody = 'Dear customer, thank you for your interest. Find files attached please. <br />The form can be returned by email or faxed to 04 5689291'; 
							$email->setBody($messageBody); 											
							$email->attachFile($registerFormPath);
							$email->attachFile($debitFormPath);
							$email->send(); // to customer
							
							
							// SEND TO US TO LET US KNOW
							// SEND TO US TO LET US KNOW
							$email = new Email();
							$email->setTo('info@rocketproducts.co.nz'); 
							$email->setFrom('admin@rocketproducts.co.nz');  
							$email->setSubject('Rocketproducts Food Service registration'); 
							$messageBody = 'This is automated message. <br />a User '.$data["NewEmail"].' <br /> just registered at the Food Service section. <br />
							two registration forms were emailed to the user.<br /><br /> If the user is real, he will mail the forms to us and we will need to enter his details at the food service section'; 
							$email->setBody($messageBody); 
							$email->send(); // to customer
							
							
							
								
								// INSERT EMAIL TO DB
								$insert = SQLInsert::create('CustomersAndCode');
								$insert->addRows(array(
									array(
									'Created' => date("Y-m-d H:i:s"),
									'CompanyName' => 'New Entry at '.date('d M Y'),
									'Code' => $data["NewEmail"]
									)
								));
								$insert->execute();
						}
							
							
							
							
							if(Director::is_ajax()) {
								$data = array('yes2' => 'yes2');
								return json_encode($data);
							}else{
								$data = array('no2' => 'no2');
								return json_encode($data);
							}
			
						}// END if($_POST['checker'] == ''){
		}//==END
		
		//======= END NEED AN ACCOUNT FORM
		//========================================================================================
		//========================================================================================
		//========================================================================================
		//========================================================================================
		
		
		
		
		
		

		
		//==============================
		//=== FOOD SERVICE DISCOUNT CODE
		//=== FOOD SERVICE DISCOUNT CODE
		//=== FOOD SERVICE DISCOUNT CODE
		public function FoodServiceDiscountForm() { 
			$fields = new FieldList( 
				new PasswordField('DiscountCode','Enter your Discount code:'),
				new HiddenField (
				$name = "checker"
				)
			); 
			$actions = new FieldList( 
				new FormAction('FoodServiceDiscountSubmit', 'Submit') 
			); 
			$validator = new RequiredFields('DiscountCode');				
			$form = new Form($this, 'FoodServiceDiscountForm', $fields, $actions, $validator);
					return $form;
			}		

		//== SUBMIT
		//== SUBMIT
		//== SUBMIT
		//== SUBMIT
		public function FoodServiceDiscountSubmit($data, $form) {
			if($_POST['checker'] == ''){ // spam protection
				$mark = 1;
				foreach($this->PromotionalCodes() as $value){				
					$code = $value->Code;
					
					if($data["DiscountCode"] == $code && $value->FoodService == '1'){
						
						//== CHECK IF CODE EXPIRED
						$month_ = $value->ActiveMonth;
						$currentMonth = date('F');
						if($month_ != $currentMonth && $month_ != 1){
							$data = array('no4' => 'expired');
							return json_encode($data);
							$mark = 0;
						}
						
						//== ALL GOOD -- LOGIN
						if($mark == 1){	
							Session::set('DiscountCode', $code);
							$data = array('yes4' => 'yes4');
							return json_encode($data); // all fine, redirect
							$mark = 0;
						}					
					}				
				}
				
				if($mark == 1){						
					return $this->lockedOutWait('no4');
				}
			}// END $_POST['checker']
		}		
		// == END FOOD SERVICE DISCOUNT CODE
		// == END FOOD SERVICE DISCOUNT CODE
		// == END FOOD SERVICE DISCOUNT CODE
		//==================================
		
	
}

