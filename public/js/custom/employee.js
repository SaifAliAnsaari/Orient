$(document).ready(function() {

    var segments = location.href.split('/');
    var action = segments[3];
    var notifications = [];
    if (action == 'edit_profile'){
       // debugger;
       
    }else if(action == 'cities'){
        fetchPickUpLocation();
    }else if(action == 'designations'){
        fetchDesignations();
    }else{
        fetchEmployeesList(); 
    }

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    
    var lastOp = "add";

    $(document).on('click', '.openDataSidebarForAddingEmployee', function() {
        //$('#dataSidebarLoader').hide();
        if (lastOp == "update") {

            $('input[name="name"]').val("");
            $('input[name="name"]').blur();

            $('input[name="phone"]').val("");
            $('input[name="phone"]').blur();

            $('input[name="email"]').val("");
            $('input[name="email"]').blur();

            $('input[name="cnic"]').val("");
            $('input[name="cnic"]').blur();

            $('input[name="city"]').val("");
            $('input[name="city"]').blur();

            $('input[name="state"]').val("");
            $('input[name="state"]').blur();

            $('input[name="address"]').val("");
            $('input[name="address"]').blur();

            $('input[name="username"]').val("");
            $('input[name="username"]').blur();

            $('input[name="hiring"]').val("");

            // $('input[name="salary"]').val("");
            // $('input[name="salary"]').blur();

           // $('select[name="country"]').val(1).trigger('change');
            $('select[name="designation"]').val(0).trigger('change');
            $('select[name="reporting"]').val(0).trigger('change');
            $('select[name="department"]').val(0).trigger('change');
            $('select[name="division"]').val(0).trigger('change');
            $('select[name="select_sales_emp"]').val(0).trigger("change");
            $('select[name="select_service_emp"]').val(0).trigger("change");
            $('select[name="select_complaints_emp"]').val(0).trigger("change");
            $('select[name="select_customers_emp"]').val(0).trigger("change");
            $('.super_admin').prop('checked', false);
        }
        lastOp = 'add';
        $('#operation').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
        $('#dropifyImgDiv').empty();
        $('#dropifyImgDiv').append('<input type="file" name="employeePicture" id="employeePicture" />');
        $('#employeePicture').dropify();
    });
    
    $(document).on('click', '.openDataSidebarForAddingLocation', function(){
       // debugger;
       $('#dataSidebarLoader').hide();
        if (lastOp == "update") {
            $('#updatePickUpForm').prop('id', 'savePickUpForm');
            $('input[name="city_name"]').val("");
            $('input[name="city_name"]').blur();
            $('input[name="province"]').val("");
            $('input[name="province"]').blur();
            $('input[name="city_short_code"]').val("");
            $('input[name="city_short_code"]').blur();
            //$('#savePickUpForm').find("select").val("-1").trigger('change')
            $('#savePickUp').show();
            $('#PickUp').hide();
            $('.updatePickUp').hide();
        }
        lastOp = 'add';
        if ($('#saveCompanyForm input[name="_method"]').length) {
            $('#saveCompanyForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForAddingDesignation', function() {
        //$('#dataSidebarLoader').hide();
        if (lastOp == "update") {
            $('input[name="designation_name"]').val("");
            $('input[name="designation_name"]').blur();
        }
        lastOp = 'add';
        $('#operation').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '#tab4', function (){
        $('#table_notif').show();
        $('#update_emp_pref').attr('disabled', 'disabled');
        $('.consignment_box').attr('disabled', 'disabled');
        $('.complains_box').attr('disabled', 'disabled');
        $('.suggestions_box').attr('disabled', 'disabled');
        $('#update_emp_pref').text('Processing..');
        $('.check_box').prop('checked', false);
        var id = $('#employee_id').val();
        $.ajax({
            type: 'GET',
            url: '/notif_pref_against_emp/' + id,
            success: function (response) {
                $('#update_emp_pref').removeAttr('disabled');
                $('.consignment_box').removeAttr('disabled');
                $('.complains_box').removeAttr('disabled');
                $('.suggestions_box').removeAttr('disabled');
                $('#update_emp_pref').text('Save');
                var response = JSON.parse(response);
                notifications = [];
                response.forEach(element => {
                    debugger;
                    $('input[id="' + element['notification_code_id'] + '"]').each(function() {
                        if ($(this).val() == "email") {
                            $(this).prop('checked', (element['email'] == "1" ? true : false));
                            if (element["email"] == "1") {
                                var value = $(this).val();
                                if (notifications.find(x => x["code"] == element['notification_code_id'])) {
                                    notifications.find(x => {
                                        if (x["code"] == element['notification_code_id']) {
                                            if (x["properties"].includes(value)) {
                                                x["properties"].splice(x["properties"].indexOf(value), 1);
                                            } else {
                                                x["properties"].push(value);
                                            }
                                        }
                                    });
                                } else {
                                    notifications.push({
                                        code: element['notification_code_id'],
                                        properties: [$(this).val()]
                                    });
                                }
                            }
                        } else {
                            //debugger;
                            $(this).prop('checked', (element['web'] == "1" ? true : false));
                            if (element["web"] == "1") {
                                var value = $(this).val();
                                if (notifications.find(x => x["code"] == element['notification_code_id'])) {
                                    notifications.find(x => {
                                        if (x["code"] == element['notification_code_id']) {
                                            if (x["properties"].includes(value)) {
                                                x["properties"].splice(x["properties"].indexOf(value), 1);
                                            } else {
                                                x["properties"].push(value);
                                            }
                                        }
                                    });
                                } else {
                                    notifications.push({
                                        code: element['notification_code_id'],
                                        properties: [$(this).val()]
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
    });




    $(document).on('click', '.openDataSidebarForUpdateEmployee', function() {
        $('#operation').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        $('#dropifyImgDiv').empty();
        $('#dropifyImgDiv').append('<input type="file" name="employeePicture" id="employeePicture" />');

        $('select[name="select_sales_emp"]').val(0).trigger("change");
        $('select[name="select_service_emp"]').val(0).trigger("change");
        $('select[name="select_complaints_emp"]').val(0).trigger("change");
        $('select[name="select_customers_emp"]').val(0).trigger("change");

        var id = $(this).attr('id');
        $('input[name="employee_updating_id"]').val(id)
        $.ajax({
            type: 'GET',
            url: '/Employee/' + id,
            success: function(response) {
                console.log(response);
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="name"]').focus();
                $('input[name="name"]').val(response.employee.name);
                $('input[name="name"]').blur();

                $('input[name="phone"]').focus();
                $('input[name="phone"]').val(response.employee.phone);
                $('input[name="phone"]').blur();

                $('input[name="email"]').focus();
                $('input[name="email"]').val(response.employee.email);
                $('input[name="email"]').blur();

                $('input[name="cnic"]').focus();
                $('input[name="cnic"]').val(response.employee.cnic);
                $('input[name="cnic"]').blur();

                $('input[name="address"]').focus();
                $('input[name="address"]').val(response.employee.address);
                $('input[name="address"]').blur();

                $('input[name="username"]').focus();
                $('input[name="username"]').val(response.employee.username);
                $('input[name="username"]').blur();

                //$('input[name="hiring"]').val(response.employee.hiring);
                $('input[name="hiring"]').datepicker("setDate", new Date(response.employee.hiring));

                // $('input[name="salary"]').focus();
                // $('input[name="salary"]').val(response.employee.salary);
                // $('input[name="salary"]').blur();

                $('select[name="company"]').val(response.employee.company).trigger('change');
                $('select[name="city"]').val(response.employee.city).trigger('change');
                $('select[name="province"]').val(response.employee.state).trigger('change');
                $('select[name="designation"]').val(response.employee.designation).trigger('change');
                $('select[name="reporting"]').val(response.employee.reporting_to).trigger('change');
                $('select[name="department"]').val(response.employee.department_id).trigger('change');
                $('select[name="division"]').val(response.employee.division).trigger('change');

                var cvrs = [];
                JSON.parse("[" + response.notif.cvr + "]").forEach(element => {
                    cvrs.push(element);
                });

                var svrs = [];
                JSON.parse("[" + response.notif.svr + "]").forEach(element => {
                    svrs.push(element);
                });

                var complaints = [];
                JSON.parse("[" + response.notif.complaint + "]").forEach(element => {
                    complaints.push(element);
                });

                var customers = [];
                JSON.parse("[" + response.notif.customer + "]").forEach(element => {
                    customers.push(element);
                });

                $('select[name="select_sales_emp"]').val(cvrs).trigger("change");
                $('select[name="select_service_emp"]').val(svrs).trigger("change");
                $('select[name="select_complaints_emp"]').val(complaints).trigger("change");
                $('select[name="select_customers_emp"]').val(customers).trigger("change");

                var imgUrl = response.base_url + response.employee.picture;
                $("#employeePicture").attr("data-height", '100px');
                $("#employeePicture").attr("data-default-file", imgUrl);
                $('#employeePicture').dropify();

                if(response.employee.super_admin == 0){
                    $('.super_admin').prop('checked', false);
                }else{
                    $('.super_admin').prop('checked', true);
                }
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForUpdatePickUp', function(){
        var id = $(this).attr('id');
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        //Form ki id change kr de hai
        $('#savePickUpForm').prop('id', 'updatePickUpForm');

        var id = $(this).attr('id');
        $('input[name="team_updating_id"]').val(id);
        if (!$('#savePickUpForm input[name="_method"]').length) {
            $('#savePickUpForm').append('<input name="_method" value="PUT" hidden />');
        }

        $.ajax({
            type: 'GET',
            url: '/pickUp_data/' + id,
            success: function (response) {
                //console.log(response); return;
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="city_name"]').focus();
                $('input[name="city_name"]').val(response.city_name);
                $('input[name="city_name"]').blur();

                $('input[name="province"]').focus();
                $('input[name="province"]').val(response.province);
                $('input[name="province"]').blur();

                $('input[name="city_short_code"]').focus();
                $('input[name="city_short_code"]').val(response.city_short);
                $('input[name="city_short_code"]').blur();

                $('input[name="delivery_id"]').val(response.id);

                // var locations = [];
                // jQuery.parseJSON(response.services).forEach(element => {
                //     locations.push(element);
                // });
                // $('#location').val(locations).trigger("change");

                $('#savePickUp').hide();
                $('.updatePickUp').show();
                $('.updatePickUp').attr('id', response.id);

            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForUpdateDesignation', function(){
        $('#operation').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="designation_update_id"]').val(id)
        $.ajax({
            type: 'GET',
            url: '/get_designation/' + id,
            success: function(response) {
                console.log(response);
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();

                $('input[name="designation_name"]').focus();
                $('input[name="designation_name"]').val(response.name);
                $('input[name="designation_name"]').blur();
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });





    $(document).on('click', '#saveEmployee', function() {
       
        var verif = [];
        $('.required').css('border', '');
        $('.required').parent().css('border', '');

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
            }else if( $(this).val() == 0 || $(this).val() == null){
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

        if(verif.includes(false)){
            return;
        }

        if (!validateEmail($('input[name="email"]').val())) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Invalid email format');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        } 

        if(!$('input[name="cnic"]').val() == ""){
            var thisRef = $('input[name="cnic"]').val();
            if (thisRef.length != 13) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Invalid CNIC');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }
        }
        if($('.super_admin').prop('checked')){
            $('#hidden_super_admin').val('1');
         }else{
             $('#hidden_super_admin').val('0');
         }

        $('input[name="hidden_cvr_emp"]').val($('select[name="select_sales_emp"]').val());
        $('input[name="hidden_svr_emp"]').val($('select[name="select_service_emp"]').val());
        $('input[name="hidden_complaint_emp"]').val($('select[name="select_complaints_emp"]').val());
        $('input[name="hidden_customers_emp"]').val($('select[name="select_customers_emp"]').val());

        $('#saveEmployee').attr('disabled', 'disabled');
        $('#cancelEmployee').attr('disabled', 'disabled');
        $('#saveEmployee').text('Processing..');
        var ajaxUrl = "/register";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/UpdateEmployee/" + $('input[name="employee_updating_id"]').val();
        }

        $('#saveEmployeeForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveEmployeeForm').serialize(),
            cache: false,
            success: function(response) {
                //console.log(response);
                if(JSON.parse(response) == "email_exist") {
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Email already exist.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "updated") {
                    fetchEmployeesList();
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    if ($('#operation').val() !== "update") {
                        $('#saveEmployeeForm').find("input[type=text], textarea").val("");
                        $('#saveEmployeeForm').find("select").val("0").trigger('change');
                        $('.dropify-clear').click();
                    }
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Employee have been updated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Employee at the moment......');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "already_exist"){
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Username already exist....');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else{
                    fetchEmployeesList();
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    if ($('#operation').val() !== "update") {
                        $('.dropify-clear').click();
                        $('input[name="name"]').val("");
                        $('input[name="phone"]').val("");
                        $('input[name="email"]').val("");
                        $('input[name="cnic"]').val("");
                        $('input[name="address"]').val("");
                        $('input[name="username"]').val("");
                        $('input[name="password"]').val("");
                        $('input[name="hiring"]').val("");
                        //$('input[name="salary"]').val("");
                        $('select[name="city"]').val('0').trigger('change');
                        $('select[name="province"]').val('0').trigger('change');
                        $('select[name="designation"]').val('0').trigger('change');
                        $('select[name="reporting"]').val('0').trigger('change');
                        $('select[name="department"]').val('0').trigger('change');
                        $('select[name="company"]').val('0').trigger('change');
                        $('select[name="division"]').val('0').trigger('change');
                        $('.open_confirmation_modal').click();
                        $('.access_right_link').attr('href', '/access_rights/'+JSON.parse(response));
                    }
                    $('#pl-close').click();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Employee have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            },
            error: function(err) {
                $('#saveEmployee').removeAttr('disabled');
                $('#cancelEmployee').removeAttr('disabled');
                $('#saveEmployee').text('Save');
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Failed to add Employee at the moment');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });
    });

    $(document).on('click', '#savePickUp', function(){
        var verif = [];
        $('.required').css('border', '');
        $('.required').parent().css('border', '');

        $('.required').each(function () {
            if ($(this).val() == "") {
                if ($(this).attr('name') == 'location') {
                    if ($(this).val() == 0 || $(this).val() == null) {
                        $(this).parent().css("border", "1px solid red");
                        verif.push(false);
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Please provide all the required information (*)');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                        return;
                    }
                } else {
                    $(this).css("border", "1px solid red");
                    verif.push(false);
                    $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Please provide all the required information (*)');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                    return;
                }
            } else {
                verif.push(true);
            }
        });

        if(verif.includes(false)){
            return;
        }

       
        $('#savePickUp').attr('disabled', 'disabled');
        $('#cancelPickUp').attr('disabled', 'disabled');
        $('#savePickUp').text('Processing..');

        $.ajax({
            type: 'POST',
            url: '/PickUp_save',
            data: {
                _token: $('input[name="_token"]').val(),
                city_name: $('input[name=city_name]').val(),
                province: $('input[name=province]').val(),
                city_short_code: $('input[name=city_short_code]').val()
                //location: $('#location').val()
            },
            success: function (response) {
                //console.log(response); return;
                $('#cancelPickUp').removeAttr('disabled');
                $('#savePickUp').removeAttr('disabled');
                $('#savePickUp').text('Save');
                // console.log(response);
                // return;

                if (JSON.parse(response) == "success") {
                    //$('input[name="location_service"]').remove();
                    fetchPickUpLocation();
                    if ($('#operation').val() !== "update") {
                        $('input[name="city_name"]').val('');
                        $('input[name="province"]').val('');
                        $('input[name="city_short_code"]').val('');
                        //$('select[name="location"]').val('-1').trigger('change');
                    }

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Pick up & Delivery Location have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if(JSON.parse(response) == 'already_exist'){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('City name Already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Pick up & Delivery Location at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
       
    });

    $(document).on('click', '#saveDesignation', function(){
        var verif = [];
        $('.required').css('border', '');
        $('.required').parent().css('border', '');

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
            } else {
                verif.push(true);
            }
        });

        if(verif.includes(false)){
            return;
        }

        $('#saveDesignation').attr('disabled', 'disabled');
        $('#cancelDesignation').attr('disabled', 'disabled');
        $('#saveDesignation').text('Processing..');
        var ajaxUrl = "/save_designation";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/UpdateDesignation/" + $('input[name="designation_update_id"]').val();
        }

        $('#saveDesignationForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveDesignationForm').serialize(),
            cache: false,
            success: function(response) {
                //console.log(response);
                $('#saveDesignation').removeAttr('disabled');
                $('#cancelDesignation').removeAttr('disabled');
                $('#saveDesignation').text('Save');
                if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Designation at the moment......');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "already exist"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Designation already exist....');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else{
                    fetchDesignations();
                    if ($('#operation').val() !== "update") {
                        $('input[name="designation_name"]').val("");
                        $('#pl-close').click();
                    }
                    
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Designation have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            },
            error: function(err) {
                $('#saveDesignation').removeAttr('disabled');
                $('#cancelDesignation').removeAttr('disabled');
                $('#saveDesignation').text('Save');
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Failed to add Designation at the moment');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });
    });







    $(document).on('click', '.activate_btn', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");

        $.ajax({
            type: 'GET',
            url: '/activate_employee',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    fetchEmployeesList();
                    $(this).removeAttr('disabled');
                    $(this).text('Deactivate');
                    $(this).removeClass("activate_btn");
                    $(this).addClass("deactivate_btn");


                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Activated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to activate employee');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }    
            }
        });
    });

    $(document).on('click', '.deactivate_btn', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");

        $.ajax({
            type: 'GET',
            url: '/deactivate_employee',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    fetchEmployeesList();
                    $(this).removeAttr('disabled');
                    $(this).text('Deactivate');
                    $(this).removeClass("deactivate_btn");
                    $(this).addClass("activate_btn");

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Deactivated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to deactivate employee');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }    
            }
        });
    });

    //Change Password User Profile
    $(document).on('click', '#save_changes_userProfile', function(){
        if($('#current_password').val() != "" || $('#new_password').val() != "" || $('#confirm_password').val() != ""){
            if($('#new_password').val() != $('#confirm_password').val()){
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('New Password and Confirm Password does not match!');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }
            if($('#new_password').val().length < 6 || $('#confirm_password').val().length < 6){
                //debugger;
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('New Password and Confirm Password should have atleast 6 characters');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }
        }

        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        $('#saveEditProfileForm').ajaxSubmit({
            type: "POST",
            url: "/update_user_profile",
            data: $('#saveEditProfileForm').serialize(),
            cache: false,
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    $("#save_changes_userProfile").removeAttr('disabled');
                    $("#save_changes_userProfile").text('Save Changes');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Updated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    location.reload();
                }else if(JSON.parse(response) == "failed"){
                    $("#save_changes_userProfile").removeAttr('disabled');
                    $("#save_changes_userProfile").text('Save Changes');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to update');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "empty"){
                    $("#save_changes_userProfile").removeAttr('disabled');
                    $("#save_changes_userProfile").text('Save Changes');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please fill all fields.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else{
                    $("#save_changes_userProfile").removeAttr('disabled');
                    $("#save_changes_userProfile").text('Save Changes');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Password does not match.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    });

    //Save Picture User Profile
    $(document).on('click', '#save_pic_user_profile', function(){
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        $('#saveEditProfilePictureForm').ajaxSubmit({
            type: "POST",
            url: "/update_user_profile_pic",
            data: $('#saveEditProfilePictureForm').serialize(),
            cache: false,
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    $("#save_pic_user_profile").removeAttr('disabled');
                    $("#save_pic_user_profile").text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Updated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    location.reload();
                }else if(JSON.parse(response) == "failed"){
                    $("#save_pic_user_profile").removeAttr('disabled');
                    $("#save_pic_user_profile").text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to update');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "empty"){
                    $("#save_pic_user_profile").removeAttr('disabled');
                    $("#save_pic_user_profile").text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please select picture to upload.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    });




    //Delete
    $(document).on('click', '.deletepickUp', function(){
        var id = $(this).attr('id');
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $.ajax({
            type: 'GET',
            url: '/delete_pickUp',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                thisRef.removeAttr('disabled');
                thisRef.text('Delete');
                if(JSON.parse(response) == "success"){
                    fetchPickUpLocation();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Deleted successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to Delete at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }    
            }
        });
    });

    $(document).on('click', '.delete_designation', function(){
        var id = $(this).attr('id');
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $.ajax({
            type: 'GET',
            url: '/delete_designation',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                thisRef.removeAttr('disabled');
                thisRef.text('Delete');
                if(JSON.parse(response) == "success"){
                    fetchDesignations();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Deleted successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to Delete at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }    
            }
        });
    });



    $(document).on('click', '.updatePickUp', function(){
        var id = $(this).attr('id');
        var verif = [];
        $('.required').css('border', '');
        $('.required').parent().css('border', '');

        $('.required').each(function () {
            if ($(this).val() == "") {
                if ($(this).attr('name') == 'location') {
                    if ($(this).val() == 0 || $(this).val() == null) {
                        $(this).parent().css("border", "1px solid red");
                        verif.push(false);
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Please provide all the required information (*)');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                        return;
                    }
                } else {
                    $(this).css("border", "1px solid red");
                    verif.push(false);
                    $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Please provide all the required information (*)');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                    return;
                }
            } else {
                verif.push(true);
            }
        });

        if(verif.includes(false)){
            return;
        }
        $('.updatePickUp').attr('disabled', 'disabled');
        $('#cancelPickUp').attr('disabled', 'disabled');
        $('.updatePickUp').text('Processing..');

        $.ajax({
            type: 'POST',
            url: '/update_pickup',
            data: {
                _token: $('input[name="_token"]').val(),
                id: id,
                city_name: $('input[name=city_name]').val(),
                province: $('input[name=province]').val(),
                city_short_code: $('input[name=city_short_code]').val()
                //location: $('#location').val()
            },
            success: function (response) {
                //console.log(response); return;
                $('#cancelPickUp').removeAttr('disabled');
                $('.updatePickUp').removeAttr('disabled');
                $('.updatePickUp').text('Update');
                // console.log(response);
                // return;

                if (JSON.parse(response) == "success") {
                    //$('input[name="location_service"]').remove();
                    fetchPickUpLocation();
                    if ($('#operation').val() !== "update") {
                        $('input[name="city_name"]').val('');
                        $('input[name="province"]').val('');
                        $('input[name="city_short_code"]').val('');
                        $('select[name="location"]').val('-1').trigger('change');
                    }

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Pick up & Delivery Location have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if(JSON.parse(response) == 'already_exist'){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('City name Already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Pick up & Delivery Location at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    });


    //Employee checkboxes
    $(document).on('click', '.check_box', function () {
        var id = $(this).attr('id');
        var value = $(this).val();
        if (notifications.find(x => x["code"] == id)) {
            notifications.find(x => {
                if (x["code"] == id) {
                    if (x["properties"].includes(value)) {
                        x["properties"].splice(x["properties"].indexOf(value), 1);
                    } else {
                        x["properties"].push(value);
                    }
                }
            });
        } else {
            notifications.push({
                code: id,
                properties: [$(this).val()]
            });
        }
        console.log(notifications);
    });

    //Save Employee notifications
    $(document).on('click', '#update_emp_pref', function () {

        if ($('#employee_id').val() == 0 || $('#employee_id').val() == null) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please select Employee');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if (notifications == "") {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Check Notification First');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        var emp_id = $('#employee_id').val();

        $('#update_emp_pref').attr('disabled', 'disabled');
        $('.consignment_box').attr('disabled', 'disabled');
        $('.complains_box').attr('disabled', 'disabled');
        $('.suggestions_box').attr('disabled', 'disabled');
        $('#update_emp_pref').text('Processing..');
        $.ajax({
            type: 'POST',
            url: '/save_pref_against_emp',
            data: {
                _token: $('input[name="_token"]').val(),
                emp_id: emp_id,
                notifications: notifications
            },
            success: function (response) {
                if (JSON.parse(response) == "success") {
                    $('#update_emp_pref').removeAttr('disabled');
                    $('.consignment_box').removeAttr('disabled');
                    $('.complains_box').removeAttr('disabled');
                    $('.suggestions_box').removeAttr('disabled');
                    $('#update_emp_pref').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Saved Successfully');
                    //$('#employee_id').val(0).trigger('change');
                   // $('.check_box').prop('checked', false);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#update_emp_pref').removeAttr('disabled');
                    $('.consignment_box').removeAttr('disabled');
                    $('.complains_box').removeAttr('disabled');
                    $('.suggestions_box').removeAttr('disabled');
                    $('#update_emp_pref').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to save at the moment!');
                    // $('#employee_id').val(0).trigger('change');
                    // $('.check_box').prop('checked', false);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }

            }
        });
    });




    $(document).on('change', '#select_city', function(){
        var id = $('#select_city option:selected').attr('name');
        var cities_array = JSON.parse($('#full_cities_array').val());
        cities_array.map(function(x) {
            if(x.province == id){
               $('#select_province').val(x.province).trigger('change');
            }
        }).indexOf(id);
        
    });

});

function fetchEmployeesList() {
    $.ajax({
        type: 'GET',
        url: '/EmployeesList',
        success: function(response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="employeesListTable" style="width:100%"><thead><tr><th>Emp ID</th><th>Employee Name</th><th>Phone</th><th>City</th><th>Email</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#employeesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#employeesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['name'] + '</td><td>' + element['phone'] + '</td><td>' + element['city'] + '</td><td>' + element['email'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateEmployee">Edit</button>'+ (element["is_active"] ? '<button id="' + element['id'] + '" class="btn btn-default red-bg  deactivate_btn" title="View Detail">Deactivate</button>' : '<button id="' + element['id'] + '" class="btn btn-default activate_btn">Activate</button>') +'</td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            //$('#employeesListTable').DataTable();
            $('#employeesListTable').DataTable( {
                "pageLength": 50
            });
        }
    });
}

function fetchPickUpLocation(){
    $.ajax({
        type: 'GET',
        url: '/GetPickUpList',
        success: function (response) {
            //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="clientsListTable" style="width:100%;"><thead><tr><th>ID</th><th>City Name</th><th>Province</th><th>City Short</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#clientsListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#clientsListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['city_name'] + '</td><td>' + element['province'] + '</td><td>' + element['city_short'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdatePickUp">Edit</button><button type="button" id="' + element['id'] + '" class="btn btn-default red-bg deletepickUp" title="Delete">Delete</button></form></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            //$('#clientsListTable').DataTable();
            $('#clientsListTable').DataTable( {
                "pageLength": 50
            });
        }
    });
}

function fetchDesignations(){
    $.ajax({
        type: 'GET',
        url: '/DesignationsList',
        success: function(response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="DesignationsListTable" style="width:100%"><thead><tr><th>ID</th><th>Designation Name</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#DesignationsListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#DesignationsListTable tbody').append(`<tr><td>${element['id']}</td><td>${element['name']}</td><td><button id="${element['id']}" class="btn btn-default btn-line openDataSidebarForUpdateDesignation">Edit</button><button id="${element['id']}" class="btn btn-default red-bg delete_designation" title="Delete">Delete</button></td></tr>`);
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            //$('#DesignationsListTable').DataTable();
            $('#DesignationsListTable').DataTable( {
                "pageLength": 50
            });
        }
    });
}



function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}