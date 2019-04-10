var add_poc_list = [];
var add_competition_list = [];
var purpose_array = [];
var products_array = [];
$(document).ready(function () {
    var segments = location.href.split('/');
    var action = segments[3];
    // var add_poc_list = [];
    // var add_competition_list = [];
    // var purpose_array = [];
    // var products_array = [];

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    if (action == 'new_cvr') {
        fetchCustomersforCVR();
    }else if(action == 'cvr_list'){
        fetchCvrList();
    }else if(action == 'edit_cvr'){
        fetchCustomersforCVR();
        
        setTimeout(() => {
            fetchCurrentCvrData();
        }, 500); 
    }


    //Customer Selected
    $(document).on('change', '#cvr_customers_list', function(){
        id = $(this).val();
        $('location').val('');
        $('.cvr_poc_list').find('option').remove();
        $('.cvr_poc_list').append('<option selected disabled value="0">Processing...</option>');

        $.ajax({
            type: 'GET',
            url: '/get_cust_address',
            data: {
                _token: '{!! csrf_token() !!}',
               id: id
            },
            success: function(response) {
               // console.log(response); return;
                var response = JSON.parse(response);
                $('#location').val(response.address.address + ", " + response.address.city);
                $('#location').focus();  
                
                $('.cvr_poc_list').find('option').remove();
                $('.cvr_poc_list').append('<option selected disabled value="0">Select POC</option>');
                response.pocs.forEach(element => {
                    $('.cvr_poc_list').append('<option name="' + element.poc_name + '" value="' + element.id + '">' + element.poc_name + '</option>');
                });
            }
        });
    });


    //Open sidebaar to add customers
    $(document).on('click', '.add_new_cust', function () {
        $('#saveCustomerForm_CVR').find('select').val('0').trigger('change');
        $('#saveCustomerForm_CVR').find('textarea').text('');
        $('[name="compName"]').val('');
        $('[name="poc"]').val('');
        $('[name="jobTitle"]').val('');
        $('[name="bussinessPH"]').val('');
        $('[name="email"]').val('');
        $('[name="address"]').val('');
        $('[name="city"]').val('');
        $('[name="state"]').val('');
        $('[name="country"]').val('');
        $('[name="web_address"]').val('');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    //Save Customers
    $(document).on('click', '.saveCustomer_CVR', function () {
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

        if (!$('input[name="email"]').val() == "") {
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
        if (jQuery.inArray(false, verif) != -1) {
            return;
        }

        //alert($('[name="_token"]').val());

        $('.saveCustomer_CVR').attr('disabled', 'disabled');
        $('#cancelCustomer').attr('disabled', 'disabled');
        $('.saveCustomer_CVR').text('Processing..');

        var ajaxUrl = "/Customer";
        $('#saveCustomerForm_CVR').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveCustomerForm_CVR').serialize(),
            cache: false,
            success: function (response) {
                if (JSON.parse(response) == "failed") {
                    $('.saveCustomer_CVR').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('.saveCustomer_CVR').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add customer at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    fetchCustomersforCVR();
                    $('.saveCustomer_CVR').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('.saveCustomer_CVR').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Customer have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    $('#saveCustomerForm_CVR').find('select').val('0').trigger('change');
                    $('#saveCustomerForm_CVR').find('textarea').text('');
                    $('[name="compName"]').val('');
                    $('[name="poc"]').val('');
                    $('[name="jobTitle"]').val('');
                    $('[name="bussinessPH"]').val('');
                    $('[name="email"]').val('');
                    $('[name="address"]').val('');
                    $('[name="city"]').val('');
                    $('[name="state"]').val('');
                    $('[name="country"]').val('');
                    $('[name="web_address"]').val('');
                }

            },
            error: function (err) {
                $('.saveCustomer').removeAttr('disabled');
                $('#cancelCustomer').removeAttr('disabled');
                $('.saveCustomer').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });
    });


    //Add poc from dropdown
    $(document).on('change', '.cvr_poc_list', function () {

        if ($('.cvr_poc_list').val() == 0 || $('.cvr_poc_list').val() == null) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Select POC.');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }
        var poc_name = $('.cvr_poc_list').find('option:selected').attr("name");
        var poc_id = $('.cvr_poc_list').find('option:selected').val();

        var poc_id_found = false;
        add_poc_list.find(x => {
            //debugger;
            if (x.poc_id == poc_id) {
                poc_id_found = true;
                find_secondary_cust = true;
                return;
            }
        });

        if (!poc_id_found) {
            add_poc_list.push({
                "poc_id": poc_id,
                "poc_name": poc_name
            });
            find_secondary_cust = true;
        }

        if (add_poc_list != "") {
            $('.poc_show_list').empty();
            // $('#hidden_poc_list').val('');
            // $('#hidden_poc_list').val(add_poc_list);
            add_poc_list.forEach(element => {
                $('.poc_show_list').append(' <div class="alert fade show alert-color _add-secon" role="alert">' + element.poc_name + '<button name="' + element.poc_id + '" type="button" class="close delete_one_poc" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>');
            });
        }
        // console.log($('#hidden_poc_list').val());
    });

    

    $(document).on('click', '.delete_one_poc', function () {
       // debugger;
        var id = $(this).attr('name');
        add_poc_list.splice(add_poc_list.findIndex(x => x.poc_id == id), 1);
    });



    //Add Poc From Modal
    $(document).on('click', '.add_poc_modal', function () {
        var test = $('#cvr_customers_list').val();
        if(!test || test == 0){
           // debugger;
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please add customer first.');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }


        var thisRef = $(this);
        var verif = [];

        $('.required_modal').each(function () {
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

        if (!$('#email_modal').val() == "") {
            if (!validateEmail($('#email_modal').val())) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Invalid email format');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }
        }
        if (jQuery.inArray(false, verif) != -1) {
            return;
        }

        thisRef.text('Processing...');
        thisRef.attr('disabled', 'disabled');
        $('.cancel_modal').attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: '/save_poc_from_modal',
            data: {
                _token: $('input[name="_token"]').val(),
                poc_name: $('#poc_name_modal').val(),
                company_name: test,
                job_title: $('#jobTitle_modal').val(),
                phone: $('#businessPH_modal').val(),
                email: $('#email_modal').val(),
            },
            success: function (response) {
                var response = JSON.parse(response);
                //console.log(response);
                thisRef.text('Add');
                thisRef.removeAttr('disabled');
                $('.cancel_modal').removeAttr('disabled');
                if (response == 'failed') {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add POC at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if (response == 'already_exist') {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Email already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    //fetchCustomersforCVR();
                    add_poc_list.push({
                        "poc_id": response,
                        "poc_name": $('#poc_name_modal').val()
                    });
                    if (add_poc_list != "") {
                        $('.poc_show_list').empty();
                        // $('#hidden_poc_list').val('');
                        // $('#hidden_poc_list').val(add_poc_list);
                        add_poc_list.forEach(element => {
                            $('.poc_show_list').append(' <div class="alert fade show alert-color _add-secon" role="alert">' + element.poc_name + '<button name="' + element.poc_id + '" type="button" class="close delete_one_poc" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>');
                        });
                    }
                    $('#poc_name_modal').val('');
                    $('#company_name_modal').val('');
                    $('#jobTitle_modal').val('');
                    $('#businessPH_modal').val('');
                    $('#email_modal').val('');
                    $('#address_modal').val('');
                    $('#city_modal').val('');
                    $('#state_modal').val('');
                }
            }
        });


    });



    //Add Competition
    $(document).on('click', '.add_competition_modal', function () {
        var verif = [];
        $('.required_competition').each(function () {
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
            if (jQuery.inArray(false, verif) != -1) {
                return;
            }

            var name = $('#competition_name').val();
            var strength = $('#competition_strength').find('option:selected').val();

            var competition_id_found = false;
            add_competition_list.find(x => {
                //debugger;
                if (x.name == name && x.strength == strength) {
                    competition_id_found = true;
                    find_secondary_cust = true;
                    return;
                }
            });

            if (!competition_id_found) {
                add_competition_list.push({
                    "strength": strength,
                    "name": name
                });
                find_secondary_cust = true;
            }

            if (add_competition_list != "") {
                $('.competition_list_div').empty();
                $('#hidden_competition_list').val('');
                $('#hidden_competition_list').val(add_competition_list);
                add_competition_list.forEach(element => {
                    $('.competition_list_div').append('<div class="col-md-6"><div class="alert fade show alert-color _add-secon w-100 mr-0" role="alert"><div class="row"><div class="col-md-6"><strong>Name: &nbsp;</strong>' + element.name + '</div><div class="col-md-6"><strong>Strength: &nbsp;</strong>' + element.strength + '</div><button id="' + element.name + '-' + element.strength + '" type="button" class="close delete_one_competitor" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div></div>');
                });
            }
            //console.log(add_competition_list);

        });
    });

    $(document).on('click', '.delete_one_competitor', function () {
        //debugger;
        var id = $(this).attr('id').split('-');
        var name = id[0];
        var strength = id[1];
        add_competition_list.splice(add_competition_list.findIndex(x => x.name == name && x.strength == strength), 1);
        // console.log(add_competition_list);
    });



    //Purpose of Visit
    $(document).on('click', '.purpose_checkboxes', function () {
        if (purpose_array.includes($(this).val())) {
            purpose_array.splice(purpose_array.indexOf($(this).val()), 1);
            $('#purpose_hidden_array').val("");
            $('#purpose_hidden_array').val(purpose_array);
        } else {
            purpose_array.push($(this).val());
            $('#purpose_hidden_array').val("");
            $('#purpose_hidden_array').val(purpose_array);
        }
        //console.log($('#purpose_hidden_array').val());
    });




    //Products
    $(document).on('click', '.checkboxes_products', function () {
        //debugger;
        if (products_array.includes($(this).val())) {
            products_array.splice(products_array.indexOf($(this).val()), 1);
            $('#products_hidden_list').val("");
            $('#products_hidden_list').val(products_array);
        } else {
            products_array.push($(this).val());
            $('#products_hidden_list').val("");
            $('#products_hidden_list').val(products_array);
        }
        // console.log($('#products_hidden_list').val());
    });



    //Save 
    $(document).on('click', '.save_cvr', function () {

        // if ($('#datepicker').val() == "" || $('#cvr_customers_list').val() == '0' || $('#cvr_customers_list').val() == null || $('#location').val() == "" || $('#time_spent').val() == "" || $("input[name='Opportunity']:checked").val() == "" || $("input[name='Opportunity']:checked").val() == "undefined" || $("input[name='Opportunity']:checked").val() == null || $("input[name='AnnualBusiness']:checked").val() == "" || $("input[name='AnnualBusiness']:checked").val() == "undefined" || $("input[name='AnnualBusiness']:checked").val() == null || $("input[name='relationship']:checked").val() == "" || $("input[name='relationship']:checked").val() == "undefined" || $("input[name='relationship']:checked").val() == null || purpose_array.length == 0 || products_array.length == 0 || add_poc_list.length == 0 || add_competition_list.length == 0) {
        //     $('#notifDiv').fadeIn();
        //     $('#notifDiv').css('background', 'red');
        //     $('#notifDiv').text('Something Missing');
        //     setTimeout(() => {
        //         $('#notifDiv').fadeOut();
        //     }, 3000);
        //     return;
        // }

        var verif = [];
        $('.required_core').each(function () {
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

        if ($("input[name='Opportunity']:checked").val() == "" || $("input[name='Opportunity']:checked").val() == "undefined" || $("input[name='Opportunity']:checked").val() == null ) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Select Opportunity');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if($("input[name='AnnualBusiness']:checked").val() == "" || $("input[name='AnnualBusiness']:checked").val() == "undefined" || $("input[name='AnnualBusiness']:checked").val() == null){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Select Annual Bussiness Value');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if($("input[name='relationship']:checked").val() == "" || $("input[name='relationship']:checked").val() == "undefined" || $("input[name='relationship']:checked").val() == null){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Select Relationship');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }


        if(purpose_array.length == 0){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Define Purpose of Visit');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if(products_array.length == 0){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Define Prooducts');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if(add_poc_list.length == 0){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Add POC');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if(add_competition_list.length == 0){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Define Competition');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }



        $('.save_cvr').text('Processing...');
        $('.save_cvr').attr('disabled', 'disabled');
        $('.cancel_cvr').attr('disabled', 'disabled');
        $('.preview_cvr').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            url: '/save_cvr',
            data: {
                _token: $('input[name="_token"]').val(),
                poc_list: add_poc_list,
                competition_list: add_competition_list,
                purpose: purpose_array + "",
                products: products_array,
                date: $('#datepicker').val(),
                customer_id: $('#cvr_customers_list').val(),
                location: $('#location').val(),
                time_spent: $('#time_spent').val(),
                opportunity: $("input[name='Opportunity']:checked").val() + "",
                annualBusiness: $("input[name='AnnualBusiness']:checked").val() + "",
                relationship: $("input[name='relationship']:checked").val() + "",
                description: $('#des_cvr').val()
            },
            success: function (response) {
                // console.log(response);
                // return;

                if (JSON.parse(response) == "failed") {
                    $('.save_cvr').removeAttr('disabled');
                    $('.cancel_cvr').removeAttr('disabled');
                    $('.preview_cvr').removeAttr('disabled');
                    $('.save_cvr').text('Submit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add CVR at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    //fetchCustomersforCVR();
                    $('.save_cvr').removeAttr('disabled');
                    $('.cancel_cvr').removeAttr('disabled');
                    $('.preview_cvr').removeAttr('disabled');
                    $('.save_cvr').text('Submit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('CVR has been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    //$('#cvr_customers_list').val('0').trigger('change');
                    //$('#location').val('');
                    $('#time_spent').val('');
                    $("input[name='des_cvr']").text('');
                   // $('.cvr_poc_list').val('0').trigger('change');
                    $('.purpose_checkboxes').prop('checked', false);
                    $('.checkboxes_products').prop('checked', false);
                    $("input[name='Opportunity']").prop('checked', false);
                    $("input[name='AnnualBusiness']").prop('checked', false);
                    $("input[name='relationship']").prop('checked', false);
                    purpose_array = [];
                    products_array = [];
                    add_poc_list = [];
                    add_competition_list = [];
                    $('.poc_show_list').empty();
                    $('.competition_list_div').empty();
                }
            }
        });

    });

    //Print
    $(document).on('click', '.print_page', function(){
        printDiv();
    });





    //Update CVR
    $(document).on('click', '.update_cvr', function(){
        if ($('#datepicker').val() == "" || $('#cvr_customers_list').val() == '0' || $('#cvr_customers_list').val() == null || $('#location').val() == "" || $('#time_spent').val() == "" || $("input[name='Opportunity']:checked").val() == "" || $("input[name='Opportunity']:checked").val() == "undefined" || $("input[name='Opportunity']:checked").val() == null || $("input[name='AnnualBusiness']:checked").val() == "" || $("input[name='AnnualBusiness']:checked").val() == "undefined" || $("input[name='AnnualBusiness']:checked").val() == null || $("input[name='relationship']:checked").val() == "" || $("input[name='relationship']:checked").val() == "undefined" || $("input[name='relationship']:checked").val() == null || purpose_array.length == 0 || products_array.length == 0 || add_poc_list.length == 0 || add_competition_list.length == 0) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Something Missing');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        $('.update_cvr').text('Processing...');
        $('.update_cvr').attr('disabled', 'disabled');
        $('.cancel_cvr').attr('disabled', 'disabled');
        $('.preview_cvr').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            url: '/update_cvr',
            data: {
                _token: $('input[name="_token"]').val(),
                cvr_id: $('#hidden_cvr_id').val(),
                poc_list: add_poc_list,
                competition_list: add_competition_list,
                purpose: purpose_array + "",
                products: products_array,
                date: $('#datepicker').val(),
                customer_id: $('#cvr_customers_list').val(),
                location: $('#location').val(),
                time_spent: $('#time_spent').val(),
                opportunity: $("input[name='Opportunity']:checked").val() + "",
                annualBusiness: $("input[name='AnnualBusiness']:checked").val() + "",
                relationship: $("input[name='relationship']:checked").val() + "",
                description: $('#des_cvr').val()
            },
            success: function (response) {
                // console.log(response);
                // return;

                if (JSON.parse(response) == "failed") {
                    $('.update_cvr').removeAttr('disabled');
                    $('.update_cvr').removeAttr('disabled');
                    $('.preview_cvr').removeAttr('disabled');
                    $('.update_cvr').text('Submit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add CVR at the moment.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                } else if (JSON.parse(response) == "success") {
                    //fetchCustomersforCVR();
                    $('.update_cvr').removeAttr('disabled');
                    $('.cancel_cvr').removeAttr('disabled');
                    $('.preview_cvr').removeAttr('disabled');
                    $('.update_cvr').text('Submit');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('CVR has been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });


    });




});

function fetchCustomersforCVR() {
    $('#cvr_customers_list').find('option').remove();
    $('#cvr_customers_list').append('<option selected disabled value="0">Processing...</option>');

    // $('.cvr_poc_list').find('option').remove();
    // $('.cvr_poc_list').append('<option selected disabled value="0">Processing...</option>');

    $.ajax({
        type: 'GET',
        url: '/GetCustForCVR',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function (response) {

            var response = JSON.parse(response);
            $('#cvr_customers_list').find('option').remove();
            $('#cvr_customers_list').append('<option selected disabled value="0">Select Customer</option>');
            response.customers.forEach(element => {
                $('#cvr_customers_list').append('<option value="' + element.id + '">' + element.company_name + '</option>');
            });

            // $('.cvr_poc_list').find('option').remove();
            // $('.cvr_poc_list').append('<option selected disabled value="0">Select POC</option>');
            // response.poc.forEach(element => {
            //     $('.cvr_poc_list').append('<option name="' + element.poc_name + '" value="' + element.id + '">' + element.poc_name + '</option>');
            // });
        }
    });
}

function fetchCvrList(){
    $.ajax({
        type: 'GET',
        url: '/GetCVRList',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Sales Engineer</th><th>Customer</th><th>Date Of Report</th><th>Date Of Visit</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['created_by'] + '</td><td>' + element['customer_name'] + '</td><td>' + element['report_created_at'] + '</td><td>' + element['date_of_visit'] + '</td><td><a href="/edit_cvr/'+ element['id'] +'"><button id="' + element['id'] + '" class="btn btn-default btn-line">Edit</button></a><a href="/cvr_preview/'+ element['id'] +'" id="' + element['id'] + '" class="btn btn-default">Preview</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}

function fetchCurrentCvrData(){
    var segments = location.href.split('/');
    
    $.ajax({
        type: 'GET',
        url: '/GetCurrentCvr/'+segments[4],
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            var response = JSON.parse(response);
            $('#curr_date').text(response.core.report_created_at);
            $('#user_name').text(response.core.created_by);
            //$('#datepicker').val(response.core.date_of_visit);
            $('#datepicker').datepicker("setDate", new Date(response.core.date_of_visit));
            $('#cvr_customers_list').val(response.core.customer_visited).trigger('change');
            $('#location').val(response.core.location);
            $('#time_spent').val(response.core.time_spent);
            $('.opportunity').filter(function(){
                return this.value === response.core.opportunity ;
            }).prop('checked', true);
            $('.bussiness_annual').filter(function(){
                return this.value === response.core.bussiness_value ;
            }).prop('checked', true);
            $('.relationship').filter(function(){
                return this.value === response.core.relationship ;
            }).prop('checked', true);
            $('#des_cvr').text(response.core.description);
            if (response.core.purpose_of_visit.indexOf(',') > -1){
                var array = response.core.purpose_of_visit.split(",");
                $.each(array,function(i){
                    $('.purpose_checkboxes').filter(function(){
                        if(this.value === array[i]){
                            purpose_array.push(array[i]);
                        }
                        return this.value === array[i];
                    }).prop('checked', true);
                    });
            }else{
                $('.purpose_checkboxes').filter(function(){
                    if(this.value === response.core.purpose_of_visit){
                        purpose_array.push(response.core.purpose_of_visit);
                    }
                    return this.value === response.core.purpose_of_visit;
                }).prop('checked', true);
            }

            setTimeout(() => {
                $('#location').focus(); 
                $('#time_spent').focus();
            }, 500);
            
            $.each(response.products,function(i){
                $('.checkboxes_products').filter(function(){
                    if(this.value === response.products[i].category_id + ""){
                        products_array.push(response.products[i].category_id + "");
                    }
                    return this.value === response.products[i].category_id + "";
                }).prop('checked', true);
            });
            
            $('.poc_show_list').empty();
            $.each(response.poc,function(i){
                $('.poc_show_list').append('<div class="alert fade show alert-color _add-secon" role="alert">'+ response.poc[i].poc_name +'<button type="button" name="' + response.poc[i].poc_id + '" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                add_poc_list.push({
                    "poc_id": response.poc[i].poc_id,
                    "poc_name": response.poc[i].poc_name
                });
            });

            $('.competition_list_div').empty();
            $.each(response.competitions,function(i){
                $('.competition_list_div').append('<div class="col-md-6"><div class="alert fade show alert-color _add-secon w-100 mr-0" role="alert"><div class="row"><div class="col-md-6"><strong>Name: &nbsp;</strong>' + response.competitions[i].name + '</div><div class="col-md-6"><strong>Strength: &nbsp;</strong>' + response.competitions[i].strength + '</div><button id="' + response.competitions[i].name + '-' + response.competitions[i].strength + '" type="button" class="close delete_one_competitor" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div></div>');
                add_competition_list.push({
                    "strength": response.competitions[i].strength,
                    "name": response.competitions[i].name
                });
            });

            // console.log(purpose_array);
            // console.log(products_array);
           
        }
    }); 
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function printDiv() {
    var printContents = document.getElementById('printResult').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
