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
    <h1>kreiraj</h1>
    <div class="">
        <form action="{{route('create.post.with.file.manager.store')}}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="form-group">
                <label for="">naslov</label>
                <input type="text" class="form-control" name="naslov">
            </div>
            {{--<iframe src="/laravel-filemanager" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>--}}

            <div class="form-group">
                <label for="">sadržaj</label>
                <textarea name="sadrzaj" class="my-editor form-control" id="desc" cols="30" rows="15"></textarea>
                <a href="javascript:;" onmousedown="tinyMCE.execCommand('mceInsertContent',false,antrfile);">[Ubaci Antrfile]</a>

            </div>
            <div class="form-group">
                <label for="">sadržaj 2</label>
                <textarea name="sadrzaj2" class="my-editor form-control" id="desc2" cols="30" rows="15"></textarea>
                <a href="javascript:;" onmousedown="tinyMCE.execCommand('mceInsertContent',false,antrfile);">[Ubaci Antrfile]</a>

            </div>
            <div class="input-group">
            <span class="input-group-btn">
              <a id="dodaj_sliku" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
              </a>
            </span>
                <input id="thumbnail" class="form-control" type="text" name="filepath" value="">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;" src="">
            <button class="btn btn-primary">usnimi formu</button>
        </form>

    </div>


@endsection
@section('scripts')
    <script src="https://cdn.tiny.cloud/1/fsquuamzo6sqovo99qdfi4bfr3ekvqv2opm5k37b1aips93q/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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
        tinymce.init({
            selector: '#desc', // Replace this CSS selector to match the placeholder element for TinyMCE
            //plugins: 'code table lists image advlist link',
            //toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
            //iz filemanager
            path_absolute : "/",
            relative_urls: false,
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',

            toolbar: ' insertfile image media template link anchor codesample | fontsizeselect blockquote undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | ltr rtl | restoredraft',
            toolbar_sticky: true,
            file_picker_callback : function(callback, value, meta) {
                let div=document.getElementsByClassName('tox-control-wrap')[0];
                let target_a=div.getElementsByTagName('input')[0];
                let id=target_a.id;
                axios.get(settings.link_to_get_folders).then((data)=>{
                    new BoleFileManager(data, settings, id);
                });


            }
        });
        tinymce.init({
            selector: '#desc2', // Replace this CSS selector to match the placeholder element for TinyMCE
            //plugins: 'code table lists image advlist link',
            //toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
            //iz filemanager
            path_absolute : "/",
            relative_urls: false,
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',

            toolbar: ' insertfile image media template link anchor codesample | fontsizeselect blockquote undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | ltr rtl | restoredraft',
            toolbar_sticky: true,
            file_picker_callback : function(callback, value, meta) {

                let div=document.getElementsByClassName('tox-control-wrap')[0];
                let target_d=div.getElementsByTagName('input')[0];
                let id=target_d.id;
                axios.get(settings.link_to_get_folders).then((data)=>{
                    new BoleFileManager(data, settings, id);
                });

            }
        });
        document.getElementById('dodaj_sliku').addEventListener('click', (e)=>{
            let target=document.getElementById('thumbnail');
            let id=target.id;
            axios.get(settings.link_to_get_folders).then((data)=>{
                //c(data)
                new BoleFileManager(data, settings, id);
            });

        })


    </script>

@endsection


