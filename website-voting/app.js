$(document).ready(function(){
    var selectedProd = null;
    $('#submitBtn').on('click',function (){
       if($("input[type='radio'][name='poty']").is(':checked')){

           selectedProd = $("input[type='radio'][name='poty']:checked").val();

           $.ajax({
               url:'prcs-vote.php',
               type:'post',
               data:{votedProd:selectedProd},
               beforeSend:function (){
                 $('.contentArea').html('Processing...');
               },
               success:function (resp){
                   $('.contentArea').html(resp);
               }
           })

       }else{
           alert('Select the product you want to vote for');
       }

    })
})
