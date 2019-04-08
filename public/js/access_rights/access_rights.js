$(document).ready(function () {
    var segments = location.href.split('/');
    var action = segments[3];
    if(action == "SCFAR"){
        fetchEmployeesList();
    }else if(action == "access_rights"){
    }
    var access_rights_array = [];
    $('input[name="right_boxes"]:checked').each(function() {
        access_rights_array.push(this.value);
     });

    //Save Controllers or Routes
    $(document).on('click', '.save_btn', function(){
        if($('#route_name').val() == "" || $('#show_up_name').val() == ""){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please fill all required fields(*).');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        $('.save_btn').attr('disabled', 'disabled');
        $('.save_btn').text('Processing..');

        $('#saveRoute').ajaxSubmit({
            type: "POST",
            url: "/saveRoute",
            data: $('#saveRoute').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "success") {
                    $('.save_btn').removeAttr('disabled');
                     $('.save_btn').text('Save');
                     $('#notifDiv').fadeIn();
                     $('#notifDiv').css('background', 'green');
                     $('#notifDiv').text('Route have been added successfully');
                     setTimeout(() => {
                         $('#notifDiv').fadeOut();
                     }, 3000);
                     $('#route_name').val("");
                     $('#show_up_name').val("");
                } else if(JSON.parse(response) == "already_exist"){
                    $('.save_btn').removeAttr('disabled');
                    $('.save_btn').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Route already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#route_name').val("");
                    $('#show_up_name').val("");
                }else{
                    $('.save_btn').removeAttr('disabled');
                    $('.save_btn').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add route at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#route_name').val("");
                    $('#show_up_name').val("");
                }
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    //Save route to an array
    $(document).on('click', '.routes', function(){
        if(access_rights_array.includes($(this).val())){
            access_rights_array.splice(access_rights_array.indexOf($(this).val()), 1);
            $('#saveAccessRights').find('input[name=access_route]').val(" ");
            $('#saveAccessRights').find("input[name=access_route]").val(access_rights_array);
        }else{
            access_rights_array.push($(this).val());
            $('#saveAccessRights').find('input[name=access_route]').val("");
            $('#saveAccessRights').find("input[name=access_route]").val(access_rights_array);
        }
       // console.log(access_rights_array);
    });

    //Save Access Rights against employee
    $(document).on('click', '.save_rights', function(){
        if(!$(".routes").is(":checked")){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please fill all required fields(*).');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        $('.save_rights').attr('disabled', 'disabled');
        $('.save_rights').text('Processing..');

        $('#saveAccessRights').ajaxSubmit({
            type: "POST",
            url: "/saveAccessRights",
            data: $('#saveAccessRights').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "success") {
                    $('.save_rights').removeAttr('disabled');
                     $('.save_rights').text('Save');
                     $('#notifDiv').fadeIn();
                     $('#notifDiv').css('background', 'green');
                     $('#notifDiv').text('Access Rights have been added successfully');
                     setTimeout(() => {
                         $('#notifDiv').fadeOut();
                     }, 3000);
                     $('#route_name').val("");
                     $('#show_up_name').val("");
                } else{
                    $('.save_rights').removeAttr('disabled');
                    $('.save_rights').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add access rights at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#route_name').val("");
                    $('#show_up_name').val("");
                }
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

});

function fetchEmployeesList() {
    $.ajax({
        type: 'GET',
        url: '/GetEmployeeListForRights',
        success: function(response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Client Name</th><th>Email</th><th>Phone#</th><th>Username</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['name'] + '</td><td>' + element['email'] + '</td><td>' + element['phone'] + '</td><td>' + element['username'] + '</td><td><a href="/access_rights/' + element['id'] +'"><button id="' + element['id'] + '" class="btn btn-default btn-line">Create Rights</button></a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}
