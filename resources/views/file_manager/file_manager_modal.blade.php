{{--<link rel="stylesheet" href="{{asset('css/_bootstrap.min.css')}}">--}}
<style>

    body {font-family: Arial, Helvetica, sans-serif;}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default
     /*display:block;*/
        position: fixed; /* Stay in place */
        z-index: 9999; /* Sit on top */
        padding-top: 50px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        font-size: 14px;
    }
    #modal_content{
        overflow: scroll; /* Enable scroll if needed */
        height:calc(90vh - 55px);
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        height: 90vh;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }

    @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }

    /* The Close Button */
    .close_modal {
        color: black;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close_modal:hover,
    .close_modal:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .my_modal-header {
        padding: 3px 10px;
        background-color: #a9d8ff;
        color: black;
        border:1px solid #a1e891;
    }

    .modal-body {padding: 2px 16px;}
    .modal_folder{
        padding:5px;
        font-size: 15px;
        /*display: flex;*/
        flex-direction: column;
        justify-content: flex-start;
        /*align-items: center;*/
        cursor: pointer;
        /*background: #8bc34a;*/
    }
    .modal_single_folder:hover{
        background-color: #83b7f3;
    }
    .modal_folder i{
        margin-right: 10px;

    }

    .inner{
        margin-left: 30px;
    }

    .tree{
        height:calc(90vh - 55px);
        overflow: scroll;

    }
    .modal_image_container{

        height:170px;
        width: 150px;
        margin:0 15px 15px 0;
    }
    .modal_image_body{
        cursor: e-resize;
        height: 120px;
    }
    .modal_image_footer{
        height: 50px;
        background: #0a0e14;
    }
    .modal_image_body img{
        width:100%;
    }
    .add_image{
        height:4rem;
    }
    .action_buttons{
        cursor: pointer;
        color: #3b29f3;
        font-size: 25px;
    }
    .modal_image_info{
        height: 20px;
        background: #fdf8db;
        color: #000000;
        font-size: 14px;
    }

    /* početak za croppie */
    .nounderline, .violet{
        color: #7c4dff !important;
    }
    .btn-dark {
        background-color: #7c4dff !important;
        border-color: #7c4dff !important;
    }
    .btn-dark .file-upload {
        width: 100%;
        padding: 10px 0px;
        position: absolute;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .profile-img img{
        width: 200px;
        height: 200px;
    }
    /* kraj za kropi */
    .action_buttons a{
        font-size: 20px;
        color:#1a202c;
    }
    .action_buttons a:hover{
        color: #e7081b;
    }
    .action_buttons span{
        font-size: 14px;
        color:#1a202c;

    }
    .toaster_msg{
        position: absolute;
        top:49px;
        right:41px;
        width:400px;
        padding:15px;
        z-index: 100;
        color: whitesmoke;
    }

</style>

<div id="myModal" class="modal ">

    <div class="modal-content">
        <div class="toaster_msg d-none"></div>
        <div class="my_modal-header">
            <div class="row">
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <h5>File Manager</h5>
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-11 d-flex justify-content-start align-items-center action_buttons">
                            <a href="#" class="mr-3" id="add_image_to_folder_btn" data-toggle="tooltip" data-placement="top" title="Dodaj sliku u folder" >
                                <i class="far fa-plus-square"></i>
                            </a>
                            <a href="#" class="mr-3 " id="add_video_to_folder_btn" data-toggle="tooltip" data-placement="top" title="Dodaj video u ovaj folder" >
                                <i class="far fa-file-video"></i>
                            <a href="#" class="mr-3 " id="add_folder_to_folder_btn" data-toggle="tooltip" data-placement="top" title="Kreiraj folder u ovom folderu" >
                                <i class="fas fa-folder-plus"></i>
                            </a>
                            </a>
                            <span>Poslednji selektovani folder je:&nbsp;&nbsp; </span><span id="folder_path" class="text-danger"></span>
                            <span id="info_msg_container" style="margin-left: auto; "></span>

                        </div>
                        <div class="col-1">
                            <span class="close_modal">&times;</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-body" id="modal_body">
            <div class="row">
                <div class="col-4">
                    <div id="modal_folders" class="d-flex flex-column align-items-start tree">

                    </div>
                </div>
                <div class="col-8" >
                    <!-- sadržaj modala odavde -->
                    <div class="d-flex justify-content-start align-items-start flex-wrap " id="modal_content">

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
