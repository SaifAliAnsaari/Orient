$(document).ready(function() {

    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    fetchEmployeesList();
    var lastOp = "add";

    $(document).on('click', '.openDataSidebarForAddingEmployee', function() {
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

            $('input[name="salary"]').val("");
            $('input[name="salary"]').blur();

            $('select[name="country"]').val(0).trigger('change');
            $('select[name="designation"]').val(0).trigger('change');
            $('select[name="reporting"]').val(0).trigger('change');
            $('select[name="department"]').val(0).trigger('change');
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

    $(document).on('click', '.openDataSidebarForUpdateEmployee', function() {
        $('#operation').val('update');
        lastOp = 'update';
        $('#dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        $('#dropifyImgDiv').empty();
        $('#dropifyImgDiv').append('<input type="file" name="employeePicture" id="employeePicture" />');

        var id = $(this).attr('id');
        $('input[name="employee_updating_id"]').val(id)
        $.ajax({
            type: 'GET',
            url: '/Employee/' + id,
            success: function(response) {
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

                $('input[name="city"]').focus();
                $('input[name="city"]').val(response.employee.city);
                $('input[name="city"]').blur();

                $('input[name="state"]').focus();
                $('input[name="state"]').val(response.employee.state);
                $('input[name="state"]').blur();

                $('input[name="address"]').focus();
                $('input[name="address"]').val(response.employee.address);
                $('input[name="address"]').blur();

                $('input[name="username"]').focus();
                $('input[name="username"]').val(response.employee.username);
                $('input[name="username"]').blur();

                $('input[name="hiring"]').val(response.employee.hiring);

                $('input[name="salary"]').focus();
                $('input[name="salary"]').val(response.employee.salary);
                $('input[name="salary"]').blur();

                $('select[name="country"]').val(response.employee.country).trigger('change');
                $('select[name="designation"]').val(response.employee.designation).trigger('change');
                $('select[name="reporting"]').val(response.employee.reporting_to).trigger('change');
                $('select[name="department"]').val(response.employee.department_id).trigger('change');

                var imgUrl = response.base_url + response.employee.picture;
                $("#employeePicture").attr("data-height", '100px');
                $("#employeePicture").attr("data-default-file", imgUrl);
                $('#employeePicture').dropify();
            }
        });

        $('#product-cl-sec').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        $('body').toggleClass('no-scroll');
    });

    $(document).on('click', '#saveEmployee', function() {
        debugger;
        if ($('#operation').val() == "add") {
            if (!$('input[name="password"]').val()) {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Please provide all the required information (*)');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }
        }

        if (!$('input[name="name"]').val() || !$('input[name="city"]').val() || !$('input[name="username"]').val() || $('select[name="country"]').val() == 0 || $('select[name="designation"]').val() == 0 || $('select[name="department"]').val() == 0) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please provide all the required information (*)');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

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
                if (response) {
                    fetchEmployeesList();
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');

                    $('#notifDiv').text('Employee have been updated successfully');
                    if ($('#operation').val() !== "update") {
                        $('#saveEmployeeForm').find("input[type=text], textarea").val("");
                        $('#saveEmployeeForm').find("select").val("0").trigger('change');
                        $('.dropify-clear').click();
                        $('#notifDiv').text('Employee have been added successfully');
                    }

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', '#0038ba');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Employee at the moment');
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

});

function fetchEmployeesList() {
    $.ajax({
        type: 'GET',
        url: '/EmployeesList',
        success: function(response) {
            $('.body').empty();
            $('.body').append('<table class="table table-hover dt-responsive nowrap" id="employeesListTable" style="width:100%"><thead><tr><th>Emp ID</th><th>Employee Name</th><th>Phone</th><th>Designation</th><th>Department</th><th>Action</th></tr></thead><tbody></tbody></table>');
            $('#employeesListTable tbody').empty();
            var response = JSON.parse(response);
            response.forEach(element => {
                $('#employeesListTable tbody').append('<tr><td>' + element['id'] + '</td><td>' + element['name'] + '</td><td>' + element['phone'] + '</td><td>' + element['designation'] + '</td><td>' + element['department'] + '</td><td><button id="' + element['id'] + '" class="btn btn-default btn-line openDataSidebarForUpdateEmployee">Edit</button><button id="' + element['id'] + '" class="btn btn-default">Active</button><button id="' + element['id'] + '" class="btn btn-default" title="View Detail">View Detail</button></td></tr>');
            });
            $('#tblLoader').hide();
            $('.body').fadeIn();
            $('#employeesListTable').DataTable();
        }
    });
}