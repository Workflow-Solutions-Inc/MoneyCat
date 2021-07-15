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

function validateconnectiontoapi(){
    $.ajax({
        type: 'GET',
        url: 'process/checkconnection.php',
        data:{},
        beforeSend:function(){        

        },
        success: function(data){
            if(data!=1){
                showPleaseWait2();
            } 
        }
        
    });
}
//update account
function boxboardDetails(value){
    $("tr").removeClass('active');
    var currentRow=$(value).closest("tr"); 
    $(value).closest("tr").addClass('active');
    name = currentRow.find("td:eq(0)").html();
    code = currentRow.find("td:eq(5)").html();
    desc = currentRow.find("td:eq(2)").html();
    tax = currentRow.find("td:eq(3)").html();
    type = currentRow.find("td:eq(4)").html();
    document.getElementById("accountcode").value = code;
    document.getElementById("accountname").value = name;
    document.getElementById("accountdesc").value = desc;
    document.getElementById("accounttype").value = type;
    //alert(type);
} 

function clearaccounts(){
    document.getElementById("accountcode").value = "";
    document.getElementById("accountname").value = "";
    document.getElementById("accountdesc").value = "";
    document.getElementById("accounttype").value = "2";
}

function addupdate(){
    
    var res = document.getElementById("accountcode").value.split("@");
    var name = document.getElementById("accountname").value;
    if(res == "" || name ==""){
        alert("values must not be null");
        return;
    }else{
        //var desc = document.getElementById("accountdesc").value;
            var desc = res[1];
            var code = res[0];
            var tax = document.getElementById("accounttype").value;
            var type = 
            $.ajax({
                type: 'GET',
                url: 'process/insertupdateaccount.php',
                data:{code:code, name:name, desc:desc, tax:tax},
                beforeSend:function(){

                    
                },
                success: function(data){
                    //alert(data);
                    if(data=="0"){
                        alert("Account Added");
                    }else{
                        alert("Account Updated")
                    }
                    getListofAccounts();
                    clearaccounts();
                  
                 }   
            });
    }
    
}   

function removeaccount(val){
    $.ajax({
        type: 'GET',
        url: 'process/removeaccount.php',
        data:{code:val.id},
        beforeSend:function(){

            
        },
        success: function(data){
            alert("Account "+val.id+" has been removed");
            
            getListofAccounts();
            clearaccounts();
          
         }   
    });
}

function getxeroaccounts(){
    $.ajax({
        type: 'GET',
        url: 'process/getAccountinxero.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            console.log(data);
            document.getElementById("accountcode").innerHTML = data;
            document.getElementById("accountcodeoverpayment").innerHTML = data;
            getselectedopchannel();
          
         }   
    });
}

function updateoverpaymentchannel(){

    var opchannel = document.getElementById("accountcodeoverpayment").value;
    $.ajax({
        type: 'GET',
        url: 'process/updateoverpaymentchannel.php',
        data:{opchannel:opchannel},
        beforeSend:function(){

            
        },
        success: function(data){
            
          
         }   
    });
}

function getselectedopchannel(){
    $.ajax({
        type: 'GET',
        url: 'process/getopchannel.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alerrt(data);
            document.getElementById("accountcodeoverpayment").value = data;
          
         }   
    });
}
// update account

function getTaxrates(){
    $.ajax({
        type: 'GET',
        url: 'process/getTaxRates.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            console.log(data);
            document.getElementById("taxrates").innerHTML = data;
            document.getElementById("taxrates2").innerHTML = data;
            //document.getElementById("accounttax").innerHTML = data;
            getcurrentTaxrates();
            getcurrentTaxrates2();
          
         }   
    });
}


function updateTaxrate(){

    var rate = document.getElementById("taxrates").value;
    $.ajax({
        type: 'GET',
        url: 'process/updateTaxRates.php',
        data:{rate:rate},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            getcurrentTaxrates();
         }   
    });
}
function updateTaxrate2(){

    var rate = document.getElementById("taxrates2").value;
    $.ajax({
        type: 'GET',
        url: 'process/updateTaxRates2.php',
        data:{rate:rate},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            getcurrentTaxrates2();
         }   
    });
}
function updateAccountType(val){

    var code = val.id;
    $.ajax({
        type: 'GET',
        url: 'process/updateAccountType.php',
        data:{code:code},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            getListofAccounts();
            getcurrentTaxrates();
         }   
    });
}

function getcurrentTaxrates(){

    $.ajax({
        type: 'GET',
        url: 'process/getCurrentTaxRates.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("taxrates");
            element.value = data;
         }   
    });
}
function getcurrentTaxrates2(){

    $.ajax({
        type: 'GET',
        url: 'process/getCurrentTaxRates2.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("taxrates2");
            element.value = data;
         }   
    });
}

function getListofAccounts(){
    $.ajax({
        type: 'GET',
        url: 'process/getListofAccounts.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("listofaccounts");
            element.innerHTML = data;
         }   
    });
}

function getListofLoanChannel(){
    $.ajax({
        type: 'GET',
        url: 'process/getListofLoanChannel.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("listofloanchannel");
            element.innerHTML = data;
         }   
    });
}

function getListofLoanXeroChannel(){
    $.ajax({
        type: 'GET',
        url: 'process/getxerobankchannels.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("xerochannel");
            element.innerHTML = data;
         }   
    });
}

function saveLoanChannel(){
    var code = document.getElementById("loanaccountcode").value;
    var xero = document.getElementById("xerochannel").value;
    if(xero =="" || code ==""){
        alert("values must not be null");
        return;
    }else{
       $.ajax({
        type: 'GET',
        url: 'process/inserttoLoanChannel.php',
        data:{code:code, xero:xero},
        beforeSend:function(){

            
        },
        success: function(data){
            alert(data);
            getListofLoanChannel();
            getListofLoanXeroChannel();
            getListofPaymentChannel();
            getPaymentChannelList();
         }   
        }); 
    }
    
}

function removeLoanChannel(val){
    var code = val.id;
    $.ajax({
        type: 'GET',
        url: 'process/removeLoanChannel.php',
        data:{code:code},
        beforeSend:function(){

            
        },
        success: function(data){
            alert(data);
            getListofLoanChannel();
            getListofLoanXeroChannel();
            getListofPaymentChannel();
            getPaymentChannelList();
         }   
    });
}

function getListofPaymentChannel(){
    $.ajax({
        type: 'GET',
        url: 'process/getListofPaymentChannel.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("paymentchannel");
            element.innerHTML = data;
            getSelectedPaymentChannel();
         }   
    });
}

function getSelectedPaymentChannel(){
    $.ajax({
        type: 'GET',
        url: 'process/getSelectedPaymentChannel.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            let element = document.getElementById("paymentchannel");
            element.value = data;
         }   
    });
}

function updatePaymentChannel(){
    //alert(1);
    var code = document.getElementById("paymentchannel").value;
    $.ajax({
        type: 'GET',
        url: 'process/updateSelectedPaymentChannel.php',
        data:{code:code},
        beforeSend:function(){

            
        },
        success: function(data){
            getListofAccounts();
            getcurrentTaxrates();

         }   
    });
}


function getPaymentChannelList(){
    //listofpaymentchannel
    $.ajax({
        type: 'GET',
        url: 'process/getPaymentChannelList.php',
        data:{},
        beforeSend:function(){

            
        },
        success: function(data){
            //alert(data);
            let element = document.getElementById("listofpaymentchannel");
            element.innerHTML = data;
         }   
    });
}
