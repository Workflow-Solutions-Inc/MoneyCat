 var fileToRead = document.getElementById("myjson");
 var formatted = "";


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
          }
          
          fr.readAsText(files.item(0));
            }

}, false);



var timerdate = new Date();
var counter = 1;
var totalnouploaded = 0;
function splitJson(jsonParams)
{
    if(validateconnectiontoapi() == ""){
        showPleaseWait2();
        return;
    }
    document.getElementById('progresslabel').innerHTML = "Processing..";
    showPleaseWait();
    var custName = "";
    var custId = "";
    var custEmail = "";
    var AddressLine = "";
    var custTaxNum = "";
    var phonetype = "";
    var phone_number = "";
    var count = 0;
    const res = JSON.parse(jsonParams);   
    for(var prop in res){
        //alert(res[prop].id);

        custName += res[prop].full_name + "|";
        custId += res[prop].id + "|";
        custEmail += res[prop].email + "|";
        AddressLine += res[prop].full_address+ "|";
        custTaxNum += res[prop].TIN+ "|";
        phonetype += res[prop].phone_type+ "|";
        phone_number += res[prop].phone_number+ "|";
        count++;
        if(count % 1000 == 0){
            looperdata(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number);
            custName = "";
            custId = "";
            custEmail = "";
            AddressLine = "";
            custTaxNum = "";
            phonetype = "";
            phone_number = "";
        }
        
    }

    if(custName != "")
    {
        looperdata(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number);
        
    }

    synccustomer();
    
}

function looperdata(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number){
    var action = "postdata";
            $.ajax({
                        type: 'POST',
                        url: 'process/customerprocess.php',
                        data:{action:action, custName:custName,custEmail:custEmail,
                            AddressLine:AddressLine,custTaxNum:custTaxNum, custId:custId, phonetype:phonetype, phone_number:phone_number},
                        beforeSend:function(){
                            
                        },
                        success: function(data){
                            console.log(data);

                            //document.getElementById("uploadresult").innerHTML +="<div style='margin-left:20px;color:grey'>"+data+"</div><hr>";
                            document.getElementById('progresslabel').innerHTML = "Finalizing..";
                         }
                        
                });
}



function upload(){
	if(formatted == ""){
		alert("no file chosen");
	}else{
        validateconnectiontoapi(formatted);
        document.getElementById("uploadresult").innerHTML = "";
        
	}
	
}

function synccustomer(){
    $.ajax({
        type: 'POST',
        url: 'process/customersyncher.php',
        data:{},
        beforeSend:function(){        

        },
        success: function(data){
            hidePleaseWait();
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


