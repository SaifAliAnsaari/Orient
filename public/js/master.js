// $(document).ready(function() {
//     $('#example').DataTable();
//     $('#pl-close, .overlay').on('click', function() {
//         $('#product-cl-sec').removeClass('active');
//         $('.overlay').removeClass('active');
//         $('body').toggleClass('no-scroll')
//     });
// });
// $('.form-control').on('focus blur', function(e) {
//         $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
//     })
//     .trigger('blur');

// $(".formselect").select2({width: '100%'});
// //$(".formselect").select2();
// $('.sd-type').select2({
//     createTag: function(params) {
//         var term = $.trim(params.term);
//         if (term === '') {
//             return null;
//         }
//         return {
//             id: term,
//             text: term,
//             newTag: true // add additional parameters
//         }
//     }
// });
// $(".select-cat").select2({width: '100%'});

$(document).ready(function() {
   // $('#example').DataTable();
    $('#example').DataTable( {
        "pageLength": 50
    });
} ); 
	
$(document).ready(function() {
    //$('#example2').DataTable();
    $('#example2').DataTable( {
        "pageLength": 50
    });
});


$(document).ready(function () { 
			
    $('#pl-close, .overlay').on('click', function () {
        $('#product-cl-sec').removeClass('active');
        $('.overlay').removeClass('active');
        $('body').toggleClass('no-scroll')
    });

    $('#productlist01').on('click', function () {
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll')		
    }); 

    var notif_ids = [];
    //Employees Four Notifications
    $(document).on('click', '#NotiFications', function(){
        $('.notifications_list').each(function (){
            notif_ids.push($(this).attr('id'));
        });
        
        $.ajax({
        type: 'POST',
        url: '/read_notif_four',
        data: {
            _token: $('input[name="_token"]').val(),
            notif_ids: notif_ids
        },
        success: function (response) {
            var response = JSON.parse(response);
            //console.log(response);
        }
        });
    });

    //Print CVR for Customer
    $(document).on('click', '.print_page_cust', function(){
        var printContents = document.getElementById('printResult').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    });
    
    
});

$('.form-control').on('focus blur', function (e) {
$(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
})
.trigger('blur');


$(".formselect").select2({width: '100%'});

$('.sd-type').select2({
createTag: function (params) {
var term = $.trim(params.term);

if (term === '') {
return null;
}

return {
id: term,
text: term,
newTag: true // add additional parameters
}
}
});


$(".select-cat").select2({width: '100%'});