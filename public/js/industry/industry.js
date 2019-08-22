$(document).ready(function () {
    var segments = location.href.split('/');
    var action = segments[3];

    if(action == 'industries'){
        fetchIndustries();
    }

    var lastOp = 'add';

    //Open SideBar For Adding
    $(document).on('click', '.openDataSidebarForAddingIndustry', function(){
        if (lastOp == "update") {
            $('input[name="industry_name"]').val("");
            $('input[name="industry_name"]').blur();
        }

        if ($('#saveIndustryForm input[name="_method"]').length) {
            $('#saveIndustryForm input[name="_method"]').remove();
        } 
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    //Open Sidebar for Update
    $(document).on('click', '.openDataSidebarForUpdateIndustry', function(){
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="hidden_industry_id"]').val(id);
        if (!$('#saveIndustryForm input[name="_method"]').length) {
            // $('#savePOCForm').append('<input name="_method" value="PUT" hidden />');
        }
        $.ajax({
            type: 'GET',
            url: '/GetSelectedIndustry/' + id,
            success: function(response) {
                var response = JSON.parse(response);
                
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();

                $('input[name="industry_name"]').focus();
                $('input[name="industry_name"]').val(response.industry_name);
                $('input[name="industry_name"]').blur();
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });


    //Save
    $(document).on('click', '#saveIndustry', function(){
        var verif = [];
        $('.required').each(function () {
            $(this).css("border", "0px solid red");
            if ($(this).val() == "") {
                $(this).css("border", "1px solid red");
                verif.push(false);
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Please provide all the required information (*)');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }else {
                verif.push(true);
            }
        });
        //alert(jQuery.inArray(false, verif)); return;
        if(jQuery.inArray(false, verif) != -1){
            return;
        }
        
        $('#saveIndustry').attr('disabled', 'disabled');
        $('#cancelIndustry').attr('disabled', 'disabled');
        $('#saveIndustry').text('Processing..');

         var ajaxUrl = "/save_industry";

        $('#saveIndustryForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveIndustryForm').serialize(),
            cache: false,
            success: function(response) {
                $('#saveIndustry').removeAttr('disabled');
                $('#cancelIndustry').removeAttr('disabled');
                $('#saveIndustry').text('Save');

                if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add industry at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    fetchIndustries();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Saved successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                    if($('#operation').val() == 'add'){
                       $('[name="industry_name"]').val('');
                    }
                    $('#pl-close').click();
                    
                }else if(JSON.parse(response) == "already_exist"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Industry already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#saveIndustry').removeAttr('disabled');
                $('#cancelIndustry').removeAttr('disabled');
                $('#saveIndustry').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });



    //Delete
    $(document).on('click', '.delete_industry', function(){
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $.ajax({
            type: 'GET',
            url: '/delete_industry/'+thisRef.attr('id'),
            data: {
                _token: '{!! csrf_token() !!}'
            },
            success: function(response) {
                if(JSON.parse(response) == 'success'){
                    //fetchMainCats();
                    thisRef.parent().parent().remove();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Successfully deleted.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else{
                    thisRef.removeAttr('disabled');
                    thisRef.text('Delete');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to delete at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    });

});


function fetchIndustries(){
    $.ajax({
        type: 'GET',
        url: '/GetIndustries',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="industriesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Industry Name</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#industriesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#industriesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['industry_name'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateIndustry">Edit</button><a id="' + element['id'] + '" class="btn btn-default red-bg delete_industry" style="background: #e20000!important; color: #fff!important">Delete</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#industriesListTable').DataTable();
        }
    });
}