<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payment_gateway";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tid = $_POST['tid'];
    $merchant_id = $_POST['merchant_id'];
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $redirect_url = $_POST['redirect_url'];
    $cancel_url = $_POST['cancel_url'];
    $language = $_POST['language'];
    
    $sql = "INSERT INTO transactions (tid, merchant_id, order_id, amount, currency, redirect_url, cancel_url, language) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdssss", $tid, $merchant_id, $order_id, $amount, $currency, $redirect_url, $cancel_url, $language);
    
    if ($stmt->execute()) {
        echo "<script>alert('Transaction recorded successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<script>
window.onload = function() {
    var d = new Date().getTime();
    document.getElementById("tid").value = d;
};
</script>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #6dd5ed, #2193b0);
    background-attachment: fixed;
    margin: 0;
    padding: 20px;
    position: relative;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    z-index: -1;
}

form {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 20px auto;
    backdrop-filter: blur(5px);
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

caption {
    font-size: 28px;
    color: #2193b0;
    margin-bottom: 20px;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

td {
    padding: 12px;
    border: none;
    background-color: rgba(255, 255, 255, 0.7);
    transition: background-color 0.3s ease;
}

tr:hover td {
    background-color: rgba(255, 255, 255, 0.9);
}

input[type="text"], select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus, select:focus {
    border-color: #2193b0;
    outline: none;
}

input[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #2980b9;
}

.section-header {
    background-color: rgba(33, 147, 176, 0.1);
    font-weight: bold;
    color: #2193b0;
}

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.radio-group input[type="radio"] {
    margin-right: 5px;
}
</style>
</head>
<body>
<form method="POST" name="customerData" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<table width="40%" height="100" border='1' align="center"><caption><font size="4" color="blue"><b>Integration Kit</b></font></caption></table>
<table width="50%" height="100" border='1' align="center">
<tr>
<td>Parameter Name:</td><td>Parameter Value:</td>
</tr>
<tr>
<td colspan="2"> Compulsory information</td>
</tr>
<tr>
<td>TID:</td><td><input type="text" name="tid" id="tid" readonly /></td>
</tr>
<tr>
<td>Merchant Id:</td><td><input type="text" name="merchant_id" value="3041682"/></td>
</tr>
<tr>
<td>Order Id:</td><td><input type="text" name="order_id" value="123654789"/></td>
</tr>
<tr>
<td>Amount:</td><td><input type="text" name="amount" value="100.00"/></td>
</tr>
<tr>
<td>Currency:</td><td><input type="text" name="currency" value="INR"/></td>
</tr>
<tr>
<td>Redirect URL:</td><td><input type="text" name="redirect_url" value="http://localhost/ccavResponseHandler.php"/></td>
</tr>
<tr>
<td>Cancel URL:</td><td><input type="text" name="cancel_url" value="http://localhost/ccavResponseHandler.php"/></td>
</tr>
<tr>
<td>Language:</td><td><input type="text" name="language" value="EN"/></td>
</tr>
<tr>
<td colspan="2">Billing information(optional):</td>
</tr>
<tr>
<td colspan="2">Payment information:</td>
</tr>
<tr>
<td> Payment Option: </td> 
<td> 
<input class="payOption" type="radio" name="payment_option" value="OPTCRDC">Credit Card
<input class="payOption" type="radio" name="payment_option" value="OPTDBCRD">Debit Card  <br/>
<input class="payOption" type="radio" name="payment_option" value="OPTNBK">Net Banking 
<input class="payOption" type="radio" name="payment_option" value="OPTCASHC">Cash Card <br/>
<input class="payOption" type="radio" name="payment_option" value="OPTMOBP">Mobile Payments
<input class="payOption" type="radio" name="payment_option" value="OPTEMI">EMI
<input class="payOption" type="radio" name="payment_option" value="OPTWLT">Wallet
</td>
</tr>

<!-- #region  <tr >
					 <td  colspan="2">
					  <div id="emi_div" style="display: none">
						 <table border="1" width="100%">
						 <tr> <td colspan="2">EMI Section </td></tr>
						 <tr> <td> Emi plan id: </td>
							<td><input readonly="readonly" type="text" id="emi_plan_id"  name="emi_plan_id" value=""/> </td>
						 </tr>
						 <tr> <td> Emi tenure id: </td>
							<td><input readonly="readonly" type="text" id="emi_tenure_id" name="emi_tenure_id" value=""/>  </td>
						 </tr>
						 <tr><td>Pay Through</td>
							 <td>
								 <select name="emi_banks"  id="emi_banks">
								 </select>
							 </td>
						</tr>
						<tr><td colspan="2">
							 <div id="emi_duration" class="span12">
								<span class="span12 content-text emiDetails">EMI Duration</span>
								<table id="emi_tbl" cellpadding="0" cellspacing="0" border="1" >
								</table> 
							</div>
							</td>
						</tr>
						<tr>
							 <td id="processing_fee" colspan="2">
							</td>
						</tr>
						</table>
					</div>
					</td>
					</tr>
					<!-- EMI section end -->
					 
					 
					 <tr> <td> Card Type: </td>
						 <td><input type="text" id="card_type" name="card_type" value="" readonly="readonly"/></td>
					 </tr>
					
					<tr> <td> Card Name: </td>
						 <td> <select name="card_name" id="card_name"> <option value="">Select Card Name</option> </select> </td>
					</tr>
					
					<tr> <td> Data Accepted At </td>
						 <td><input type="text" id="data_accept" name="data_accept" readonly="readonly"/></td>
					</tr>
					 
					 <tr> <td> Card Number: </td>
						<td> <input type="text" id="card_number" name="card_number" value=""/>e.g. 4111111111111111 </td>
					 </tr>
					  <tr> <td> Expiry Month: </td>
						   <td> <input type="text" name="expiry_month" value=""/>e.g. 07 </td>
					 </tr>
					  <tr> <td> Expiry Year: </td>
							 <td> <input type="text" name="expiry_year" value=""/>e.g. 2027</td>
					 </tr>
					  <tr> <td> CVV Number:</td>
						   <td> <input type="text" name="cvv_number" value=""/>e.g. 328</td>
					 </tr>
					 <tr> <td> Issuing Bank:</td>
						<td><input type="text" name="issuing_bank" value=""/>e.g. State Bank Of India</td>
					 </tr>
				 <tr> 
					<td> Mobile Number:</td>
							<td><input type="text" name="mobile_number" value=""/>e.g. 9770707070</td>
					 </tr>
				<tr> 
					<td> MMID:</td>
							<td><input type="text" name="mm_id" value=""/>e.g. 1234567</td>
					 </tr>
					 <tr> 
						<td> OTP:</td>
								<td><input type="text" name="otp" value=""/>e.g. 123456</td>
						 </tr>
		
				 <tr> 
					<td> Promotions:</td>
							<td> <select name="promo_code" id="promo_code"> <option value="">All Promotions &amp; Offers</option> </select> </td>
					 </tr>-->
<tr>
<td></td><td><INPUT TYPE="submit" value="CheckOut"></td>
</tr>
</table>
</form>

<script src="jquery-1.7.2.min.js"></script>
<script type="text/javascript">
  $(function(){
 
    var jsonData;
			var access_code="AVMV33KK98BT01VMTB" // shared by CCAVENUE 
		  var amount="6000.00";
			var currency="INR";
			
		  $.ajax({
			   url:'https://secure.ccavenue.com/transaction/transaction.do?command=getJsonData&access_code='+access_code+'&currency='+currency+'&amount='+amount,
			   dataType: 'jsonp',
			   jsonp: false,
			   jsonpCallback: 'processData',
			   success: function (data) { 
					 jsonData = data;
					 // processData method for reference
					 processData(data); 
			 // get Promotion details
					 $.each(jsonData, function(index,value) {
				if(value.Promotions != undefined  && value.Promotions !=null){  
					var promotionsArray = $.parseJSON(value.Promotions);
							   $.each(promotionsArray, function() {
						console.log(this['promoId'] +" "+this['promoCardName']);	
						var	promotions=	"<option value="+this['promoId']+">"
						+this['promoName']+" - "+this['promoPayOptTypeDesc']+"-"+this['promoCardName']+" - "+currency+" "+this['discountValue']+"  "+this['promoType']+"</option>";
						$("#promo_code").find("option:last").after(promotions);
					});
				}
			});
			   },
			   error: function(xhr, textStatus, errorThrown) {
				   alert('An error occurred! ' + ( errorThrown ? errorThrown :xhr.status ));
				   //console.log("Error occured");
			   }
			   });
			   
			   $(".payOption").click(function(){
				   var paymentOption="";
				   var cardArray="";
				   var payThrough,emiPlanTr;
				var emiBanksArray,emiPlansArray;
				   
				   paymentOption = $(this).val();
				   $("#card_type").val(paymentOption.replace("OPT",""));
				   $("#card_name").children().remove(); // remove old card names from old one
				$("#card_name").append("<option value=''>Select</option>");
				   $("#emi_div").hide();
				   
				   //console.log(jsonData);
				   $.each(jsonData, function(index,value) {
					   //console.log(value);
					  if(paymentOption !="OPTEMI"){
						 if(value.payOpt==paymentOption){
							cardArray = $.parseJSON(value[paymentOption]);
							$.each(cardArray, function() {
								$("#card_name").find("option:last").after("<option class='"+this['dataAcceptedAt']+" "+this['status']+"'  value='"+this['cardName']+"'>"+this['cardName']+"</option>");
							});
						 }
					  }
					  
					  if(paymentOption =="OPTEMI"){
						  if(value.payOpt=="OPTEMI"){
							  $("#emi_div").show();
							  $("#card_type").val("CRDC");
							  $("#data_accept").val("Y");
							  $("#emi_plan_id").val("");
							$("#emi_tenure_id").val("");
							$("span.emi_fees").hide();
							  $("#emi_banks").children().remove();
							  $("#emi_banks").append("<option value=''>Select your Bank</option>");
							  $("#emi_tbl").children().remove();
							  
							emiBanksArray = $.parseJSON(value.EmiBanks);
							emiPlansArray = $.parseJSON(value.EmiPlans);
							$.each(emiBanksArray, function() {
								payThrough = "<option value='"+this['planId']+"' class='"+this['BINs']+"' id='"+this['subventionPaidBy']+"' label='"+this['midProcesses']+"'>"+this['gtwName']+"</option>";
								$("#emi_banks").append(payThrough);
							});
							
							emiPlanTr="<tr><td>&nbsp;</td><td>EMI Plan</td><td>Monthly Installments</td><td>Total Cost</td></tr>";
								
							$.each(emiPlansArray, function() {
								emiPlanTr=emiPlanTr+
								"<tr class='tenuremonth "+this['planId']+"' id='"+this['tenureId']+"' style='display: none'>"+
									"<td> <input type='radio' name='emi_plan_radio' id='"+this['tenureMonths']+"' value='"+this['tenureId']+"' class='emi_plan_radio' > </td>"+
									"<td>"+this['tenureMonths']+ "EMIs. <label class='merchant_subvention'>@ <label class='emi_processing_fee_percent'>"+this['processingFeePercent']+"</label>&nbsp;%p.a</label>"+
									"</td>"+
									"<td>"+this['currency']+"&nbsp;"+this['emiAmount'].toFixed(2)+
									"</td>"+
									"<td><label class='currency'>"+this['currency']+"</label>&nbsp;"+ 
										"<label class='emiTotal'>"+this['total'].toFixed(2)+"</label>"+
										"<label class='emi_processing_fee_plan' style='display: none;'>"+this['emiProcessingFee'].toFixed(2)+"</label>"+
										"<label class='planId' style='display: none;'>"+this['planId']+"</label>"+
									"</td>"+
								"</tr>";
							});
							$("#emi_tbl").append(emiPlanTr);
						 } 
					  }
				   });
				   
			 });
	   
		  
		  $("#card_name").click(function(){
			  if($(this).find(":selected").hasClass("DOWN")){
				  alert("Selected option is currently unavailable. Select another payment option or try again later.");
			  }
			  if($(this).find(":selected").hasClass("CCAvenue")){
				  $("#data_accept").val("Y");
			  }else{
				  $("#data_accept").val("N");
			  }
		  });
			  
		 // Emi section start      
			  $("#emi_banks").live("change",function(){
				   if($(this).val() != ""){
						   var cardsProcess="";
						   $("#emi_tbl").show();
						   cardsProcess=$("#emi_banks option:selected").attr("label").split("|");
						$("#card_name").children().remove();
						$("#card_name").append("<option value=''>Select</option>");
						$.each(cardsProcess,function(index,card){
							$("#card_name").find("option:last").after("<option class=CCAvenue value='"+card+"' >"+card+"</option>");
						});
						$("#emi_plan_id").val($(this).val());
						$(".tenuremonth").hide();
						$("."+$(this).val()+"").show();
						$("."+$(this).val()).find("input:radio[name=emi_plan_radio]").first().attr("checked",true);
						$("."+$(this).val()).find("input:radio[name=emi_plan_radio]").first().trigger("click");
						 
						 if($("#emi_banks option:selected").attr("id")=="Customer"){
							$("#processing_fee").show();
						 }else{
							$("#processing_fee").hide();
						 }
						 
					}else{
						$("#emi_plan_id").val("");
						$("#emi_tenure_id").val("");
						$("#emi_tbl").hide();
					}
					
					
					
					$("label.emi_processing_fee_percent").each(function(){
						if($(this).text()==0){
							$(this).closest("tr").find("label.merchant_subvention").hide();
						}
					});
					
			 });
			 
			 $(".emi_plan_radio").live("click",function(){
				var processingFee="";
				$("#emi_tenure_id").val($(this).val());
				processingFee=
						"<span class='emi_fees' >"+
							 "Processing Fee:"+$(this).closest('tr').find('label.currency').text()+"&nbsp;"+
							 "<label id='processingFee'>"+$(this).closest('tr').find('label.emi_processing_fee_plan').text()+
							 "</label><br/>"+
								"Processing fee will be charged only on the first EMI."+
						"</span>";
				 $("#processing_fee").children().remove();
				 $("#processing_fee").append(processingFee);
				 
				 // If processing fee is 0 then hiding emi_fee span
				 if($("#processingFee").text()==0){
					 $(".emi_fees").hide();
				 }
				  
			});
			
			
			$("#card_number").focusout(function(){
				/*
				 emi_banks(select box) option class attribute contains two fields either allcards or bin no supported by that emi 
				*/ 
				if($('input[name="payment_option"]:checked').val() == "OPTEMI"){
					if(!($("#emi_banks option:selected").hasClass("allcards"))){
					  if(!$('#emi_banks option:selected').hasClass($(this).val().substring(0,6))){
						  alert("Selected EMI is not available for entered credit card.");
					  }
				   }
			   }
			  
			});
				
				
		// Emi section end 		
	   
	   
	   // below code for reference 
	 
	   function processData(data){
			 var paymentOptions = [];
			 var creditCards = [];
			 var debitCards = [];
			 var netBanks = [];
			 var cashCards = [];
			 var mobilePayments=[];
			 $.each(data, function() {
				  // this.error shows if any error   	
				 console.log(this.error);
				  paymentOptions.push(this.payOpt);
				  switch(this.payOpt){
					case 'OPTCRDC':
						var jsonData = this.OPTCRDC;
						 var obj = $.parseJSON(jsonData);
						 $.each(obj, function() {
							 creditCards.push(this['cardName']);
						});
					break;
					case 'OPTDBCRD':
						var jsonData = this.OPTDBCRD;
						 var obj = $.parseJSON(jsonData);
						 $.each(obj, function() {
							 debitCards.push(this['cardName']);
						});
					break;
					  case 'OPTNBK':
						  var jsonData = this.OPTNBK;
						var obj = $.parseJSON(jsonData);
						$.each(obj, function() {
							 netBanks.push(this['cardName']);
						});
					break;
					
					case 'OPTCASHC':
					  var jsonData = this.OPTCASHC;
					  var obj =  $.parseJSON(jsonData);
					  $.each(obj, function() {
						  cashCards.push(this['cardName']);
					  });
					 break;
					   
					  case 'OPTMOBP':
					  var jsonData = this.OPTMOBP;
					  var obj =  $.parseJSON(jsonData);
					  $.each(obj, function() {
						  mobilePayments.push(this['cardName']);
					  });
				  }
				  
				});
			   
			   //console.log(creditCards);
			  // console.log(debitCards);
			  // console.log(netBanks);
			  // console.log(cashCards);
			 //  console.log(mobilePayments);
				
		  }

  });
</script>
</body>
</html>
