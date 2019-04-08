$(document).ready(function() { 
    var segments = location.href.split('/');
    var action = segments[3];

    if(action == 'main_category'){
        fetchMainCats();
    }else if(action == 'sub_category'){
        fetchSubCats();
    }else if(action == 'product_category'){
        fetchProductCats();
    }

    var lastOp = 'add';

    //Open SideBar For Adding
    $(document).on('click', '.openDataSidebarForAddingMainCat', function(){
        if (lastOp == "update") {
            $('input[name="main_cat_name"]').val("");
            $('input[name="main_cat_name"]').blur();
        }

        if ($('#saveMainCategotyForm input[name="_method"]').length) {
            $('#saveMainCategotyForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForAddingSubCat', function(){
        if (lastOp == "update") {
            $('input[name="sub_cat_name"]').val("");
            $('input[name="sub_cat_name"]').blur();
            $('select[name="select_main_cat"]').val('0').trigger('change');
        }

        if ($('#saveSubCategotyForm input[name="_method"]').length) {
            $('#saveSubCategotyForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForAddingProductCat', function(){
        if (lastOp == "update") {
            $('input[name="product_cat_name"]').val("");
            $('input[name="product_cat_name"]').blur();
        }

        if ($('#saveProductCategotyForm input[name="_method"]').length) {
            $('#saveProductCategotyForm input[name="_method"]').remove();
        }
        $('input[id="operation"]').val('add');
        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });



    //Open Sidebar for Update
    $(document).on('click', '.openDataSidebarForUpdateMainCat', function(){
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="hidden_cat_id"]').val(id);
        if (!$('#saveMainCategotyForm input[name="_method"]').length) {
            // $('#savePOCForm').append('<input name="_method" value="PUT" hidden />');
        }
        $.ajax({
            type: 'GET',
            url: '/GetSelectedMainCat/' + id,
            success: function(response) {
                var response = JSON.parse(response);
                
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="main_cat_name"]').focus();
                $('input[name="main_cat_name"]').val(response.name);
                $('input[name="main_cat_name"]').blur();
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '.openDataSidebarForUpdateSubCat', function(){
        $('input[id="operation"]').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        var id = $(this).attr('id');
        $('input[name="hidden_sub_cat_id"]').val(id);
        if (!$('#saveSubCategotyForm input[name="_method"]').length) {
            // $('#savePOCForm').append('<input name="_method" value="PUT" hidden />');
        }
        $.ajax({
            type: 'GET',
            url: '/GetSelectedSubCat/' + id,
            success: function(response) {
                var response = JSON.parse(response);
                
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('#uploadedImg').remove();

                $('input[name="sub_cat_name"]').focus();
                $('input[name="sub_cat_name"]').val(response.name);
                $('input[name="sub_cat_name"]').blur();

                $('select[name="select_main_cat"]').val(response.main_cat_id).trigger('change');
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });



    //Save
    $(document).on('click', '#saveMainCat', function(){
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
        
        $('#saveMainCat').attr('disabled', 'disabled');
        $('#cancelMainCat').attr('disabled', 'disabled');
        $('#saveMainCat').text('Processing..');

        var ajaxUrl = "/save_main_cat";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/update_main_cat/" + $('input[name="hidden_cat_id"]').val();
        }

        $('#saveMainCategotyForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveMainCategotyForm').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "failed") {
                    $('#saveMainCat').removeAttr('disabled');
                    $('#cancelMainCat').removeAttr('disabled');
                    $('#saveMainCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add category at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    fetchMainCats();
                    $('#saveMainCat').removeAttr('disabled');
                    $('#cancelMainCat').removeAttr('disabled');
                    $('#saveMainCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Category have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                    if($('#operation').val() == 'add'){
                       $('[name="main_cat_name"]').val('');
                    }
                    
                }else if(JSON.parse(response) == "already_exist"){
                    $('#saveMainCat').removeAttr('disabled');
                    $('#cancelMainCat').removeAttr('disabled');
                    $('#saveMainCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Category already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#saveMainCat').removeAttr('disabled');
                $('#cancelMainCat').removeAttr('disabled');
                $('#saveMainCat').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    $(document).on('click', '#saveSubCat', function(){
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
        //alert(jQuery.inArray(false, verif)); return;
        if(jQuery.inArray(false, verif) != -1){
            return;
        }
        $('#saveSubCat').attr('disabled', 'disabled');
        $('#cancelSubCat').attr('disabled', 'disabled');
        $('#saveSubCat').text('Processing..');

        var ajaxUrl = "/save_sub_cat";
        if ($('#operation').val() !== "add") {
            ajaxUrl = "/update_sub_cat/" + $('input[name="hidden_sub_cat_id"]').val();
        }

        $('#saveSubCategotyForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveSubCategotyForm').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "failed") {
                    $('#saveSubCat').removeAttr('disabled');
                    $('#cancelSubCat').removeAttr('disabled');
                    $('#saveSubCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add category at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    fetchSubCats();
                    $('#saveSubCat').removeAttr('disabled');
                    $('#cancelSubCat').removeAttr('disabled');
                    $('#saveSubCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Category have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                    if($('#operation').val() == 'add'){
                       $('[name="main_cat_name"]').val('');
                    }
                    
                }else if(JSON.parse(response) == "already_exist"){
                    $('#saveSubCat').removeAttr('disabled');
                    $('#cancelSubCat').removeAttr('disabled');
                    $('#saveSubCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Category already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#saveSubCat').removeAttr('disabled');
                $('#cancelSubCat').removeAttr('disabled');
                $('#saveSubCat').text('Save');
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small class="validationErrors" style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    $(document).on('click', '#saveProductCat', function(){
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
        //alert(jQuery.inArray(false, verif)); return;
        if(jQuery.inArray(false, verif) != -1){
            return;
        }
        $('#saveProductCat').attr('disabled', 'disabled');
        $('#cancelProductCat').attr('disabled', 'disabled');
        $('#saveProductCat').text('Processing..');

        var ajaxUrl = "/save_product_cat";

        if ($('#operation').val() !== "add") {
            ajaxUrl = "/update_product_cat/" + $('input[name="hidden_product_cat_id"]').val();
        }
        $('#saveProductCategotyForm').ajaxSubmit({
            type: "POST",
            url: ajaxUrl,
            data: $('#saveProductCategotyForm').serialize(),
            cache: false,
            success: function(response) {
                // console.log(response);
                // return;
                if (JSON.parse(response) == "failed") {
                    $('#saveProductCat').removeAttr('disabled');
                    $('#cancelProductCat').removeAttr('disabled');
                    $('#saveProductCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add category at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    
                } else if(JSON.parse(response) == "success"){
                    fetchProductCats();
                    $('#saveProductCat').removeAttr('disabled');
                    $('#cancelProductCat').removeAttr('disabled');
                    $('#saveProductCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    $('#notifDiv').text('Category have been added successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                    if($('#operation').val() == 'add'){
                       $('[name="main_cat_name"]').val('');
                    }
                    
                }else if(JSON.parse(response) == "already_exist"){
                    $('#saveProductCat').removeAttr('disabled');
                    $('#cancelProductCat').removeAttr('disabled');
                    $('#saveProductCat').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Category already exist');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
               
            },
            error: function(err) {
                $('#saveProductCat').removeAttr('disabled');
                $('#cancelProductCat').removeAttr('disabled');
                $('#saveProductCat').text('Save');
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
    $(document).on('click', '.delete_main_cat', function(){
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $.ajax({
            type: 'GET',
            url: '/delete_main_cat/'+thisRef.attr('id'),
            data: {
                _token: '{!! csrf_token() !!}'
            },
            success: function(response) {
                if(JSON.parse(response) == 'success'){
                    fetchMainCats();
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

    $(document).on('click', '.delete_sub_cat', function(){
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        thisRef.text('Processing...');
        $.ajax({
            type: 'GET',
            url: '/delete_sub_cat/'+thisRef.attr('id'),
            data: {
                _token: '{!! csrf_token() !!}'
            },
            success: function(response) {
                if(JSON.parse(response) == 'success'){
                    fetchSubCats();
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

function fetchMainCats(){
    $.ajax({
        type: 'GET',
        url: '/GetMainCategories',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Category Name</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['name'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateMainCat">Edit</button><a id="' + element['id'] + '" class="btn btn-default red-bg delete_main_cat">Delete</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}

function fetchSubCats(){
    $.ajax({
        type: 'GET',
        url: '/GetSubCategories',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Main Category Name</th><th>Sub Category Name</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['main_cat_name'] + '</td><td>' + element['name'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateSubCat">Edit</button><a id="' + element['id'] + '" class="btn btn-default red-bg delete_sub_cat">Delete</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}

function fetchProductCats(){
    $.ajax({
        type: 'GET',
        url: '/GetProductCategories',
        data: {
            _token: '{!! csrf_token() !!}'
        },
        success: function(response) {
           //console.log(response);
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="companiesListTable" style="width:100%;"><thead><tr><th>ID</th><th>Sub Category Name</th><th>Product Category Name</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#companiesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                // <td>' + (element['home_phone'] != null ?  element['home_phone']  : element['business_phone'] ) + '</td>
                $('#companiesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['sub_cat_name'] + '</td><td>' + element['name'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdatePOC">Edit</button><a id="' + element['id'] + '" class="btn btn-default red-bg delete_main_cat">Delete</a></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#companiesListTable').DataTable();
        }
    });
}
