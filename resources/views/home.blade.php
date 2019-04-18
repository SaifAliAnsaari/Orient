<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orient Engineering Services</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="css/select2-bootstrap4.css">

    <link href="css/dropify.min.css" rel="stylesheet" type="text/css" />

    <link href="css/style.css?v=r.041119" rel="stylesheet">

</head>

<body id="page-top">


    <nav class="navbar navbar-expand  static-top">
        <a class="hamburger" href="#" id="sidebarToggle"></a>
        <a class="_logo" href="/"><img src="/images/orichem.jpeg" alt="" /></a>
        <ul class="navbar-nav ml-auto top_nav">
            <li class="nav-item TM_icon">
                <a class="nav-link" href="#"><img src="/images/q-link-icon.svg" alt="" /></a>
            </li>



            @csrf
            <li class="nav-item TM_icon dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="NotiFications" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> <span class="badge">
                        @if($notifications_counts != "" && !empty($approval_notif))
                        {{ $notifications_counts->counts + sizeof($approval_notif) }}
                        @endif
                        @if(!empty($approval_notif) && empty($notifications_counts))
                        {{ sizeof($approval_notif) }}
                        @endif

                    </span> <img src="{{ URL::to('/images/bell-icon.svg') }}" alt="" /></a>
                <div class="dropdown-menu dropdown-menu-right notiF" aria-labelledby="NotiFications">
                    <h4 class="notiF-title">Notification </h4>
                    @if(!empty($notif_data))
                    @foreach($notif_data as $notifications)
                    <a
                        href="{{ $notifications->cvr_id ? '/cvr_preview'.'/'.$notifications->cvr_id : '/CustomerProfile'.'/'.$notifications->customer_id }}"><img
                            src="{{'/images/profile-img--.jpg'}} " class="NU-img" alt=""><strong
                            class="notifications_list" id="{{$notifications->id}}">{{$notifications->message}} </strong>
                        <p>
                            <?php 
                                                $datetime1 = new DateTime(date('Y-m-d H:i:s'));//start time
                                                $datetime2 = new DateTime($notifications->created_at);//end time
                                                $interval = $datetime1->diff($datetime2);
                                                echo $interval->format('%d days %H hours %i minutes %s seconds ago');
                                            ?>
                        </p>
                    </a>
                    @endforeach
                    @endif
                    @if(!empty($approval_notif))
                    @foreach($approval_notif as $notifications)
                    <a href='/cvr_preview/{{$notifications->cvr_id}}'><img src="{{'/images/profile-img--.jpg'}} "
                            class="NU-img" alt=""><strong class="notifications_list"
                            id="{{$notifications->id}}">{{'CVR ('.$notifications->cvr_id.') has been '. ($notifications->approval == 1 ? "approved" : "disapproved") }}
                        </strong>
                        <p>
                            <?php 
                                            $datetime1 = new DateTime(date('Y-m-d H:i:s'));//start time
                                            $datetime2 = new DateTime($notifications->created_at);//end time
                                            $interval = $datetime1->diff($datetime2);
                                            echo $interval->format('%d days %H hours %i minutes %s seconds ago');
                                            ?>
                        </p>
                    </a>
                    @endforeach
                    @endif
                    <a href="/notifications" class="all-NF">View All ( {{ sizeof($all_notif) + sizeof($unread_notif) }}
                        )</a>
                </div>
            </li>


            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="{{ Auth::user()->picture ? URL::to(Auth::user()->picture) : '/images/avatar.svg' }}"
                        class="user_log" alt="" />
                    <span class="uname">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <span class="dropdown-item usernamelab">{{ Auth::user()->name }}</span>
                    <a class="dropdown-item" href="/edit_profile/{{ Auth::user()->id }}"><i class="fa fa-user"> </i>
                        Profile</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-cogs"> </i> Settings</a>
                    <a class="dropdown-item" href="/logout"><i class="fa fa-power-off"> </i> Logout</a>
                </div>
            </li>
        </ul>
    </nav>

    <div id="wrapper">



        <div id="content-wrapper">

            <div class="container">

                <div class="row PT-20">
                    <div class="col-md-4">
                        <a href="/new_cvr" class="box-sec">
                            <span class="img-svg"><img src="/images/add-report-icon.svg" alt=""></span>
                            <strong>Add</strong> CVR
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="/Customer_list" class="box-sec">
                            <span class="img-svg"><img src="/images/add-customer.svg" alt=""></span>
                            <strong>Add</strong> Customer
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="/poc_list" class="box-sec">
                            <span class="img-svg"><img src="/images/add-poc.svg" alt=""></span>
                            <strong>Add</strong> POC
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="/generate_complaints" class="box-sec">
                            <span class="img-svg"><img src="/images/complain-icon.svg" alt=""></span>
                            <strong>Complaints</strong>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="/edit_profile/{{Auth::user()->id}}" class="box-sec">
                            <span class="img-svg"><img src="/images/cont-setting.svg" alt=""></span>
                            <strong>Setting</strong>
                        </a>
                    </div>




                </div>


                <footer class="sticky-footer">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            © 2019 Orient Engineering Services All rights reserved.
                        </div>
                    </div>
                </footer>

            </div>


        </div>

    </div>



    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/datatables.min.js"></script>
    <script src="js/select2.min.js"></script>

    <script src="js/dropify.min.js"></script>
    <script src="js/form-file-upload-data.js"></script>

    <script src="js/custom.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
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

        });

        $('.form-control').on('focus blur', function (e) {
                $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length >
                    0));
            })
            .trigger('blur');


        $(".formselect").select2();


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

    </script>





</body>

</html>
