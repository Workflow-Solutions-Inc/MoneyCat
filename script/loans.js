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



function splitJson(jsonParams)
{
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

            count++;
            if(count % 1000 == 0){
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

                            document.getElementById("btnupload").disabled = true;
                            document.getElementById("btnupload").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        },
                        success: function(data){
                            
                            //alert("done");
                            document.getElementById("btnupload").disabled = false;
                            document.getElementById("btnupload").innerHTML = "Upload";
                            document.getElementById("uploadresult").innerHTML +="<div style='margin-left:20px;color:grey;'>"+data+"</div><hr>";
                            document.getElementById('currentjsonupload').innerHTML = count;

                          
                         }
                        
                });
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

                            document.getElementById("btnupload").disabled = true;
                            document.getElementById("btnupload").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        },
                        success: function(data){
                            
                            //alert("done");
                            document.getElementById("btnupload").disabled = false;
                            document.getElementById("btnupload").innerHTML = "Upload";
                            document.getElementById("uploadresult").innerHTML +="<div style='margin-left:20px;color:grey;'>"+data+"</div><hr>";
                            document.getElementById('currentjsonupload').innerHTML = count;
                          
                         }
                        
                });
    }
}

function upload(){
    if(formatted == ""){
        alert("no file chosen");
    }else{
        document.getElementById("uploadresult").innerHTML = "";
        splitJson(formatted);
    }
    
}
