$(document).ready(function () {

    var segments = location.href.split('/');
    var action = segments[3];
    var resolve_id = '';


    if (action == 'complaints_settings') {
        fetchcomplain_types();
    } else if (action == 'complaints_list') {
        fetchcomplain_List()
    } else if(action == 'resolved_complains'){
        fetchresolvedcomplains();
    }



    //Open Modal For adding Complain Type
    $(document).on('click', '.open_modal_for_add', function () {
        if ($('#operation').val() == 'update') {
            $('#complain_head').val('');
            $('#complain_tat').val('');
            $('#complain_assign_to').val("-1").trigger('change');
            $('#operation').val('add');
        }
    });

    
    //Reverse action if . entered by user in TAT
    // $(document).on('keyup', '#complain_tat', function(){
    //     alert($(this).val().substr($(this).val().length-1));
    //     if($(this).val().substr($(this).val().length-1) == '.'){
    //         alert('asa');
    //     }
    // });


    //Save Complaint Type
    $(document).on('click', '.save_complaint_type', function () {
        var verif = [];
        $('.required').each(function () {
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
            } else if ($(this).val() == 0 || $(this).val() == null) {
                $(this).parent().css("border", "1px solid red");
                verif.push(false);
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Please provide all the required information (*)');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            } else {
                verif.push(true);
            }
        });

        if (jQuery.inArray(false, verif) != -1) {
            return;
        }

        $('.cancel_modal').attr('disabled', 'disabled');
        $('.save_complaint_type').attr('disabled', 'disabled');
        $('.save_complaint_type').text('Processing...');
        var id = $(this).attr('id');

        $.ajax({
            type: 'POST',
            url: '/save_complain_type',
            data: {
                _token: $('input[name="_token"]').val(),
                head: $('#complain_head').val(),
                tat: $('#complain_tat').val(),
                id: id,
                operation: $('#operation').val(),
                assign_to: $('#complain_assign_to').val()
            },
            success: function (response) {

                $('.cancel_modal').removeAttr('disabled');
                $('.save_complaint_type').removeAttr('disabled');
                $('.save_complaint_type').text('Save');
                // console.log(response);
                // return;

                if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Complain type at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('.cancel_modal').click();
                    fetchcomplain_types();
                    $('#complain_head').val('');
                    $('#complain_tat').val('');
                    $('#complain_assign_to').val("-1").trigger('change');
                }
            }
        });

    });


    //Open Modal for Update
    $(document).on('click', '.add_complain_type', function () {
        $("#complain_assign_to").val('-1').trigger('change');
        $('#operation').val('update');
        $('#modal_loader').show();
        var id = $(this).attr('id');
        //alert(id);
        $.ajax({
            type: 'GET',
            url: '/get_complain_type_data',
            data: {
                _token: '{!! csrf_token() !!}',
                id: id
            },
            success: function (response) {
                //debugger;
                $('#modal_loader').hide();
                var response = JSON.parse(response);
                //console.log(response);
                $('#complain_head').val(response.core.complain_head);
                $('#complain_head').focus();
                $('#complain_tat').val(response.core.complain_tat);
                $('#complain_tat').focus();
                $('.save_complaint_type').attr('id', response.core.id);

                for (var i = 0; i < response.names.length; i++) {
                    var newState = new Option(response.names[i]['name'], response.names[i]['ids'], true, true);
                    $("#complain_assign_to").append(newState).trigger('change');
                }
            }
        });
    });


    //Delete Complain Type
    $(document).on('click', '.delete_type', function () {
        var id = $(this).attr('id');
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');

        $.ajax({
            type: 'GET',
            url: '/delete_complain_type',
            data: {
                _token: '{!! csrf_token() !!}',
                id: id
            },
            success: function (response) {
                thisRef.removeAttr('disabled');
                if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to Delete Complain type at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Deleted Successfully.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    fetchcomplain_types();
                }
            }
        });
    });




    //Generate Complain
    $(document).on('click', '.generate_complain', function () {
        var verif = [];
        $('.required').each(function () {
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
            } else if ($(this).val() == 0 || $(this).val() == null) {
                $(this).parent().css("border", "1px solid red");
                verif.push(false);
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Please provide all the required information (*)');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            } else {
                verif.push(true);
            }
        });

        if (jQuery.inArray(false, verif) != -1) {
            return;
        }

        $('.cancel_complain').attr('disabled', 'disabled');
        $('.generate_complain').attr('disabled', 'disabled');
        $('.generate_complain').text('Processing....');
        $.ajax({
            type: 'POST',
            url: '/save_complain',
            data: {
                _token: $('input[name="_token"]').val(),
                customers: $('#customers').val(),
                complain_type: $('#complain_type').val(),
                description: $('#description').val()
            },
            success: function (response) {

                $('.cancel_complain').removeAttr('disabled');
                $('.generate_complain').removeAttr('disabled');
                $('.generate_complain').text('Generate');

                if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Complain at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Complain added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#customers').val('0').trigger('change');
                    $('#complain_type').val('0').trigger('change');
                    $('#description').val("");
                    $('#description').text("");
                }
            }
        });


    });


    //Resolve_complain
    $(document).on('click', '.resolve_complain', function(){
        resolve_id = '';
        resolve_id = $(this).attr('id');
    });
    $(document).on('click', '.resolve_modal', function(){
        var id = resolve_id;
        if($('#remarks_resolve').val() == ''){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Enter Remarks First.');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }
        
        $('.cancel_modal').attr('disabled', 'disabled');
        $('.resolve_modal').attr('disabled', 'disabled');
        $('.resolve_modal').text('Processing....');
        $.ajax({
            type: 'POST',
            url: '/resolve_complain',
            data: {
                _token: $('input[name="_token"]').val(),
                id: id,
                remarks: $('#remarks_resolve').val()
            },
            success: function (response) {

                $('.cancel_modal').removeAttr('disabled');
                $('.resolve_modal').removeAttr('disabled');
                $('.resolve_modal').text('Resolve');

                if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to Resolve Complain at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Complain Resolved successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#remarks_resolve').val("");
                    $('#remarks_resolve').text("");
                    $('.cancel_modal').click();
                    fetchcomplain_List();
                }
            }
        });
    });

    //Open Modal to view Detail
    $(document).on('click', '.view_detail_modal', function(){
        $('#customer_detail_modal').val('');
        $('#complain_detail_modal').val('');
        $('#remarks_detail_modal').val('');
        var id = $(this).attr('id');
        $.ajax({
            type: 'GET',
            url: '/get_complain_detail',
            data: {
                _token: '{!! csrf_token() !!}',
                id: id
            },
            success: function (response) {
                //debugger;
                $('#modal_loader').hide();
                var response = JSON.parse(response);
                //console.log(response);
                $('#customer_detail_modal').val(response.cust_name);
                $('#customer_detail_modal').focus();
                $('#complain_detail_modal').val(response.complain);
                $('#complain_detail_modal').focus();
                $('#remarks_detail_modal').val(response.remarks);
            }
        });
    });

});

function fetchcomplain_types() {
    $.ajax({
        type: 'GET',
        url: '/complains_type_list',
        success: function (response) {
            //console.log(JSON.parse(response));
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="employeesListTable" style="width:100%"><thead><tr><th>Complaint Head</th><th>TAT</th><th>Assign To</th><th>Actions</th></tr></thead><tbody></tbody></table>');
            $('#employeesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                var names = "";

                element['assign_to'].map(x => names += `<span class="lab-line">${x.mem_name}</span>`);

                $('#employeesListTable tbody').append(`<tr><td>${element['head']}</td><td>${element['tat']}</td><td class="names_td">${names}</td><td><button id="${element['id']}" class="btn btn-default add_complain_type" data-toggle="modal" data-target=".competition-lg">Edit</button><button id="${element['id']}" class="btn btn-default red-bg delete_type" title="View Detail">Delete</button> </td></tr>`);
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#employeesListTable').DataTable();
        }
    });
}

function fetchcomplain_List() {
    $.ajax({
        type: 'GET',
        url: '/get_complains_list',
        success: function (response) {
            // console.log(JSON.parse(response));
            // return;
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="employeesListTable" style="width:100%"><thead><tr><th>Date</th><th>Customer</th><th>Complain</th><th>Created By</th><th>TAT</th><th>Remaining Time</th><th>Status</th><th>Actions</th></tr></thead><tbody></tbody></table>');
            $('#employeesListTable tbody').empty();
            var response = JSON.parse(response);
            var currentdate = new Date();
            var current_date_time = currentdate.getFullYear() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
            response.info.forEach(element => {

                //console.log(JSON.parse(element['assign_to']));
                JSON.parse(element['assign_to']).map(function (idx, ele) {
                    if(idx == response.id){
                        var daystohours = element['tat'] * 24;
                        var timeSinceComplain = find_difference(element['created_at'], current_date_time);
                        var valuestop = moment.duration(daystohours+":00", "HH:mm");
                        var difference = valuestop.subtract(timeSinceComplain);
        
                        $('#employeesListTable tbody').append(`<tr><td>${element['date']}</td><td>${element['customer']}</td><td>${element['complain']}</td><td>${element['created_by']}</td><td>${element['tat']}</td><td>${( difference.hours() < 0 ? 0 : (difference.hours() + (difference._data.days*24)) ) + " hrs " + (difference.minutes() < 0 ? 0 : difference.minutes()) + " mins"}</td><td>${(element['resolved'] == 0 ? '<span class="lab-pending">Pending</span>' : '<span class="lab-line">Resolved</span>')}</td><td><button id="${element['id']}" class="btn btn-default resolve_complain" data-toggle="modal" data-target=".competition-lg" ${ (element['resolved'] == 1 ? 'disabled' : '') }>Resolve</button> <button id="${element['id']}" class="btn btn-default view_detail_modal" data-toggle="modal" data-target=".competition-lg2">View Detail</button></td></tr>`);
                    }
                 });
                
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#employeesListTable').DataTable();
        }
    });
}


function fetchresolvedcomplains() {
    $.ajax({
        type: 'GET',
        url: '/get_resolved_complains_list',
        success: function (response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="employeesListTable" style="width:100%"><thead><tr><th>Date</th><th>Customer</th><th>Complain</th><th>Created By</th><th>TAT</th><th>Resolved Time</th><th>Resolved By</th></tr></thead><tbody></tbody></table>');
            $('#employeesListTable tbody').empty();
            var response = JSON.parse(response);
            var currentdate = new Date();
            response.info.forEach(element => {

                JSON.parse(element['assign_to']).map(function (idx, ele) {
                    if(idx == response.id){
                        var timeSinceComplain = find_difference(element['created_at'], element['resolve_at']);
                        var current_format = timeSinceComplain.split(':');
                        var time_format = current_format[0] + " hrs " + current_format[1] + " mins";
                        $('#employeesListTable tbody').append(`<tr><td>${element['date']}</td><td>${element['customer']}</td><td>${element['complain']}</td><td>${element['created_by']}</td><td>${element['tat']}</td><td>${time_format}</td><td>${element['resolved_by']}</td></tr>`);
                    }
                 });
                
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#employeesListTable').DataTable();
        }
    });
}


function find_difference(start_actual_time, end_actual_time) {
    // var start_actual_time  =  "2019-04-17 11:20";
    // var end_actual_time    =  "2019-04-17 12:30";
    start_actual_time = new Date(start_actual_time);
    end_actual_time = new Date(end_actual_time);

    var diff = end_actual_time - start_actual_time;
    var diffSeconds = diff / 1000;
    var HH = Math.floor(diffSeconds / 3600);
    var MM = Math.floor(diffSeconds % 3600) / 60;

    var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + Math.round(((MM < 10) ? ("0" + MM) : MM));
    //alert(formatted);
    return formatted;
}
