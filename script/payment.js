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
            //alert(formatted);
          }
          
          fr.readAsText(files.item(0));
            }

}, false);



function splitJson(jsonParams)
{
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
                            currentcount+=1;

                          
                         }
                        
                });
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
                          amount_type:amount_type
                        },
                        beforeSend:function(){

                            document.getElementById("btnupload").innerHTML = "Loading..";
                        },

                         beforeSend:function(){

                            document.getElementById("btnupload").disabled = true;
                            document.getElementById("btnupload").innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading';
                        },
                        success: function(data){
                            
                            //alert("done");
                            document.getElementById("btnupload").disabled = true;
                            //document.getElementById("btnupload").innerHTML = "Loading";
                            document.getElementById("uploadresult").innerHTML +="<div style='margin-left:20px;color:grey;'>"+data+"</div><hr>";
                            currentcount+=1;

                          
                         }
                        
                });
            }
     
        }
}

function upload(){
	if(formatted == ""){
		alert("no file chosen");
	}else{
        clearjson()
        document.getElementById("uploadresult").innerHTML = "";
		splitJson(formatted);
        //alert("Done");
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
                console.log(data);
                //document.getElementById("uploadresult").innerHTML += data;
             }
            
    });

}
