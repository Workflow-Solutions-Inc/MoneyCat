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
    looperdata(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number, counter);
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
    looperdata(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number, counter);

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
                document.getElementById("testresult").innerHTML += data;
                document.getElementById('progresslabel').innerHTML = "Finalizing..";
                if(count == document.getElementById('totaljsondata').innerHTML){
                    synccustomer();
                }
                
            },
            success: function(data){
//console.log(data);


}

});
}



function upload(){
    if(formatted == ""){
        alert("no file chosen");
    }else{
        document.getElementById("testresult").innerHTML = "";
        validateconnectiontoapi(formatted);  

    }

}

function validate(){
    if(formatted == ""){
        alert("no file chosen");
    }else{
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
            document.getElementById("btnupload").disabled = true;
            document.getElementById("btnupload").style.backgroundColor = "grey";
            hidePleaseWait();
            document.getElementById('resultlabel').innerHTML = "Finished uploading json file.";
            document.getElementById("resultlabel").style.color = "green";
            showPleaseWait3();
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

function validatecontactdata(jsonParams){
    var counter = 1;
    document.getElementById('progresslabel').innerHTML = "validating..";
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
    var json = $.parseJSON(jsonParams); 
    for(var i=0;i< json.length;i++){
//alert(res[prop].id);

custName += json[i].full_name + "|";
custId += json[i].id + "|";
custEmail += json[i].email + "|";
AddressLine += json[i].full_address+ "|";
custTaxNum += json[i].TIN+ "|";
phonetype += json[i].phone_type+ "|";
phone_number += json[i].phone_number+ "|";
count++;
console.log(count);
if(count % 1000 == 0){
    contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number, count);
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
    contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number, count);

}
}

function contactvalidator(custName, custId, custEmail, AddressLine, custTaxNum, phonetype, phone_number, count){
    var action = "postdata";
    var newdata = "";
    $.ajax({
    type: 'POST',
    url: 'process/validatecontactupload.php',
    data:{action:action, custName:custName,custEmail:custEmail,
        AddressLine:AddressLine,custTaxNum:custTaxNum, custId:custId, phonetype:phonetype, phone_number:phone_number},
        beforeSend:function(){

        },
        success: function(data){
            newdata = data;
        
        
    }

});
    if(count == document.getElementById('totaljsondata').innerHTML){
            if(newdata==""){
            document.getElementById("btnupload").disabled = false;
            document.getElementById("btnupload").style.backgroundColor = "lightgreen";
            document.getElementById('resultlabel').innerHTML = "Validation found without errors, you may now upload the json file.";
            document.getElementById("resultlabel").style.color = "green";
            }else{
                document.getElementById("btnupload").disabled = true;
                document.getElementById("btnupload").style.backgroundColor = "grey";
                document.getElementById('resultlabel').innerHTML = "Validation found with errors";
                document.getElementById("resultlabel").style.color = "red";
            }
            
            document.getElementById('progresslabel').innerHTML = "Finalizing..";
            hidePleaseWait();
            showPleaseWait3();
        }
        
        document.getElementById("testresult").innerHTML += newdata;
    console.log(count);
}


