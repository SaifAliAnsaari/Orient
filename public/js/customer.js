$(document).ready(function() {

    var segments = location.href.split('/');
    var action = segments[3];

    if (action !== 'CustomerProfile')
        fetchCompaniesList();
    else {
        fetchCompanyInfoForUpdate($('#companyIdForUpdate').val());
    }
    var lastOp = "add";

    $('input[name="jobTitle"]').parent().parent().hide();
    $('input[name="orgName"]').parent().parent().hide();
    $('input[name="faxPh"]').parent().parent().hide();
    $('input[name="strn"]').parent().parent().hide();
    $('input[name="ntn"]').parent().parent().hide();
    $('input[name="merchantName"]').parent().parent().hide();
    $('select[name="merchant_type"]').parent().parent().hide();
    $('input[name="faxPh"]').parent().parent().hide();
    $('input[name="strn"]').parent().parent().hide();
    $('input[name="ntn"]').parent().parent().hide();

    $('input[name="compName"]').parent().parent().fadeIn();
    $('input[name="homePh"]').parent().parent().fadeIn();
    $('input[name="cnic"]').parent().parent().fadeIn();

    $('select[name="type"]').change(function() {
        debugger;
        if ($(this).val() == "1") {

            $('input[name="jobTitle"]').parent().parent().hide();
            $('input[name="orgName"]').parent().parent().hide();
            $('input[name="faxPh"]').parent().parent().hide();
            $('input[name="strn"]').parent().parent().hide();
            $('input[name="ntn"]').parent().parent().hide();
            $('input[name="merchantName"]').parent().parent().hide();
            $('select[name="merchant_type"]').parent().parent().hide();
            $('input[name="faxPh"]').parent().parent().hide();
            $('input[name="strn"]').parent().parent().hide();
            $('input[name="ntn"]').parent().parent().hide();

            $('input[name="compName"]').parent().parent().fadeIn();
            $('input[name="homePh"]').parent().parent().fadeIn();
            $('input[name="cnic"]').parent().parent().fadeIn();
        } else if ($(this).val() == "2") {

            $('input[name="compName"]').parent().parent().hide();
            $('input[name="homePh"]').parent().parent().hide();
            $('input[name="cnic"]').parent().parent().hide();
            $('input[name="merchantName"]').parent().parent().hide();
            $('select[name="merchant_type"]').parent().parent().hide();
            $('input[name="faxPh"]').parent().parent().hide();
            $('input[name="strn"]').parent().parent().hide();
            $('input[name="ntn"]').parent().parent().hide();

            $('input[name="orgName"]').parent().parent().fadeIn();
            $('input[name="faxPh"]').parent().parent().fadeIn();
            $('input[name="strn"]').parent().parent().fadeIn();
            $('input[name="ntn"]').parent().parent().fadeIn();
            $('input[name="jobTitle"]').parent().parent().fadeIn();

        } else {

            $('input[name="compName"]').parent().parent().hide();
            $('input[name="homePh"]').parent().parent().hide();
            $('input[name="cnic"]').parent().parent().hide();
            $('input[name="orgName"]').parent().parent().hide();
            $('input[name="faxPh"]').parent().parent().hide();
            $('input[name="strn"]').parent().parent().hide();
            $('input[name="ntn"]').parent().parent().hide();

            $('input[name="merchantName"]').parent().parent().fadeIn();
            $('select[name="merchant_type"]').parent().parent().fadeIn();
            $('input[name="faxPh"]').parent().parent().fadeIn();
            $('input[name="strn"]').parent().parent().fadeIn();
            $('input[name="ntn"]').parent().parent().fadeIn();
            $('input[name="jobTitle"]').parent().parent().fadeIn();
        }
    });

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
            $('input[name="businessPh"]').val("");
            $('input[name="businessPh"]').blur();
            $('input[name="homePh"]').val("");
            $('input[name="homePh"]').blur();
            $('input[name="mobPh"]').val("");
            $('input[name="mobPh"]').blur();
            $('input[name="whatsappPh"]').val("");
            $('input[name="whatsappPh"]').blur();
            $('input[name="faxPh"]').val("");
            $('input[name="faxPh"]').blur();
            $('input[name="address"]').val("");
            $('input[name="address"]').blur();
            $('input[name="city"]').val("");
            $('input[name="city"]').blur();
            $('input[name="state"]').val("");
            $('input[name="state"]').blur();
            $('input[name="region"]').val("");
            $('input[name="region"]').blur();
            $('input[name="email"]').val("");
            $('input[name="email"]').blur();
            $('input[name="webpage"]').val("");
            $('input[name="webpage"]').blur();
            $('textarea[name="description"]').val("");

            $('#saveCustomerForm').find("select").val("0").trigger('change');
            $('select[name="deliveryPorts"], select[name="documentTypes"]').val("").trigger('change');
        }
        lastOp = 'add';
        if ($('#saveCustomerForm input[name="_method"]').length) {
            $('#saveCustomerForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll')

        $('#dropifyImgDiv').empty();
        $('#dropifyImgDiv').append('<input type="file" name="compPicture" id="companyPic" class="dropify" />');
        $('#companyPic').dropify();
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

        $('#dropifyImgDiv').empty();
        $('#dropifyImgDiv').append('<input type="file" name="compPicture" id="companyPic" class="dropify" />');
        $.ajax({
            type: 'GET',
            url: '/Customer/' + id,
            success: function(response) {
                var response = JSON.parse(response);
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

                $('input[name="businessPh"]').focus();
                $('input[name="businessPh"]').val(response.info.business_phone);
                $('input[name="businessPh"]').blur();

                $('input[name="homePh"]').focus();
                $('input[name="homePh"]').val(response.info.home_phone);
                $('input[name="homePh"]').blur();

                $('input[name="mobPh"]').focus();
                $('input[name="mobPh"]').val(response.info.mobile_phone);
                $('input[name="mobPh"]').blur();

                $('input[name="whatsappPh"]').focus();
                $('input[name="whatsappPh"]').val(response.info.whatsapp_phone);
                $('input[name="whatsappPh"]').blur();

                $('input[name="faxPh"]').focus();
                $('input[name="faxPh"]').val(response.info.fax_number);
                $('input[name="faxPh"]').blur();

                $('input[name="address"]').focus();
                $('input[name="address"]').val(response.info.address);
                $('input[name="address"]').blur();

                $('input[name="city"]').focus();
                $('input[name="city"]').val(response.info.city);
                $('input[name="city"]').blur();

                $('input[name="state"]').focus();
                $('input[name="state"]').val(response.info.state);
                $('input[name="state"]').blur();

                $('input[name="region"]').focus();
                $('input[name="region"]').val(response.info.region);
                $('input[name="region"]').blur();

                $('input[name="email"]').focus();
                $('input[name="email"]').val(response.info.email);
                $('input[name="email"]').blur();

                $('input[name="webpage"]').focus();
                $('input[name="webpage"]').val(response.info.webpage);
                $('input[name="webpage"]').blur();

                $('select[name="type"]').val(response.info.customer_type).trigger('change');
                $('select[name="parentCompnay"]').val(response.info.parent_company).trigger('change');
                $('select[name="country"]').val(response.info.country).trigger('change');
                $('select[name="acqSource"]').val(response.info.customer_acquisition_source).trigger('change');
                $('select[name="zone"]').val(response.info.zone_id).trigger('change');

                var imgUrl = response.base_url + '/storage/company/' + response.info.picture;
                $.get(imgUrl)
                    .done(function() {
                        $("#companyPic").attr("data-height", '100px');
                        $("#companyPic").attr("data-default-file", imgUrl);
                        $('#companyPic').dropify();
                    })

                var docTypes = [];
                response.document_types.forEach(element => {
                    docTypes.push(element["document_id"]);
                });

                var delivPorts = [];
                response.delivery_ports.forEach(element => {
                    delivPorts.push(element["port_name"]);
                });

                $('select[name="deliveryPorts"]').val(delivPorts).trigger("change");
                $('select[name="documentTypes"]').val(docTypes).trigger("change");
                $('textarea[name="description"]').val(response.info.remarks);

            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '#saveCustomer', function() {

        $('.validationErrors').remove();

        if (!$('input[name="compName"]').val() || !$('input[name="poc"]').val() || $('select[name="zone"]').val() == 0 || $('select[name="type"]').val() == 0 || $('select[name="country"]').val() == 0 || !$('input[name="businessPh"]').val() || !$('input[name="address"]').val() || !$('input[name="city"]').val() || $('select[name="documentTypes"]').val() == 0 || $('select[name="deliveryPorts"]').val() == 0) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please provide all the required information (*)');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        $('#saveCustomer').attr('disabled', 'disabled');
        $('#cancelCustomer').attr('disabled', 'disabled');
        $('#saveCustomer').text('Processing..');

        $('input[name="document_types"]').val($('select[name="documentTypes"]').val());
        $('input[name="delivery_ports"]').val($('select[name="deliveryPorts"]').val());

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
                if (JSON.parse(response) == "success") {
                    if (action == 'CustomerProfile') {
                        fetchCompanyInfoForUpdate($('input[name="product_updating_id"]').val());
                    } else {
                        fetchCompaniesList();
                    }
                    $('#saveCustomer').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('#saveCustomer').text('Save');

                    if ($('#operation').val() !== "update") {
                        $('#saveCustomerForm').find("input[type=text], textarea").val("");
                        $('#saveCustomerForm').find("select").val("0").trigger('change');
                        $('select[name="deliveryPorts"], select[name="documentTypes"]').val("").trigger('change');
                        $('.dropify-clear').click();
                    }

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Customer have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#saveCustomer').removeAttr('disabled');
                    $('#cancelCustomer').removeAttr('disabled');
                    $('#saveCustomer').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add customer at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
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

    $(document).on('click', '.deleteCustomer', function() {
        var customerId = $(this).attr('id');
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.parent().ajaxSubmit({
            type: "POST",
            url: '/Customer/' + customerId,
            data: thisRef.parent().serialize(),
            cache: false,
            success: function(response) {
                if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Customer have been deleted');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    thisRef.parent().parent().parent().remove();
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to delete the customer at this moment');
                    setTimeout(() => {
                        thisRef.removeAttr('disabled');
                        $('#notifDiv').fadeOut();
                    }, 3000);
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
            $('.con_info p:eq(1)').append('<strong>' + response.info.business_phone + '</strong>');
            $('.con_info p:eq(2)').append('<strong>' + (response.info.region ? response.info.region : "NA") + '</strong>');
            $('.con_info p:eq(3)').append('<strong>' + (response.info.country).toUpperCase() + '</strong>');

            $('._cut-img img').attr('src', '/storage/company/' + response.info.picture);

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
        success: function(response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Company Name</th><th>POC</th><th>Country</th><th>Region</th><th>Customer Type</th><th>Parent Company</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['company_name'] + '</td><td>' + element['company_poc'] + '</td><td>' + element['country'] + '</td><td>' + element['region'] + '</td><td>' + element['customer_type'] + '</td><td>' + element['parent_company'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateCustomer">Edit</button><a href="/CustomerProfile/' + element['id'] + '" id="' + element['id'] + '" class="btn btn-default">Profile</a><form id="deleteCustomerForm" style="display: inline-block"><input type="text" name="_method" value="DELETE" hidden /><input type="text" name="_token" value="' + $('input[name="tokenForAjaxReq"]').val() + '" hidden /><button type="button" id="' + element['id'] + '" class="btn btn-default red-bg deleteCustomer" title="Delete">Delete</button></form></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}