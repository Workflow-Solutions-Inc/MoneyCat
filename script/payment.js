 var fileToRead = document.getElementById("myjson");
 var formatted = "";

fileToRead.addEventListener("change", function(event) {
    var files = fileToRead.files;
    if (files.length) {
        var files = document.getElementById('myjson').files;
          console.log(files);
          if (files.length <= 0) {
            return false;
          }
          
          var fr = new FileReader();
          
          fr.onload = function(e) { 
          console.log(e);
            var result = JSON.parse(e.target.result);
            formatted = JSON.stringify(result, null, 2);
            document.getElementById('result').innerHTML = formatted;
            document.getElementById('totaljsondata').innerHTML = result.length;
            //alert(formatted);
          }
          
          fr.readAsText(files.item(0));
            }

}, false);

$(function () {
    var pleaseWait = $('#logsmodal'); 

    showPleaseWait3 = function() {
        $('#logsmodal').modal({backdrop: 'static', keyboard: false}) 
        pleaseWait.modal('show');

    };

    hidePleaseWait3 = function () {
        pleaseWait.modal('hide');
    };

//showPleaseWait();
});

$(function () {
    var pleaseWait = $('#pleaseWaitDialog'); 
    
    showPleaseWait = function() {
        $('#pleaseWaitDialog').modal({backdrop: 'static', keyboard: false}) 
        pleaseWait.modal('show');

    };
        
    hidePleaseWait = function () {
        pleaseWait.modal('hide');
    };
    
    //showPleaseWait();
});

$(function () {
    var pleaseWait = $('#pleasereconnectmodal'); 
    
    showPleaseWait2 = function() {
        $('#pleasereconnectmodal').modal({backdrop: 'static', keyboard: false}) 
        pleaseWait.modal('show');

    };
        
    hidePleaseWait2 = function () {
        pleaseWait.modal('hide');
    };
    
    //showPleaseWait();
});

function splitJson(jsonParams)
{
    document.getElementById('progresslabel').innerHTML = "Processing..";
    showPleaseWait();
    var json = $.parseJSON(jsonParams);
    var currentcount = 1;
    for (var i=0;i< json.length;++i)
        {
            //alert(json[i].Fullname);

            contact_Id = json[i].id;
            agreement_number = json[i].agreement_number;
            loan_description = json[i].id;
            loan_amount = json[i].amount;
            account = json[i].account;
            category = json[i].category;
            date_of_payment = json[i].date_of_payment;
            amount_type = json[i].amount_type;

            if(category == 2){
                uploadpayments(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount);
                currentcount += 1;
            }
     
        }

    for (var i=0;i< json.length;++i)
        {
            //alert(json[i].Fullname);

            contact_Id = json[i].id;
            agreement_number = json[i].agreement_number;
            loan_description = json[i].id;
            loan_amount = json[i].amount;
            account = json[i].account;
            category = json[i].category;
            date_of_payment = json[i].date_of_payment;
            amount_type = json[i].amount_type;

            if(category != 2){
                uploadpayments(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount); 
                currentcount += 1;      
            }
     
        }
    
}

function uploadpayments(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount){
    var action = "postdata";
    $.ajax({
                type: 'POST',
                url: 'process/paymentprocess2.php',
                data:{action:action,
                  contact_Id:contact_Id,
                  agreement_number:agreement_number,
                  loan_description:loan_description,
                  loan_amount:loan_amount,
                  account:account,
                  category:category,
                  date_of_payment:date_of_payment,
                  amount_type:amount_type,
                  currentcount:currentcount
                },

                 beforeSend:function(){
                },
                success: function(data){
                     document.getElementById("testresult").innerHTML += data;
                 }
                
        });
}

function currentcountjson(count){
   document.getElementById('currentjsonupload').innerHTML = count;
}

function upload(){
	if(formatted == ""){
		alert("no file chosen");
	}else{
        clearjson()
        document.getElementById("uploadresult").innerHTML = "";
		validateconnectiontoapi(formatted);
        setTimeout(function(){
        getLines();
        //alert("Done");
        },3000);
	}
	
}

function clearjson(){
    $.ajax({
            type: 'POST',
            url: 'process/clearjson.php',
            data:{
            },
            beforeSend:function(){
            },
            success: function(data){
                console.log(data);
             }
            
    });
}

function getLines(){
    $.ajax({
            type: 'POST',
            url: 'process/processpaymentlines.php',
            data:{
            },
            beforeSend:function(){
            },
            success: function(data){
                //console.log(data);
                hidePleaseWait();
                document.getElementById("btnupload").disabled = true;
                document.getElementById("btnupload").style.backgroundColor = "grey";
                //document.getElementById("uploadresult").innerHTML += data;
             }
            
    });
}

function validateconnectiontoapi(formatted){
    $.ajax({
        type: 'GET',
        url: 'process/checkconnection.php',
        data:{},
        beforeSend:function(){        

        },
        success: function(data){
            if(data==1){
                splitJson(formatted);
            }else{
                showPleaseWait2();
            }   
        }
        
    });

}

function validate(){
    if(formatted == ""){
        alert("no file chosen");
    }else{
        document.getElementById("testresult").innerHTML = ""; 
        validatepaymentdata(formatted);

    }

}

function paymentsvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount){
    var action = "postdata";
    $.ajax({
                type: 'POST',
                url: 'process/validatepaymentupload.php',
                data:{action:action,
                  contact_Id:contact_Id,
                  agreement_number:agreement_number,
                  loan_description:loan_description,
                  loan_amount:loan_amount,
                  account:account,
                  category:category,
                  date_of_payment:date_of_payment,
                  amount_type:amount_type,
                  currentcount:currentcount
                },

                 beforeSend:function(){
                },
                success: function(data){
                   
                    document.getElementById("testresult").innerHTML += data;
                    if(currentcount == document.getElementById('totaljsondata').innerHTML){
                        if(document.getElementById('testresult').innerHTML == ""){
                            document.getElementById("btnupload").disabled = false;
                            document.getElementById("btnupload").style.backgroundColor = "lightgreen";
                        }else{
                            document.getElementById("btnupload").disabled = true;
                            document.getElementById("btnupload").style.backgroundColor = "grey";
                        }
                        document.getElementById('progresslabel').innerHTML = "Finalizing..";
                        hidePleaseWait();
                    }
                 }
                
        });
}

function validatepaymentdata(jsonParams)
{
    document.getElementById('progresslabel').innerHTML = "validating..";
    showPleaseWait();
    var json = $.parseJSON(jsonParams);
    var currentcount = 1;
    for (var i=0;i< json.length;++i)
        {
            //alert(json[i].Fullname);

            contact_Id = json[i].id;
            agreement_number = json[i].agreement_number;
            loan_description = json[i].id;
            loan_amount = json[i].amount;
            account = json[i].account;
            category = json[i].category;
            date_of_payment = json[i].date_of_payment;
            amount_type = json[i].amount_type;

            if(category == 2){
                paymentsvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount);
                currentcount+=1;
            }
     
        }

    for (var i=0;i< json.length;++i)
        {
            //alert(json[i].Fullname);

            contact_Id = json[i].id;
            agreement_number = json[i].agreement_number;
            loan_description = json[i].id;
            loan_amount = json[i].amount;
            account = json[i].account;
            category = json[i].category;
            date_of_payment = json[i].date_of_payment;
            amount_type = json[i].amount_type;

            if(category != 2){
                paymentsvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,category,date_of_payment,amount_type,currentcount);  
                currentcount+=1;     
            }
     
        }
    
}
