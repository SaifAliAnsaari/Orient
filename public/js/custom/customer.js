var company_id = 0;
$(document).ready(function() {

    var segments = location.href.split('/');
    var action = segments[3];

    // if (action !== 'CustomerProfile' && action !== 'Customer_zone_list'){
    // //alert('here');
    //     fetchCompaniesList();
    // }else {
    //     fetchCompanyInfoForUpdate($('#companyIdForUpdate').val());
    // }
    if(action == 'Customer_list'){
        fetchCompaniesList();
        fetchParentCompanies(company_id);
    }else if(action == 'CustomerProfile'){
        $('#example3').DataTable();
        $('#example4').DataTable();
        fetchCompanyInfoForUpdate($('#companyIdForUpdate').val());
    }else if(action == 'poc_list'){
        fetchPOCList();
    }
    var lastOp = "add";

    $(document).on('click', '.openDataSidebarForAddingCustomer', function() {
        if (lastOp == "update") {
            $('input[name="compName"]').val("");
            $('input[name="compName"]').blur();
            $('input[name="compName"]').val("");
            $('input[name="compName"]').blur();
            $('input[name="poc"]').val("");
            $('input[name="poc"]').blur();
            $('input[name="jobTitle"]').val("");
            $('input[name="jobTitle"]').blur();
            $('input[name="bussinessPH"]').val("");
            $('input[name="bussinessPH"]').blur()
            $('input[name="address"]').val("");
            $('input[name="address"]').blur();
            $('input[name="city"]').val("Karachi");
            $('input[name="state"]').val("Sindh");
            $('input[name="email"]').val("");
            $('input[name="email"]').blur();
            $('input[name="web_address"]').val("");
            $('input[name="web_address"]').blur();
            $('textarea[name="description"]').val("");

            $('select[name="parent_company"]').val(0).trigger('change');
            $('select[name="industry"]').val(0).trigger('change');

        }

        if ($('#saveCustomerForm input[name="_method"]').length) {
            $('#saveCustomerForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');

    });

    $(document).on('click', '.openDataSidebarForAddingPOC', function(){
        if (lastOp == "update") {
             $('input[name="poc_name"]').val("");
             $('input[name="company_name"]').val("");
             $('input[name="jobTitle"]').val("");
             $('input[name="businessPH"]').val("");
             $('input[name="email"]').val("");
             $('input[name="address"]').val("");
             $('input[name="city"]').val("");
             $('input[name="state"]').val("");
        }

        // if ($('#savePOCForm input[name="_method"]').length) {
        //     $('#savePOCForm input[name="_method"]').remove();
        // }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll')
    });




    $(document).on('click', '.openDataSidebarForUpdateCustomer', function() {
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="product_updating_id"]').val(id)
        if (!$('#saveCustomerForm input[name="_method"]').length) {
            $('#saveCustomerForm').append('<input name="_method" value="PUT" hidden />');
        }

        $.ajax({
            type: 'GET',
            url: '/Customer/' + id,
            success: function(response) {
                var response = JSON.parse(response);
                console.log(response);
               // return;
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="compName"]').focus();
                $('input[name="compName"]').val(response.info.company_name);
                $('input[name="compName"]').blur();

                $('input[name="poc"]').focus();
                $('input[name="poc"]').val(response.info.company_poc);
                $('input[name="poc"]').blur();

                $('input[name="jobTitle"]').focus();
                $('input[name="jobTitle"]').val(response.info.job_title);
                $('input[name="jobTitle"]').blur();

                $('input[name="bussinessPH"]').focus();
                $('input[name="bussinessPH"]').val(response.info.business_phone);
                $('input[name="bussinessPH"]').blur();

                $('input[name="address"]').focus();
                $('input[name="address"]').val(response.info.address);
                $('input[name="address"]').blur();

                $('input[name="email"]').focus();
                $('input[name="email"]').val(response.info.email);
                $('input[name="email"]').blur();

                $('input[name="web_address"]').focus();
                $('input[name="web_address"]').val(response.info.webpage);
                $('input[name="web_address"]').blur();

                $('input[name="country"]').focus();
                $('input[name="country"]').val(response.info.country);
                $('input[name="country"]').blur();

                $('select[name="parent_company"]').val((response.info.parent_company ? response.info.parent_company : '0')).trigger('change')
                $('select[name="industry"]').val(response.info.industry).trigger('change');
                $('select[name="city"]').val(response.info.city).trigger('change');
                $('select[name="province"]').val(response.info.state).trigger('change');

                $('textarea[name="description"]').val(response.info.remarks);

            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForUpdatePOC', function(){
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="product_updating_id"]').val(id)
        // if (!$('#savePOCForm input[name="_method"]').length) {
        //     $('#savePOCForm').append('<input name="_method" value="PUT" hidden />');
        // }
        $.ajax({
            type: 'GET',
            url: '/GetSelectedPOC/' + id,
            success: function(response) {
                var response = JSON.parse(response);
                
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="poc_name"]').focus();
                $('input[name="poc_name"]').val(response.poc_name);
                $('input[name="poc_name"]').blur();

                $('select[name="company_name"]').val(response.company_name).trigger('change');

                $('input[name="jobTitle"]').focus();
                $('input[name="jobTitle"]').val(response.job_title);
                $('input[name="jobTitle"]').blur();

                $('input[name="businessPH"]').focus();
                $('input[name="businessPH"]').val(response.bussiness_ph);
                $('input[name="businessPH"]').blur();

                $('input[name="address"]').focus();
                $('input[name="address"]').val(response.address);
                $('input[name="address"]').blur();

                $('input[name="city"]').focus();
                $('input[name="city"]').val(response.city);
                $('input[name="city"]').blur();

                $('input[name="state"]').focus();
                $('input[name="state"]').val(response.state);
                $('input[name="state"]').blur();

                $('input[name="email"]').focus();
                $('input[name="email"]').val(response.email);
                $('input[name="email"]').blur();

            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    //Save Customers
    $(document).on('click', '#saveCustomer', function() {
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
        
        if(!$('input[name="email"]').val() == ""){
            if (!validateEmail($('input[name="email"]').val())) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Invalid email format');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            } 
        }
        if(jQuery.inArray(false, verif) != -1){
            return;
        }
          

        $('#saveCustomer').attr('disabled', 'disabled');
        $('#cancelCustomer').attr('disabled', 'disabled');
        $('#saveCustomer').text('Processing..');

        var ajaxUrl = "/Customer";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/Customer/" + $('input[name="product_updating_id"]').val();
        }
        $('#saveCustomerForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveCustomerForm').serialize(),
            cache: false,
            success: function(response) {
                if (JSON.parse(response) == "failed") {
                    $('#saveCustomer').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('#saveCustomer').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add customer at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else {
                    fetchCompaniesList();
                    $('#saveCustomer').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('#saveCustomer').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Customer have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#pl-close').click();

                    if($('#operation').val() == 'add'){
                        // $('#saveCustomerForm').find('input').val('');
                        // $('#saveCustomerForm').find('select').val(0).trigger('change');
                        $('#description').val('');
                        $("input[name='compName']").val('');
                        $("select[name='parent_company']").val('0').trigger('change');
                        $("select[name='industry']").val('0').trigger('change');
                        $("select[name='city']").val('0').trigger('change');
                        $("select[name='province']").val('0').trigger('change');
                        $("input[name='poc']").val('');
                        $("input[name='jobTitle']").val('');
                        $("input[name='bussinessPH']").val('');
                        $("input[name='email']").val('');
                        $("input[name='address']").val('');
                        $("input[name='web_address']").val('');
                    }
                    
                }
               
            },
            error: function(err) {
                $('#saveCustomer').removeAttr('disabled');
                $('#cancelCustomer').removeAttr('disabled');
                $('#saveCustomer').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });
    });

    //Save POC
    $(document).on('click', '#savePOC', function(){
       // debugger;
        var verif = [];
        $('.required').each(function () {
            if ($(this).val() == "") {
               // debugger;
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
        
        if(!$('input[name="email"]').val() == ""){
            if (!validateEmail($('input[name="email"]').val())) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Invalid email format');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            } 
        }
        $('#savePOC').attr('disabled', 'disabled');
        $('#cancelPOC').attr('disabled', 'disabled');
        $('#savePOC').text('Processing..');

        var ajaxUrl = "/save_poc";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/update_poc/" + $('input[name="product_updating_id"]').val();
        }
        $('#savePOCForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#savePOCForm').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "failed") {
                    $('#savePOC').removeAttr('disabled');
                    $('#cancelPOC').removeAttr('disabled');
                    $('#savePOC').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add poc at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    fetchPOCList();
                    $('#savePOC').removeAttr('disabled');
                    $('#cancelPOC').removeAttr('disabled');
                    $('#savePOC').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('POC have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#pl-close').click();
                    
                    if($('#operation').val() == 'add'){
                        //$('#savePOCForm').find('input').val('');
                        $("input[name='poc_name']").val('');
                        $("input[name='company_name']").val('');
                        $("input[name='jobTitle']").val('');
                        $("input[name='businessPH']").val('');
                        $("input[name='email']").val('');
                        $("input[name='address']").val('');
                        $("input[name='city']").val('');
                        $("input[name='state']").val('');
                    }
                    
                }else if(JSON.parse(response) == "already_exist"){
                    $('#savePOC').removeAttr('disabled');
                    $('#cancelPOC').removeAttr('disabled');
                    $('#savePOC').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Email already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#savePOCForm').removeAttr('disabled');
                $('#cancelCustomer').removeAttr('disabled');
                $('#savePOCForm').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    var customerId = "";
    var thisRef = "";

    
    //Activate Customer
    $(document).on('click', '.activate_btn', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        var thisRef = $(this);

        $.ajax({
            type: 'GET',
            url: '/activate_customer',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    //fetchCompaniesList();
                    thisRef.removeAttr('disabled');
                    thisRef.text('Deactivate');
                    thisRef.removeClass("activate_btn");
                    thisRef.addClass("deactivate_btn");
                    thisRef.addClass('red-bg');

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

    //Deactivate Customer
    $(document).on('click', '.deactivate_btn', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        var thisRef = $(this);

        $.ajax({
            type: 'GET',
            url: '/deactivate_customer',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    //fetchCompaniesList();
                    thisRef.removeAttr('disabled');
                    thisRef.text('Activate');
                    thisRef.removeClass("deactivate_btn");
                    thisRef.removeClass('red-bg');
                    thisRef.addClass("activate_btn");

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


    //Activate POC
    $(document).on('click', '.activate_btn_poc', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        var thisRef = $(this);

        $.ajax({
            type: 'GET',
            url: '/activate_poc',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
            },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    thisRef.removeAttr('disabled');
                    thisRef.text('Deactivate');
                    thisRef.removeClass("activate_btn");
                    thisRef.addClass("deactivate_btn_poc");
                    thisRef.addClass('red-bg');

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

    //Deactivate POC
    $(document).on('click', '.deactivate_btn_poc', function(){
        var id = $(this).attr('id');
        $(this).text('PROCESSING....');
        $(this).attr("disabled", "disabled");
        var thisRef = $(this);

        $.ajax({
            type: 'GET',
            url: '/deactivate_poc',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
           },
            success: function(response) {
                if(JSON.parse(response) == "success"){
                    //fetchCompaniesList();
                    thisRef.removeAttr('disabled');
                    thisRef.text('Activate');
                    thisRef.removeClass("deactivate_btn");
                    thisRef.removeClass('red-bg');
                    thisRef.addClass("activate_btn");

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

    //Edit poc From Edit Page
    $(document).on('click', '.edit_poc_btn', function(){
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
        
        if(!$('input[name="email"]').val() == ""){
            if (!validateEmail($('input[name="email"]').val())) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Invalid email format');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            } 
        }

        $('.edit_poc_btn').text('Processing...');
        $('.edit_poc_btn').attr('disabled', 'disabled');

        $('#edit_poc_form').ajaxSubmit({
            type: "POST",
            url: '/update_poc_fromPOCDetailPage',
            data: $('#edit_poc_form').serialize(),
            cache: false,
            success: function(response) {
                if (JSON.parse(response) == "failed") {
                    $('.edit_poc_btn').removeAttr('disabled');
                    $('.edit_poc_btn').text('Edit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to update poc at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    //fetchPOCList();
                    $('.edit_poc_btn').removeAttr('disabled');
                    $('.edit_poc_btn').text('Edit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('POC have been updated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "already_exist"){
                    $('.edit_poc_btn').removeAttr('disabled');
                    $('.edit_poc_btn').text('Edit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Email already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#edit_poc_form').removeAttr('disabled');
                $('#cancelPOC').removeAttr('disabled');
                $('#edit_poc_form').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    $('#updateCustomerProfileForm').removeAttr( 'style' );

     //Edit Client Profile
     var count_click_edit = 0;
     $(document).on('click', '.edit_profile_btn', function(){
         var thisRef = $(this);
         var currentcompany_name = $('.nam-title').text();
         var currentpoc = $('.con_info p:eq(0)').text();
         var currentaddress = $('.con_info p:eq(1)').text();
         var currentcity = $('.con_info p:eq(2)').text();
         var currentcountry = $('.con_info p:eq(3)').text();
         if(count_click_edit == 0){
             thisRef.text('Save');
             count_click_edit ++;
             
            $('.nam-title').empty();
            $('.con_info p:eq(0)').empty();
            $('.con_info p:eq(1)').empty();
            $('.con_info p:eq(2)').empty();
            $('.con_info p:eq(3)').empty();
             
            $('.nam-title').append('<div class="form-group" ><input style="max-height: 30px; font-size:12px !important;" type="text" value="'+ currentcompany_name +'" id="profile_page_company_page" name="profile_page_company_page" class="form-control required_one"></div>');

            $('.con_info p:eq(0)').append('<div class="form-group" ><input style="max-height: 30px; font-size:12px !important;" type="text" value="'+ currentpoc +'" id="profile_page_poc" name="profile_page_poc" class="form-control required_one"></div>');

            $('.con_info p:eq(1)').append('<div class="form-group" ><input style="max-height: 30px; font-size:12px !important;" type="text" value="'+ currentaddress +'" id="profile_page_address" name="profile_page_address" class="form-control required_one"></div>');

            $('.con_info p:eq(2)').append('<div class="form-group" ><input style="max-height: 30px; font-size:12px !important;" type="text" value="'+ currentcity +'" id="profile_page_city" name="profile_page_city" class="form-control required_one"></div>');

            $('.con_info p:eq(3)').append('<div class="form-group" ><input style="max-height: 30px; font-size:12px !important;" type="text" value="'+ currentcountry +'" id="profile_page_country" name="profile_page_country" class="form-control required_one"></div>');
 
         }else{
             thisRef.text('PROCESSING....');
             thisRef.attr("disabled", "disabled");
             count_click_edit = 0;
             $.ajax({
                type: 'Get',
                url: '/updateClientFromProfile',
                data: {
                    id: $('#hidden_cust_id').val(),
                    company_name: $('#profile_page_company_page').val(),
                    poc: $('#profile_page_poc').val(),
                    address: $('#profile_page_address').val(),
                    city: $('#profile_page_city').val(),
                    country: $('#profile_page_country').val(),
                    _token: '{!! csrf_token() !!}'
                },
                 success: function(response) {
                    
                     if (JSON.parse(response) == "success") {
                         location.reload();
                     } else {
                         location.reload();
                     }
                 },
                 error: function(err) {
                     //location.reload();
                     if (err.status == 422) {
                         $.each(err.responseJSON.errors, function(i, error) {
                             var el = $(document).find('[name="' + i + '"]');
                             el.after($('<small class = "validation_errors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                         });
                     }
                 }
             });
         }
       
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




    $(document).on('click', '.add_company_modal', function(){
        var verif = [];
        $('.required_company').each(function () {
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
        if (jQuery.inArray(false, verif) != -1) {
            return;
        }
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $('.cancel_company_modal').attr('disabled', 'disabled');
        $.ajax({
            type: 'GET',
            url: '/save_parent_company',
            data: {
                _token: '{!! csrf_token() !!}',
               name: $('#company_name').val()
            },
            success: function(response) {
                thisRef.removeAttr('disabled');
                $('.cancel_company_modal').removeAttr('disabled');
                thisRef.text('Save');
                if(JSON.parse(response) == "already_exist"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Parent Company already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }else if(JSON.parse(response) == "failed"){
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to add parent company');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else{
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#company_name').val('');
                    fetchParentCompanies(JSON.parse(response));
                    $('.cancel_company_modal').click();
                }   
            }
        });

    });
    
});

function fetchCompanyInfoForUpdate(id) {
    $.ajax({
        type: 'GET',
        url: '/Customer/' + id,
        success: function(response) {
            var response = JSON.parse(response);
            $('.nam-title').text(response.info.company_name);
            $('.con_info strong').remove();
            $('.con_info p:eq(0)').append('<strong>' + response.info.company_poc + '</strong>');
            $('.con_info p:eq(1)').append('<strong>' + (response.info.address != null ? response.info.address : "Na") + '</strong>');
            $('.con_info p:eq(2)').append('<strong>' + (response.info.city ? response.info.city : "NA") + '</strong>');
            $('.con_info p:eq(3)').append('<strong>' + (response.info.country).toUpperCase() + '</strong>');

            $('._cut-img img').attr('src', '/storage/company/profile-img--.jpg');

            $('#tblLoader').hide();
            $('#contentDiv').fadeIn();
            $('#contentDiv').find('table').css('width', '100%');
        }
    });
}

function fetchCompaniesList() {
    $.ajax({
        type: 'GET',
        url: '/GetCustomersList',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Company Name</th><th>Address</th><th>City</th><th>Country</th><th>Parent Company</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['company_name'] + '</td><td>' + element['address'] + '</td><td>' + element['city'] + '</td><td>' + element['country'] + '</td><td>' + (element['parent_company'] ? element['parent_company'] : "NA") + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateCustomer">Edit</button><a href="/CustomerProfile/' + element['id'] + '" id="' + element['id'] + '" class="btn btn-default">Profile</a>'+ (element["is_active"] == 1 ? '<button id="' + element['id'] + '" class="btn btn-default red-bg  deactivate_btn" title="View Detail">Deactivate</button>' : '<button id="' + element['id'] + '" class="btn btn-default activate_btn">Activate</button>') +'</td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}

function fetchPOCList(){
    $.ajax({
        type: 'GET',
        url: '/GetPOCList',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>POC Name</th><th>Company Name</th><th>Job Title</th><th>Email</th><th>Phone#</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['poc_name'] + '</td><td>' + element['company'] + '</td><td>' + element['job_title'] + '</td><td>' + element['email'] + '</td><td>' + element['bussiness_ph'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdatePOC">Edit</button>'+ (element["is_active"] == 1 ? '<button id="' + element['id'] + '" class="btn btn-default red-bg  deactivate_btn_poc" title="View Detail">Deactivate</button>' : '<button id="' + element['id'] + '" class="btn btn-default activate_btn_poc">Activate</button>') +'<a href="/poc_detail/'+ element['id'] +'" id="' + element['id'] + '" class="btn btn-default">Detail</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}

function fetchParentCompanies(company_id){
    $('#parent_company').find('option').remove();
    $('#parent_company').append('<option selected disabled value="0">Processing...</option>');

    $.ajax({
        type: 'GET',
        url: '/GetCompaniesForCustomers',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function (response) {
            var response = JSON.parse(response);
            $('#parent_company').find('option').remove();
            $('#parent_company').append('<option selected disabled value="0">Select Parent Company</option>');
            response.forEach(element => {
                $('#parent_company').append('<option value="' + element.id + '" '+ (element.id == company_id ? "selected" : '') +'>' + element.name + '</option>');
            });
            
            setTimeout(() => {
                $('#parent_company').val(company_id+"").trigger('change');
            }, 500);
        }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
