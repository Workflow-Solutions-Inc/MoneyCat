
function splitJson(jsonParams)
{
	var json = $.parseJSON(jsonParams);
 	for (var i=0;i< json.length;++i)
        {
        	//alert(json[i].Fullname);

        	custName = json[i].Fullname;
			custFirstname = json[i].FirstName;
			custLastName = json[i].Lastname;
			custEmail = json[i].Email;
			custSkype = null;
			custbankAcc = json[i].BankAcc;
			custTaxNum = json[i].TaxIdNumber;
			custArTaxType = 'INPUT';
			custAPTaxType = 'OUTPUT';




        	var action = "postdata";
        	$.ajax({
						type: 'POST',
						url: 'processor/custprocessor.php',
						data:{action:action, custName:custName,custFirstname:custFirstname,custLastName:custLastName,custEmail:custEmail,custSkype:custSkype,
							custbankAcc:custbankAcc,custTaxNum:custTaxNum,custArTaxType:custArTaxType,custAPTaxType:custAPTaxType},
						beforeSend:function(){

						
						},
						success: function(data){
							alert(data);
					      
					     }
					    
				});
        }
}