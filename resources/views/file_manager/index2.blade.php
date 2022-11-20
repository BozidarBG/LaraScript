@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    {{--<link  href="{{asset('js/cropperjs/dist/cropper.css')}}" rel="stylesheet">--}}
    <style>

        /*za croppie */
        .nounderline, .violet{
            color: #7c4dff !important;
        }
        .btn-dark {
            background-color: #7c4dff !important;
            border-color: #7c4dff !important;
            cursor:pointer;
        }
        .btn-dark .file-upload {
            width: 100%;
            padding: 10px 0;
            position: absolute;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        /*kraj za croppie*/
    </style>
@endsection
@section('content')
    @include('file_manager.file_manager_modal')
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    {{--    <script src="{{asset('js/cropperjs/dist/cropper.js')}}"></script>--}}
    <script src="{{asset("file_manager/file_manager.js")}}"></script>
    <script>

        let c = console.log;

        const settings={
            link_to_get_images: "{{route('file.manager.get.files')}}",
            link_to_store_images:"{{route('file.manager.store.image')}}",
            link_to_store_folder:"{{route('file.manager.store.folder')}}",
            link_to_get_folders:"{{route('file.manager.get.folders')}}",
            link_to_store_videos:"{{route('file.manager.store.video')}}",
        };

        axios.get(settings.link_to_get_folders).then((data)=>{
            new BoleFileManager(data, settings, null);
        });

        document.getElementById('myModal').classList.remove('modal');
        document.getElementsByClassName('modal-content')[0].style.width="100%";
        document.getElementsByClassName('close_modal')[0].style.display="none";

    </script>

@endsection


