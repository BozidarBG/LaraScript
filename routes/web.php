<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;



Route::view('/', 'welcome')->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('user.dashboard');

require __DIR__.'/auth.php';

//ovo je za na jednoj strani crud (kreiranje kategorija i nešto što je malo. polje ili dva u tabeli)
//koristi PostController i bootstrap
Route::middleware(['auth'])->group(function (){
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create-post', [PostController::class, 'create'])->name('posts.create');
    Route::post('/store-post', [PostController::class, 'store'])->name('posts.store');
    Route::post('/delete-post/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/update-post/{id}', [PostController::class, 'update'])->name('posts.update');
});

//video plejer od web dev simplified
Route::middleware(['auth'])->group(function (){
    Route::get('/video-player', [\App\Http\Controllers\VideoController::class, 'index'])->name('video.player.index');
});

//file manager
Route::group(['middleware' => [ 'auth']], function () {

    Route::get('/create-post-with-file-manager', [\App\Http\Controllers\FilesController::class, 'index'])->name('create.post.with.file.manager');//ok
    Route::post('/file-manager-store', [\App\Http\Controllers\FilesController::class, 'store'])->name('create.post.with.file.manager.store');//ok
    Route::post('file-manager-get-files',[\App\Http\Controllers\FilesController::class,'getImages'])->name('file.manager.get.files');//ok
    Route::post('file-manager-store-image',[\App\Http\Controllers\FilesController::class,'storeImage'])->name('file.manager.store.image');//ok
    Route::post('file-manager-store-folder',[\App\Http\Controllers\FilesController::class,'storeFolder'])->name('file.manager.store.folder');//ok
    Route::get('file-manager-get-folders',[\App\Http\Controllers\FilesController::class, 'getFolders'])->name('file.manager.get.folders');
    Route::post('file-manager-store-video',[\App\Http\Controllers\FilesController::class,'storeVideo'])->name('file.manager.store.video');//ok




    Route::get('create-post-with-file-manager2222222222',[\App\Http\Controllers\FilesController::class, 'create'])->name('files.create');
    Route::get('create-post2',[\App\Http\Controllers\FilesController::class, 'create2'])->name('files.create2');

    Route::post('store-post2',[\App\Http\Controllers\FilesController::class, 'store'])->name('files.store');

    Route::get('/boletest/test2', [\App\Http\Controllers\FilesController::class, 'index2'])->name('create2');
    Route::get('get-folders',[\App\Http\Controllers\FilesController::class,'getFolders']);


    Route::get('/cropper-create', [\App\Http\Controllers\FilesController::class, 'createCroppie'])->name('create.croppie');
});

Route::get('/treeview', function (){
    return view('treewiew');
})->name('treeview');
