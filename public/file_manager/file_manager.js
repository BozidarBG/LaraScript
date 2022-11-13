class BoleFileManager{
    constructor(data, settings, target_for_image_path) {
        this.target_for_image_path=target_for_image_path;
        this.data=data;
        this.settings=settings;
        this.current_folder={};
        this.info_msg_container=document.getElementById('info_msg_container');
        this.modal_content=document.getElementById('modal_content');
        this.createFolderTreeview();
        this.openModal();
        this.giveOpenCloseFolderListeners();
        this.giveListenerToCreateFolderButton();
        this.giveListenerToCreateImageButton();
        this.giveListenerToCreateVideoButton();
    }
    c = console.log;


    createFolderTreeview(){
        let html="";
        if(this.data.data.hasOwnProperty('result')){
            this.data.data.result.forEach((value, key)=>{
                html +=this.createSingleFolderHtmlInTreeview(value);
            });
            document.getElementById('modal_folders').innerHTML=html;
            //moraju dve petlje da se rade za svaki nivo. prva da se pravi parent, druga da se prave deca
            this.data.data.result.forEach((data)=>{
                this.createTreeviewRecursion(data);
            });
        }else{
            //nema ništa. vrv neki error
            c('greška, nema result', this.data)
        }
    }

    createTreeviewRecursion(data){
        if(data.hasOwnProperty('folders') && data.folders.length){
            data.folders.forEach(data=>{
                let div_target=document.getElementById('inner_folder_'+data.parent);
                div_target.innerHTML+=this.createSingleFolderHtmlInTreeview(data);
            });

            data.folders.forEach((data)=>{
                this.createTreeviewRecursion(data)
            });
        }
    }

    createSingleFolderHtmlInTreeview=(data)=>{
        let starting_display=data.parent==0 ? 'd-flex' : 'd-none';
        return '<div class="modal_folder '+starting_display+' " ' +
                'data-status="folder_closed" ' +
                'data-path="'+data.path+'" ' +
                'data-parent="'+data.parent+'" ' +
                'data-folder_id="'+data.id+'">'+
                    '<div class="d-flex align-items-center modal_single_folder">'+
                        '<i class="fas fa-folder"></i>'+
                        '<span class="folder_name">'+data.name+'</span>'+
                    '</div>'+
                    '<div class="ml-2" id="inner_folder_'+data.id+'">' +
                    '</div>'+
                '</div>';
    }

    showCreateFolderForm(action, folder_path){
        return `
           <div class="col-md-4 col-xs-12 col-sm-12 mt-4" >
               <form action="${action}"  method="POST" id="add_folder_to_folder">
                   <div class="form-group">
                       <label for="file1">Kreiraj folder u odabranom folderu</label>
                       <input type="hidden" name="folder_path" value="${folder_path}">
                       <input type="text" class="form-control" id="name" name="name">
                   </div>

                   <input type="submit" value="Sačuvaj" class="btn btn-outline-primary">
               </form>
           </div>
   `;
    }

    showMessage=(msg, css_class=null)=>{
        return '<span class="'+css_class+'" style="background: #ffffff; padding:10px; ">'+msg+'</span>';
    }

    giveHoverListener=()=>{

    }

    showToasterMessage(msg, classes="bg-success"){
        let div=document.getElementsByClassName('toaster_msg')[0];
        div.textContent=msg;
        div.classList.remove('d-none');
        div.classList.add(classes);
        setTimeout(()=>{
            div.textContent="";
            div.classList.add('d-none');
            div.classList.remove(classes);
        }, 3500);

    }

    callbackAfterImageIsAdded=(data)=>{
        if(data[0]==='success'){
            this.info_msg_container.innerHTML=this.showMessage('Slika uspešno uploadovana', 'text-success');
            this.getAllImagesFromFolder(this.current_folder.path)
        }else{
            for(let i=0; i<data.errors.length; i++){
                this.info_msg_container.innerHTML +=this.showMessage(data.errors[i], 'text-danger');
            }
        }
        setTimeout(function(){
            this.info_msg_container.innerHTML="";
        }, 5000);
    }

    giveListenerToCreateVideoButton=()=>{
        document.getElementById('add_video_to_folder_btn').addEventListener('click', (e)=>{
            e.preventDefault();
            if(Object.keys(this.current_folder).length===0){
                this.modal_content.innerHTML="<div class='bg-danger'>Potrebno je selektovati folder</div>"
            }else{
                this.modal_content.innerHTML=this.showUploadVideoForm(this.settings.link_to_store_videos, this.current_folder.path)
            }
        });
    }

    showUploadVideoForm=(action, folder_path)=>{
        return `
           <div class="col-md-4 col-xs-12 col-sm-12 mt-4" >
               <form action="${action}"  method="POST" id="add_video_to_folder" enctype="multipart/form-data">
                   <div class="form-group">
                       <label for="file1">Dodaj video u odabrani folder</label>
                       <input type="hidden" name="folder_path" value="${folder_path}">
                       <input type="file" class="form-control" id="video" name="video">
                   </div>

                   <input type="submit" value="Sačuvaj" class="btn btn-outline-primary">
               </form>
           </div>
           `;
    }


    updateDirTree=(data)=>{
        if(data.data.hasOwnProperty('success')){
            let folder_name=data.data.success.name;//ok
            let container_div=document.querySelector('div[data-folder_id="'+this.current_folder.folder_id+'"]');
            //treba da u parenta, tj u selektovani, da ubacimo novi folder tj div
            let new_folder={};
            new_folder.name=folder_name;
            new_folder.path=this.current_folder.path+'/'+folder_name;
            new_folder.parent=container_div.getAttribute('data-folder_id');
            new_folder.id=Math.random();
            new_folder.type="folder";
            new_folder.folders=[];
            let new_folder_html=this.createSingleFolderHtmlInTreeview(new_folder)
            let replaced_html=new_folder_html.replace('d-none', 'd-flex');
            document.getElementById('inner_folder_'+new_folder.parent).innerHTML += replaced_html;
            this.giveOpenCloseFolderListeners();
            document.getElementById('modal_content').innerHTML="";
            this.showToasterMessage('Folder je kreiran uspešno', 'bg-success')
        }else{
            c(data.data)
            //neka greška
            this.showToasterMessage(data.data.errors, 'bg-danger')
        }
    }


    giveListenerToCreateFolderButton=()=>{
        document.getElementById('add_folder_to_folder_btn').addEventListener('click', (e)=>{
            e.preventDefault();
            if(Object.keys(this.current_folder).length===0){
                c('nije selektovan folder')
                this.modal_content.innerHTML="<div class='bg-danger'>Potrebno je selektovati folder</div>"
            }else{
                c('selektovan folder')
                this.modal_content.innerHTML=this.showCreateFolderForm(this.settings.link_to_store_folder, this.current_folder.path);
                this.addSubmitFormByPostListener('add_folder_to_folder', 'updateDirTree');
            }
        }, true);
    }

    giveListenerToCreateImageButton=()=>{
        document.getElementById('add_image_to_folder_btn').addEventListener('click', (e)=>{
            e.preventDefault();
            if(Object.keys(this.current_folder).length===0){
                this.modal_content.innerHTML="<div class='bg-danger'>Potrebno je selektovati folder</div>"
            }else{
                this.modal_content.innerHTML=this.showInputFileFormCroppie()
                this.initCroppie(this.settings, this.current_folder.path)
            }
        });
    }
    initCroppie=(settings, folder_path)=>{
        var croppie = null;
        var el = document.getElementById('resizer');
        var croppie_settings={
            viewport: {
                width: 800,
                height: 400,
                type: 'square'
            },
            boundary: {
                width: 850,
                height: 450
            },
            enableResize:true,
            enableOrientation: true
        };
        const showToasterMessage=this.showToasterMessage;
        const getAllImagesFromFolder=this.getAllImagesFromFolder;
        const current_folder=this.current_folder;

        document.getElementById('img_width').addEventListener('change keyup', (e)=>{
            let new_val=e.target.value;
            croppie_settings.viewport.width=new_val;
            croppie_settings.boundary.width=new_val*1.2;
        })

        document.getElementById('img_height').addEventListener('change keyup', (e)=>{
            let new_val=e.target.value;
            croppie_settings.viewport.height=new_val;
            croppie_settings.boundary.height=new_val*1.2;
        });


        const base64ImageToBlob=(str)=>{
            // extract content type and base64 payload from original string
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);
            // decode base64
            var imageContent = atob(b64);
            // create an ArrayBuffer and a view (as unsigned 8-bit)
            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);
            // fill the view, using the decoded base64
            for (var n = 0; n < imageContent.length; n++) {
                view[n] = imageContent.charCodeAt(n);
            }
            // convert ArrayBuffer to Blob
            var blob = new Blob([buffer], { type: type });
            return blob;
        }
        const getImage = (input, croppie)=>{
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    croppie.bind({
                        url: e.target.result,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('file-upload').addEventListener('change', (event)=>{
            if(croppie){
                croppie.destroy();
            }
            croppie = new Croppie(el, croppie_settings);
            getImage(event.target, croppie);
        });

        document.getElementById('upload').addEventListener('click', (e)=>{
            croppie.result('base64').then(function(base64) {

                var url = settings.link_to_store_images;
                var formData = new FormData();
                var image_name=document.getElementById("img_name");
                if(image_name){
                    formData.append('name', image_name.value);
                }
                formData.append('folder_path', folder_path);
                formData.append("image", base64ImageToBlob(base64));
                axios.post(url, formData).then((data)=>{
                    if (data.data.hasOwnProperty("success")) {
                        showToasterMessage('Slika uspešno postavljena', 'bg-success')
                        getAllImagesFromFolder(current_folder.path)

                    } else {
                        showToasterMessage('Greška prilikom uploadovanja', 'bg-danger')

                    }
                });
            });
        });

        let rotate_btns=document.getElementsByClassName('rotate');
        for(let i=0;i<rotate_btns.length; i++){
            rotate_btns[i].addEventListener('click', (e)=>{
                croppie.rotate(parseInt(e.currentTarget.getAttribute('data-deg')));
            });
        }
    }

    showInputFileFormCroppie=()=>{
        return `
       <div class="row w-100">
           <div class="col-2 border-right">
               <div class="text-center">
                   <div class="btn btn-dark mt-4">
                       <input type="file" class="file-upload" id="file-upload"
                              name="image" accept="image/*">
                       Upload New Photo
                   </div>
               </div>
               <div class="my-4">
                   <label for="">Širina u px</label>
                   <input type="number" class="form-control" id="img_width" min="1">
               </div>
               <div>
                   <label for="">Visina u px</label>
                   <input type="number" class="form-control" id="img_height" min="1">
               </div>
           </div>
           <div class="col-10">
               <div class="mt-4">
                   <div id="resizer"></div>
                   <div>
                       <input type="text" id="img_name" class="form-control" placeholder="Ime slike (opciono)">
                   </div>
                   <button class="btn rotate float-lef" data-deg="90" >
                       <i class="fas fa-undo"></i></button>
                   <button class="btn rotate float-right" data-deg="-90" >
                       <i class="fas fa-redo"></i></button>
                   <hr>
                   <button class="btn btn-block btn-dark" id="upload" >
                       Crop And Upload</button>
               </div>
           </div>
       </div>
               `;
    }

    addSubmitFormByPostListener(form_id, cb){
        const callback=this[cb].bind(this[cb]);
        const form=document.getElementById(form_id);
        const action=form.getAttribute('action');
        form.addEventListener('submit', (e)=>{
            e.preventDefault();
            let formData=new FormData(e.currentTarget);
            axios.post(action, formData).then((data)=>{
                callback(data)
            });
        })
    }

    openModal(){
        let modal = document.getElementById("myModal");
        let btn=document.querySelector('button[title="Source"]');
        let span = document.getElementsByClassName("close_modal")[0];
        modal.style.display = "block";
        // btn.onclick = function() {
        //     modal.style.display = "block";
        // }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    toggleFolder(target){
        //c("target je",target)
        let folder_id=target.getAttribute('data-folder_id');
        //c('folder id je ', folder_id)
        let target_i=target.getElementsByTagName('i')[0];
        //c('target_i je ', target_i)
        //this.removeSelectedClass();
        if(target.getAttribute('data-status')==="folder_opened"){
            target.setAttribute('data-status', 'folder_closed');
            target_i.classList.add('fa-folder');
            target_i.classList.remove('fa-folder-open');

        }else{
            //prikauzjemo sve slike iz tog foldera ako ima
            let path=target.getAttribute('data-path');
            this.getAllImagesFromFolder(path);
            this.current_folder.path=path;
            this.current_folder.parent_id=target.getAttribute('data-parent')
            this.current_folder.folder_id=target.getAttribute('data-folder_id');
            document.getElementById('folder_path').textContent=path;
            target.setAttribute('data-status', 'folder_opened');
            target_i.classList.add('fa-folder-open');
            target_i.classList.remove('fa-folder');
        }
        let child_folders=document.querySelectorAll(`[data-parent="${folder_id}"]`);
        //c(child_folders)
        for(let i=0; i<child_folders.length; i++){
            if(child_folders[i].classList.contains('d-none')){
                child_folders[i].classList.remove('d-none');
                child_folders[i].classList.add('d-flex');
            }else{
                child_folders[i].classList.add('d-none');
                child_folders[i].classList.remove('d-flex');
            }
        }
        //c(this.current_folder.path)
    }

    //kad se klikne na folder koji je zatvoren, folder treba da se otvori i da se prikažu sve slike iz tog foldera
    //ova funk ajaxom dobija sve slike
    getAllImagesFromFolder(folder_path){
        let formData=new FormData;
        formData.append('path', folder_path);
        //const showAll=this.showAllImagesFromFolder;
        if(folder_path){
            axios.post(this.settings.link_to_get_images, {path:folder_path}).then((data)=>{
                c(data)
                if(data.data.hasOwnProperty('success')){
                    this.showAllImagesFromSelectedFolder(data.data.success)
                    this.giveChoseImageListeners();
                    this.giveEditImageListener();
                }else{
                    //prazan folder
                }
            });

        }else{
            //neki error sa path u hmtl-u
        }
    }

    giveChoseImageListeners(){
        let choose_btns=document.getElementsByClassName('choose_img');
        for(let i=0; i<choose_btns.length; i++){
            choose_btns[i].addEventListener('click', (e)=>{
                e.preventDefault();
                let path=e.currentTarget.getAttribute('data-path');
                //gasimo modal i stavljamo path u tynimce modal

                let target=document.getElementById(this.target_for_image_path);
                target.value=path;
                let modal=document.getElementById('myModal');
                modal.style.display = "none";
                this.modal_content.innerHTML="";
            });
        }
    }

    giveEditImageListener() {
        let btns = document.getElementsByClassName('edit_image');
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', (e) => {
                e.preventDefault();
                let path = e.target.getAttribute('data-path');

                this.modal_content.innerHTML=`
               <div class="outer_container">
                    <div class="image_container">
                       <div class="image_workspace">
                           <img class="image_source" id="selected_image" src="${path}" />
                           <span>Image</span>
                       </div>
                    </div>
                    <div class="preview_container">
                       <div class="preview_cover">
                           <div class="img_preview"><span>Preview</span></div>
                       </div>
                    </div>
                    <div class="side_controls_shifter">
                       <i class="fa-solid fa-caret-left active"></i>
                       <i class="fa-solid fa-caret-right"></i>
                   </div>
                   <div class="side_control_page_1">
                       <div class="zoom">
                           <span>Zoom In - Out</span>
                           <li><i class="fa-solid fa-magnifying-glass-plus"></i></li>
                           <li><i class="fa-solid fa-magnifying-glass-minus"></i></li>
                       </div>
                       <div class="rotate">
                           <span>Rotate image</span>
                           <li><i class="fa-solid fa-arrow-rotate-right"></i></li>
                           <li><i class="fa-solid fa-arrow-rotate-left"></i></li>
                       </div>
                       <div class="flip">
                           <span>Flip Image</span>
                           <li><i class="fa-solid fa-arrow-right-arrow-left"></i></li>
                           <li><i class="fa-solid fa-arrow-down-up-across-line"></i></li>
                       </div>
                       <div class="move" style="border-bottom: 0px;">
                           <span style="margin-bottom: 20px;">Move Image</span>
                           <li><i class="fa-solid fa-arrow-up-long" style="left: 44px;"></i></li>
                           <li><i class="fa-solid fa-arrow-right-long"></i></li>
                           <li><i class="fa-solid fa-arrow-down-long"></i></li>
                           <li><i class="fa-solid fa-arrow-left-long" style="left: 44px;"></i></li>
                       </div>
                       </div>
                       <div class="side_control_page_2" style="display: none;">
                           <div class="aspect">
                               <span>Aspect Ratio</span>
                               <li>16:9</li>
                               <li>4:3</li>
                               <li>1:1</li>
                               <li>2:3</li>
                               <li>Free</li>
                           </div>
                       </div>

                       <div class="action_button">
                           <button class="upload">Upload</button>
                           <input type="file" class="hidden_upload" style="display: none;" accept="image/*">
                           <button class="download">Download</button>
                       </div>

                       <div class="bottom_control">
                           <div class="ctrl_cropper">
                               <span>Control Cropper</span>
                               <li><i class="fa-solid fa-bars"></i></li>
                               <li><i class="fa-solid fa-crop-simple"></i></li>
                           </div>
                           <div class="lock">
                               <span>Lock Cropper</span>
                               <li><i class="fa-solid fa-lock"></i></li>
                               <li><i class="fa-solid fa-lock-open"></i></li>
                           </div>
                           <div class="drag_mode">
                               <span>Drag Mode</span>
                               <li><i class="fa-solid fa-minimize"></i></li>
                               <li><i class="fa-solid fa-maximize"></i></li>
                           </div>
                       </div>
                   </div>
               </div>
               `;
                this.initCroppie();

            });
        }
    }

    showAllImagesFromSelectedFolder(arr){
        //c('slike',arr)
        if(arr.length){
            let html="";
            for(let i=0; i<arr.length; i++){
                html +='<div class="modal_image_container mb-5">' +
                    '<div class="modal_image_body">' +
                    '<img src="'+arr[i].path+'" height="100%">' +
                    '</div>' +
                    '<div class="modal_image_info">Š: '+arr[i].width+'px, V: '+arr[i].height+'px</div>'+
                    '<div class="modal_image_footer d-flex justify-content-between align-items-center px-2">'+
                    '<button class="btn btn-sm btn-warning edit_image" data-path="'+arr[i].path+'">Edit</button>' +
                    '<button class="btn btn-sm btn-primary choose_img" data-path="'+arr[i].path+'">Odaberi</button>'+
                    '</div>'+
                    '</div>';
            }
            document.getElementById('modal_content').innerHTML=html;
            //this.giveHoverListener();//nije završeno
        }else{
            document.getElementById('modal_content').innerHTML="<p class='text-danger'>Nema slika u ovom folderu</p>";
        }
    }

    giveOpenCloseFolderListeners() {
        let folders=document.getElementsByClassName('modal_folder');
        for(let i=0; i<folders.length; i++){
            folders[i].addEventListener('click', (e)=>{
                e.stopPropagation();
                this.toggleFolder(e.currentTarget)
            });
        }
    }

    //za cropper ne treba
    showInputFileForm=(action, folder_path)=>{
        return `
           <div class="col-12" >
               <div class="outer_container">
                   <div class="image_container">
                       <div class="image_workspace">
                           <img class="image_source" id="selected_image" src="" />
                           <span>Image</span>
                       </div>
                    </div>
                   <div class="preview_container">
                       <div class="preview_cover">
                           <div class="img_preview"><span>Preview</span></div>
                       </div>

                   </div>
                   <div class="">
                           <input type="text" class="form-control" id="file_name" placeholder="Novo ime slike">
                       </div>
                   <div class="side_controls_shifter">
                       <i class="fa-solid fa-caret-left active"></i>
                       <i class="fa-solid fa-caret-right"></i>
                   </div>
                   <div class="side_control_page_1">
                       <div class="zoom">
                           <span>Zoom In - Out</span>
                           <li><i class="fa-solid fa-magnifying-glass-plus"></i></li>
                           <li><i class="fa-solid fa-magnifying-glass-minus"></i></li>
                       </div>
                       <div class="rotate">
                           <span>Rotate image</span>
                           <li><i class="fa-solid fa-arrow-rotate-right"></i></li>
                           <li><i class="fa-solid fa-arrow-rotate-left"></i></li>
                       </div>
                       <div class="flip">
                           <span>Flip Image</span>
                           <li><i class="fa-solid fa-arrow-right-arrow-left"></i></li>
                           <li><i class="fa-solid fa-arrow-down-up-across-line"></i></li>
                       </div>
                       <div class="move" style="border-bottom: 0px;">
                           <span style="margin-bottom: 20px;">Move Image</span>
                           <li><i class="fa-solid fa-arrow-up-long" style="left: 44px;"></i></li>
                           <li><i class="fa-solid fa-arrow-right-long"></i></li>
                           <li><i class="fa-solid fa-arrow-down-long"></i></li>
                           <li><i class="fa-solid fa-arrow-left-long" style="left: 44px;"></i></li>
                       </div>
                   </div>

                   <div class="side_control_page_2" style="display: none;">
                       <div class="aspect">
                           <span>Aspect Ratio</span>
                           <li>16:9</li>
                           <li>4:3</li>
                           <li>1:1</li>
                           <li>2:3</li>
                           <li>Free</li>
                       </div>
                   </div>

                   <div class="action_button">
                       <button class="upload">Upload</button>
                       <input type="file" class="hidden_upload" style="display: none;" accept="image/*">
                       <button class="download">Download</button>
                   </div>

                   <div class="bottom_control">
                       <div class="ctrl_cropper">
                           <span>Control Cropper</span>
                           <li><i class="fa-solid fa-bars"></i></li>
                           <li><i class="fa-solid fa-crop-simple"></i></li>
                       </div>
                       <div class="lock">
                           <span>Lock Cropper</span>
                           <li><i class="fa-solid fa-lock"></i></li>
                           <li><i class="fa-solid fa-lock-open"></i></li>
                       </div>
                       <div class="drag_mode">
                           <span>Drag Mode</span>
                           <li><i class="fa-solid fa-minimize"></i></li>
                           <li><i class="fa-solid fa-maximize"></i></li>
                       </div>
                   </div>
               </div>
           </div>

   `;

    }


}
