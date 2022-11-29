@extends('layouts.app')
@section('styles')
{{--    <link rel="stylesheet" href="{{asset('video_player/video_player.css')}}">--}}
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.3/plyr.css" />
@endsection
@section('content')
<div class="row">
    <!-- Modal -->
    <div class="modal fade" id="video_modal" tabindex="-1" aria-labelledby="video_modallLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_body">

                    <video id="player" playsinline controls data-poster="/images/1.jpg">
                        <source src="" type="video/mp4" />
{{--                        <source src="/path/to/video.webm" type="video/webm" />--}}

                        <!-- Captions are optional -->

                    </video>




{{--                    <div class="row bg-success">--}}
{{--                        <div class="video-container paused" data-volume-level="high">--}}
{{--                            <img class="thumbnail-img">--}}
{{--                            <div class="video-controls-container">--}}
{{--                                <div class="timeline-container">--}}
{{--                                    <div class="timeline">--}}
{{--                                        <img class="preview-img">--}}
{{--                                        <div class="thumb-indicator"></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="controls">--}}
{{--                                    <button class="play-pause-btn">--}}
{{--                                        <svg class="play-icon" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M8,5.14V19.14L19,12.14L8,5.14Z" />--}}
{{--                                        </svg>--}}
{{--                                        <svg class="pause-icon" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M14,19H18V5H14M6,19H10V5H6V19Z" />--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                    <div class="volume-container">--}}
{{--                                        <button class="mute-btn">--}}
{{--                                            <svg class="volume-high-icon" viewBox="0 0 24 24">--}}
{{--                                                <path fill="currentColor" d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.84 14,18.7V20.77C18,19.86 21,16.28 21,12C21,7.72 18,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.76 16.5,12M3,9V15H7L12,20V4L7,9H3Z" />--}}
{{--                                            </svg>--}}
{{--                                            <svg class="volume-low-icon" viewBox="0 0 24 24">--}}
{{--                                                <path fill="currentColor" d="M5,9V15H9L14,20V4L9,9M18.5,12C18.5,10.23 17.5,8.71 16,7.97V16C17.5,15.29 18.5,13.76 18.5,12Z" />--}}
{{--                                            </svg>--}}
{{--                                            <svg class="volume-muted-icon" viewBox="0 0 24 24">--}}
{{--                                                <path fill="currentColor" d="M12,4L9.91,6.09L12,8.18M4.27,3L3,4.27L7.73,9H3V15H7L12,20V13.27L16.25,17.53C15.58,18.04 14.83,18.46 14,18.7V20.77C15.38,20.45 16.63,19.82 17.68,18.96L19.73,21L21,19.73L12,10.73M19,12C19,12.94 18.8,13.82 18.46,14.64L19.97,16.15C20.62,14.91 21,13.5 21,12C21,7.72 18,4.14 14,3.23V5.29C16.89,6.15 19,8.83 19,12M16.5,12C16.5,10.23 15.5,8.71 14,7.97V10.18L16.45,12.63C16.5,12.43 16.5,12.21 16.5,12Z" />--}}
{{--                                            </svg>--}}
{{--                                        </button>--}}
{{--                                        <input class="volume-slider" type="range" min="0" max="1" step="any" value="1">--}}
{{--                                    </div>--}}
{{--                                    <div class="duration-container">--}}
{{--                                        <div class="current-time">0:00</div>--}}
{{--                                        /--}}
{{--                                        <div class="total-time"></div>--}}
{{--                                    </div>--}}
{{--                                    <button class="captions-btn">--}}
{{--                                        <svg viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M18,11H16.5V10.5H14.5V13.5H16.5V13H18V14A1,1 0 0,1 17,15H14A1,1 0 0,1 13,14V10A1,1 0 0,1 14,9H17A1,1 0 0,1 18,10M11,11H9.5V10.5H7.5V13.5H9.5V13H11V14A1,1 0 0,1 10,15H7A1,1 0 0,1 6,14V10A1,1 0 0,1 7,9H10A1,1 0 0,1 11,10M19,4H5C3.89,4 3,4.89 3,6V18A2,2 0 0,0 5,20H19A2,2 0 0,0 21,18V6C21,4.89 20.1,4 19,4Z" />--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                    <button class="speed-btn wide-btn">--}}
{{--                                        1x--}}
{{--                                    </button>--}}
{{--                                    <button class="mini-player-btn">--}}
{{--                                        <svg viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zm-10-7h9v6h-9z"/>--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                    <button class="theater-btn">--}}
{{--                                        <svg class="tall" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M19 6H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 10H5V8h14v8z"/>--}}
{{--                                        </svg>--}}
{{--                                        <svg class="wide" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M19 7H5c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H5V9h14v6z"/>--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                    <button class="full-screen-btn">--}}
{{--                                        <svg class="open" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>--}}
{{--                                        </svg>--}}
{{--                                        <svg class="close" viewBox="0 0 24 24">--}}
{{--                                            <path fill="currentColor" d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>--}}
{{--                                        </svg>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <video src="">--}}
{{--                                <track kind="captions" srclang="en" src="">--}}
{{--                            </video>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_modal" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        @forelse($videos as $video)
            <div class="card m-5 p-5">
                <button
                    style="width: 200px"
                    class="btn btn-success video_modal"
                    data-video_id="{{$video->id}}"
                    data-video_source="{{route('get.video', [$video->id])}}"
                    data-toggle="modal"
                    data-target="#video_modal">{{$video->name}}</button>

            </div>
            @empty
        <p>nema video nijedan</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.plyr.io/3.7.3/plyr.js"></script>
    <script>
        let buttons=document.getElementsByClassName('video_modal');
        let controls=[
            'play-large', 'play', 'rewind', 'fast-forward', 'current-time', 'mute', 'restart', 'progress',
            'duration', 'volume', 'pip', 'fullscreen'
        ];
            for(let i=0; i<buttons.length; i++){
                buttons[i].addEventListener('click', (e)=>{
                    let video_source=e.currentTarget.dataset.video_source;
                    //c(video_id)//daje video_id iz dugmeta
                    let video_element=document.getElementsByTagName('video')[0];
                    let source_element=document.getElementsByTagName('source')[0];
                    source_element.setAttribute('src', video_source);
                    const player = new Plyr(video_element, {controls});
                    //video_element.load();

                    //gašenje videa
                    let close_btn=document.getElementById('close_modal');
                    close_btn.addEventListener('click', ()=>{
                        player.destroy()
                    });

                });
            }
    </script>







{{--    <script src="{{asset('video_player/video_player.js')}}"></script>--}}

{{--    <script>--}}
{{--        let c=console.log;--}}
{{--        let buttons=document.getElementsByClassName('video_modal');--}}
{{--        for(let i=0; i<buttons.length; i++){--}}
{{--            buttons[i].addEventListener('click', (e)=>{--}}
{{--                let video_id=e.currentTarget.dataset.video_id;--}}
{{--                //c(video_id)//daje video_id iz dugmeta--}}
{{--                let video=document.createElement('video');--}}
{{--                let src='/get-video/'+video_id;--}}
{{--                c(src)--}}
{{--                startVideo(src)--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}
@endsection
