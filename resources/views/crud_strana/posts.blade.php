@extends('layouts.app')

@section('title', 'Naslov strane')
@section('styles')
    <style>

    </style>
@endsection

@section('content')

    <div class="row p-3">
        @include('layouts.modal')
        @if(session()->has('success'))
            <div class="col-12 alert alert-success">{{session()->get('success')}}</div>
        @endif

        <div class="col-8">
            <div class="card">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Body</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>

                    </tr>
                    </thead>
                    <tbody id="table_body">
                    @forelse($items as $i)
                    <tr>
                        <th scope="row">{{$i->id}}</th>
                        <td id="{{$i->id}}_title">{{$i->title}}</td>
                        <td>{{$i->user->name}}</td>
                        <td id="{{$i->id}}_body">{{$i->body}}</td>
                        <td><button class="fc_edit btn btn-warning" data-id="{{$i->id}}" data-title="{{$i->title}}" data-body="{{$i->body}}">Edit</button></td>
                        <td><button class="fc_delete_modal btn btn-danger" data-toggle="modal" data-target="#confirmation_modal" data-route="{{route('posts.destroy', ['id'=>$i->id])}}">Delete</button></td>
                    </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4">
            <div class="card p-3" id="create_div">
                <div class="card-title bg-primary">
                    <h4>Create</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('posts.store')}}" id="create_form">
                        @csrf
                        <div id="fc_errors_div"></div>
                        <div class="form-group">
                            <label >Title</label>
                            <input type="text" class="fc form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label >Body</label>
                            <textarea name="body" class="fc form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>


            <div class="card p-3 d-none" id="edit_div">
                <div class="card-header">
                    <h4 class="card-title"> Edit</h4>
                </div>
                <div class="card-body">
                    <div id="backend_category_errors_edit"></div>
                    <form method="post" id="edit_form" action="">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="title" class="form-control fc" id="title_update" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="">
                                    <label for="color">Color</label>
                                    <input type="text" name="body" class="form-control fc" id="body_update">
                                </div>
                                <div class="form-group d-flex" style="">
                                    <button type="submit" class="btn btn-primary" id="update_btn">Update</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection
@section('scripts')
    <script>
        const rules={
            title:{required:true, minLength:2, maxLength:50},
            body:{required:true, minLength:5, maxLength:10000}
        }
        let confirmation_modal=document.getElementById('confirmation_modal');

        const triggerToaster=(msg, class_list, time_shown=3000)=>{
            document.body.innerHTML +=`
                <div id="fc_toaster" class="position-absolute w-25 d-flex align-items-center p-2 ${class_list}" style="border-radius:5px;z-index: 10;top:20px;right:20px;">
                    <span class="text-white">${msg}</span>
                </div>
            `;

            let toaster=document.getElementById('fc_toaster');
            setTimeout(()=>{toaster.remove()}, time_shown);
        }

        const editRow=(e)=>{
            //show edit form div, hide create form div
            document.getElementById('create_div').classList.add('d-none');
            document.getElementById('edit_div').classList.remove('d-none');

            let ev_target=e.target;
            const updateRow=(data)=>{
                //console.log(data);
                if(data.data.errors){
                    console.log(data.data.errors)
                    //refresh page
                }else{
                    //change data sets in e.target
                    ev_target.setAttribute('data-title', data.data.success.title);
                    ev_target.setAttribute('data-body', data.data.success.body);
                    //change data in table
                    document.getElementById(data.data.success.id+"_title").textContent=data.data.success.title;
                    document.getElementById(data.data.success.id+"_body").textContent=data.data.success.body;
                    addToaster('alert-success', 'Edited successfully!');
                    //show create form div, hide edit form div
                    document.getElementById('create_div').classList.remove('d-none');
                    document.getElementById('edit_div').classList.add('d-none');
                    resetForms();
                }
            }
            //show form and fill with data

            let edit_div=document.getElementById('edit_div');
            edit_div.classList.remove('d-none');
            let edit_form=document.getElementById('edit_form');
            let old_title_input=document.getElementById('title_update');
            let old_body_input=document.getElementById('body_update');
            old_title_input.value=e.target.getAttribute('data-title');
            old_body_input.value=e.target.getAttribute('data-body');
            edit_form.setAttribute('action', "/update-post/"+e.target.getAttribute('data-id'));

            //let update_form=document.getElementById('update_form');
            edit_form.addEventListener('submit', (e)=>{
                e.preventDefault();

                let formCheck=new FormSubmition('edit_form', rules, 'http', updateRow);
                if(formCheck.hasErrors()){
                    formCheck.putErrorsAboveEveryInput(formCheck.errorsObj, ['alert', 'alert-danger'], 'p');
                }else{
                    formCheck.sendPostViaAjax();
                    //edit_div.classList.add('d-none');
                    //edit_form.submit();
                    //location.reload();
                }
            });

        }

        const createRecord=()=>{
            const addNewRowToTable=(d)=>{
                //console.log(d.data.success)
                let data=d.data.success;
                let row=`
             <tr>
                <th scope="row">${data.id}</th>
                <td id="${data.id}_title">${data.title}</td>
                <td>${data.name}</td>
                <td id="${data.id}_body">${data.body}</td>
                <td><button class="fc_edit btn btn-warning" data-id="${data.id}" data-title="${data.title}" data-body="${data.body}">Edit</button></td>
                <td><button class="fc_delete_modal btn btn-danger" data-toggle="modal" data-target="#confirmation_modal" data-route="/delete-post/${data.id}">Delete</button></td>
            </tr>
            `;
                document.getElementById('table_body').innerHTML +=row;
                addToaster('alert-success', 'Row created successfully');
                resetForms();
                addListeners();
            }
            let form_obj=new FormSubmition('create_form', rules, 'ajax', addNewRowToTable);
            if(form_obj.hasErrors()){
                form_obj.putErrorsAboveEveryInput();
            }else{
                //send via form.submti();
               //document.getElementById('create_form').submit();
                //send via ajax
                form_obj.sendPostViaAjax();
            }
        }

        const resetForms =()=>{
            let forms=document.getElementsByTagName('form');
            for(let i=0; i<forms.length; i++){
                forms[i].reset();
            }
        }


        const clearErrorMessages=(id)=>{
            let errorDiv=document.getElementById(id);
            errorDiv.innerHTML="";

        }
        const closeBootstrapModal=(modal_id)=>{
            const modal=document.getElementById(modal_id);
            const modal_backdrop=document.getElementsByClassName('modal-backdrop')[0];
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            modal.setAttribute('style', 'display:none');
            document.body.removeChild(modal_backdrop)
        }

        //delete with modal
        //kad se klikne delete, aktivira se bootstrap modal
        //kad se klikne na id="confirmation_modal_confirm" šalje se delete sa ajax.
        //može kontroler da uradi return redirect()->route('neka ruta); ako je ok ili da vrati return response json(['error
        //ili da vrati return response()->json(['success']) pa da dalje js briše red
        // ili greške (npr ne postoji ili ne može da se briše)

        const getDeleteRowAndRoute=(e)=>{
            let deleteRoute =e.target.hasAttribute('data-route') ? e.target.getAttribute('data-route') : null;
            let rowToBeDeleted=e.target.closest('tr');
            //console.log(deleteRoute, rowToBeDeleted);

            const deleteRow=(e)=>{
                axios.post(deleteRoute, {}).then((data)=>{
                    //console.log(data)
                    if(data.data.hasOwnProperty('success')){
                        //$('#confirmation_modal').modal('hide');//too complicated to use without jQ
                        closeBootstrapModal('confirmation_modal')
                        rowToBeDeleted.remove();
                        triggerToaster('Red obrisan','alert-success')
                    }
                });
            }
            //new Listener('click', 'confirm_modal_button', 'id', deleteRow);
        }

        const addToaster=(className="alert-success", msg)=>{
            const timer=(clasName)=>{
                toaster.classList.remove(clasName);
                toaster.textContent="";
                toaster.classList.add('d-none');
            };
            let toaster=document.getElementById('fc_toaster');
            toaster.textContent=msg;
            toaster.classList.add(className);
            toaster.classList.remove('d-none');
            setTimeout(timer, 2000);
        }


        const addListeners=()=>{
            // new Listener('submit', 'create_form','id', createRecord);
            // new Listener('click', 'fc_edit','class', editRow);
            // new Listener('click', 'fc_delete_modal', 'class', getDeleteRowAndRoute);//delete listener

            document.getElementById('create_form').addEventListener('submit', (e)=>{
                createRecord(e);
            });

            let edits=document.getElementsByClassName('fc_edit');
            for(let i=0; i<edits.length; i++){
                edits[i].addEventListener('click', (e)=>{
                    editRow(e);
                });
            }

            let deletes=document.getElementsByClassName('fc_delete_modal');
            for(let i=0; i<deletes.length; i++){
                deletes[i].addEventListener('click', (e)=>{
                    getDeleteRowAndRoute(e);
                });
            }

        }




        addListeners();

    </script>
@endsection

