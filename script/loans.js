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
    var contact_Id = "";
    var agreement_number = "";
    var loan_description = "";
    var loan_amount = "";
    var account = "";
    var bankaccount = "";
    var Date_of_loan = "";
    var Due_Date_of_loan = "";
    var category = "";
    var amount_type = "";
    var count = 0;
    var json = $.parseJSON(jsonParams);
    var currentcount = 1;
    //alert(json.length);
    for (var i=0;i< json.length;i++)
        {
            //alert(1);
            //alert(json[i].Fullname);
            contact_Id += json[i].contact_Id+ "|";
            agreement_number += json[i].agreement_number+ "|";
            loan_description += json[i].loan_type+ "|";
            loan_amount += json[i].loan_amount+ "|";
            account += json[i].account+ "|";
            bankaccount += json[i].disbursement_channel+ "|";
            category += json[i].category+ "|";
            Date_of_loan += json[i].date_disbursed+ "|";
            Due_Date_of_loan += json[i].due_date_of_loan+ "|";
            amount_type += json[i].amount_type+ "|";
            showPleaseWait();
            count++;
            if(count % 1000 == 0){
            uploadloans(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type);
            contact_Id = "";
            agreement_number = "";
            loan_description = "";
            loan_amount = "";
            account = "";
            bankaccount = "";
            Date_of_loan = "";
            Due_Date_of_loan = "";
            category = "";
            amount_type = "";
            }
            
        }
        //alert("Done");
    if(contact_Id != "")
    {
            uploadloans(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type);
    }
}

var counter = 0;
function uploadloans(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type){
    var action = "postdata";
    $.ajax({
                type: 'POST',
                url: 'process/loanprocess2.php',
                data:{action:action, 
                  contact_Id:contact_Id,
                  agreement_number:agreement_number,
                  loan_description:loan_description,
                  loan_amount:loan_amount,
                  account:account,
                  bankaccount:bankaccount,  
                  Date_of_loan:Date_of_loan,
                  Due_Date_of_loan:Due_Date_of_loan,
                  category:category,
                  amount_type:amount_type
                },
                beforeSend:function(){

                },
                success: function(data){
                    document.getElementById("testresult").innerHTML += data;
                    counter += 1000;
                    if(counter >= document.getElementById('totaljsondata').innerHTML){
                        successupload(); 
                    }
                            
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

function loanvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type){
    var action = "postdata";
    $.ajax({
                type: 'POST',
                url: 'process/validateloanupload.php',
                data:{action:action, 
                  contact_Id:contact_Id,
                  agreement_number:agreement_number,
                  loan_description:loan_description,
                  loan_amount:loan_amount,
                  account:account,
                  bankaccount:bankaccount,  
                  Date_of_loan:Date_of_loan,
                  Due_Date_of_loan:Due_Date_of_loan,
                  category:category,
                  amount_type:amount_type
                },
                beforeSend:function(){

                },
                success: function(data){
                    //alert(data);
                    document.getElementById("testresult").innerHTML += data;
                    counter2 += 1000;
                    if(counter2 >= document.getElementById('totaljsondata').innerHTML){
                        if(document.getElementById("testresult").innerHTML == ""){
                            foundwithouterrors();
                        }else{
                            foundwitherrors();
                        }
                        hidePleaseWait();
                        showPleaseWait3();
                    }

                }
                
        });
}

function validate(){
    if(formatted == ""){
        alert("no file chosen");
    }else{
        document.getElementById("testresult").innerHTML = ""; 
        validateloandata(formatted);
    }

}

var counter2 = 0;
function validateloandata(jsonParams)
{
    document.getElementById('progresslabel').innerHTML = "Validating...";
    var contact_Id = "";
    var agreement_number = "";
    var loan_description = "";
    var loan_amount = "";
    var account = "";
    var bankaccount = "";
    var Date_of_loan = "";
    var Due_Date_of_loan = "";
    var category = "";
    var amount_type = "";
    var count = 0;
    var json = $.parseJSON(jsonParams);
    var currentcount = 1;
    //alert(json.length);
    for (var i=0;i< json.length;i++)
        {
            //alert(1);
            //alert(json[i].Fullname);
            contact_Id += json[i].contact_Id+ "|";
            agreement_number += json[i].agreement_number+ "|";
            loan_description += json[i].loan_type+ "|";
            loan_amount += json[i].loan_amount+ "|";
            account += json[i].account+ "|";
            bankaccount += json[i].disbursement_channel+ "|";
            category += json[i].category+ "|";
            Date_of_loan += json[i].date_disbursed+ "|";
            Due_Date_of_loan += json[i].due_date_of_loan+ "|";
            amount_type += json[i].amount_type+ "|";
            showPleaseWait();
            count++;
            if(count % 1000 == 0){
            loanvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type);
            contact_Id = "";
            agreement_number = "";
            loan_description = "";
            loan_amount = "";
            account = "";
            bankaccount = "";
            Date_of_loan = "";
            Due_Date_of_loan = "";
            category = "";
            amount_type = "";
            }
            
        }
        //alert("Done");
    if(contact_Id != "")
    {
            loanvalidator(contact_Id,agreement_number,loan_description,loan_amount,account,bankaccount,Date_of_loan,Due_Date_of_loan,category,amount_type);
    }
}

function validateconnectiontoapi2(){
    $.ajax({
        type: 'GET',
        url: 'process/checkconnection.php',
        data:{},
        beforeSend:function(){        

        },
        success: function(data){
            if(data==1){
                
            }else{
                showPleaseWait2();
            }   
        }

    });
}

function foundwitherrors(){
    document.getElementById("btnupload").disabled = true;
    document.getElementById("btnupload").style.backgroundColor = "grey";
    document.getElementById('resultlabel').innerHTML = "Validation done with errors. Please fix them first and validate again.";
    document.getElementById("resultlabel").style.color = "red";
}

function foundwithouterrors(){
    document.getElementById("btnupload").disabled = false;
    document.getElementById("btnupload").style.backgroundColor = "lightgreen";
    document.getElementById('resultlabel').innerHTML = "Validation found without errors, you may now upload the json file.";
    document.getElementById("resultlabel").style.color = "green";
}

function successupload(){
    document.getElementById('progresslabel').innerHTML = "Finalizing.."; 
    document.getElementById("btnupload").disabled = true;
    document.getElementById("btnupload").style.backgroundColor = "grey";
    hidePleaseWait(); 
    document.getElementById('resultlabel').innerHTML = "Upload Successful.";
    document.getElementById("resultlabel").style.color = "green";
    showPleaseWait3();  
}

