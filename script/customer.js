var fileToRead = document.getElementById("myjson");
var formatted = "";

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
            //alert(result.length);
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
    document.getElementById('progresslabel').innerHTML = "Processing..";
    showPleaseWait();
    console.log(timerdate.getDate());
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

//synccustomer();

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
            document.getElementById("testresult").innerHTML += data;
            counter+=1000;
            if(counter2 > document.getElementById('totaljsondata').innerHTML){
                synccustomer();
            }
            //document.getElementById('progresslabel').innerHTML = "Finalizing..";
            }

    });
}



function upload(){
    if(formatted == ""){
        alert("No File Chosen");
    }else{
        counter = 0;
        document.getElementById("testresult").innerHTML = "";
        validateconnectiontoapi(formatted);  

    }

}

function validate(){
    if(formatted == ""){
        alert("No File Chosen");
    }else{
        counter2 = 0;
        document.getElementById("testresult").innerHTML = ""; 
        validatecontactdata(formatted);
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
            successupload();
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

var flag1 = true;
var counter2 = 0;
function validatecontactdata(jsonParams){
    var counter = 1;
    document.getElementById('progresslabel').innerHTML = "Validating...";
    showPleaseWait();
    console.log(timerdate.getDate());
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
            contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number);
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
        contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number);

    }
    
}

function contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number){
    var action = "postdata";
    flag2 = true;
    
    $.ajax({
    type: 'POST',
    url: 'process/validatecontactupload.php',
    data:{action:action, custName:custName,custEmail:custEmail,
        AddressLine:AddressLine,custTaxNum:custTaxNum, custId:custId, phonetype:phonetype, phone_number:phone_number,counter2:counter2},
        beforeSend:function(){

        },
        success: function(data){
        counter2+=1000;
        if(data==""){
            
        }else{
            flag1 = false;
        }
        if(flag1==false){
            
            foundwitherrors();
        }else{
            foundwithouterrors();
        }
        console.log(flag1);
        document.getElementById("testresult").innerHTML += data;
        console.log(counter2);
        if(counter2 >= document.getElementById('totaljsondata').innerHTML){
                hidePleaseWait();
                showPleaseWait3();
            }
        //document.getElementById('progresslabel').innerHTML = "Finalizing..";
        
        
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
    document.getElementById('resultlabel').innerHTML = "Validation done successfully. You may now proceed with the Upload.";
    document.getElementById("resultlabel").style.color = "green";
}

function successupload(){
    document.getElementById("btnupload").disabled = true;
    document.getElementById("btnupload").style.backgroundColor = "grey";
    hidePleaseWait();
    document.getElementById('resultlabel').innerHTML = "Upload successful.";
    document.getElementById("resultlabel").style.color = "green";
    showPleaseWait3();
}



