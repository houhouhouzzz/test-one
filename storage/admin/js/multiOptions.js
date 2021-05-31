var dom = $('.options');


// change(dom.val());

$(document).on('change','#app .options',function(){
    console.log($(this).val());
    // change($(this).val());
});

// function change(val){
//     if(val == 1){
//         dom.parents("#tab-form-1").find('.form-group:nth-child(2)').show().next().hide();
//     }else{
//         dom.parents("#tab-form-1").find('.form-group:nth-child(3)').show().prev().hide();
//     }
// }
