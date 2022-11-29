@extends('layouts.app')
@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;500&display=swap');
        :root{
            --player-width: 100%;
            --controls-color: #F2F2F2;
            --slider-width: 100%;
            --slider-height: 4px;
            --thumb-width: 15px;
            --thumb-height: 15px;
            --lower-color: #ffffff63;
            --upper-color: red;
            --thumb-color: red;
            --thumb-opacity: 0;
        }
        .player-container {
            width: var(--player-width);
            height: auto;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            background-color: black;
        }
        .video-selector{
            width: var(--player-width);
            height: auto;
            vertical-align: middle;
        }
        .goes-full{
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }
        .player-controls{
            position: absolute;
            bottom: 0px;
        }
        .controls-background{
            width: var(--player-width);
            height: 100px;
            position: absolute;
            bottom: 0px;
            z-index: 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6));
        }
        /* player progressbar */
        .player-progressbar{
            width: var(--slider-width);
            height: 10px;
            position: relative;
            z-index: 2;
            left: 5px;
        }
        .range-container{
            width: var(--slider-width);
            height: var(--slider-height);
            position: relative;
            z-index: 1;
            top: 50%;
            transform: translateY(-50%);
            transition: all .2s;
            -webkit-transition: all .2s;
            background-color: transparent;
        }
        .range-container input{
            width: 100%;
            height: var(--slider-height);
            outline: none;
            -webkit-appearance: none;
            pointer-events: none;
            margin-left: 0px;
            position: absolute;
            z-index: 1;
            top: -2px;
            opacity: 0;
        }
        .range-container input::-webkit-slider-runnable-track{
            height: var(--slider-height);
        }
        .range-container input::-webkit-slider-thumb{
            pointer-events: auto;
            -webkit-appearance: none;
            width: var(--thumb-width);
            height: var(--thumb-height);
            border-radius: 100%;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            background-color: black;
        }
        .range-container .lower,
        .range-container .upper{
            width: calc(var(--slider-width) - var(--thumb-width));
            height: 100%;
            background-color: var(--lower-color);
            position: absolute;
            z-index: -1;
            margin-left: calc(var(--thumb-width) / 2);
        }
        .range-container .upper{
            width: var(--upper-width);
            height: 100%;
            background-color: var(--upper-color);
            margin-left: 0px;
        }
        .range-container .upper::before,
        .range-container .upper::after{
            content: '';
            background-color: var(--thumb-color);
            width: var(--thumb-width);
            height: var(--thumb-height);
            border-radius: 100%;
            position: absolute;
            z-index: 1;
            right: calc(0px - (var(--thumb-width) / 2));
            top: 0px;
            bottom: 0px;
            margin: auto;
            transition: all .2s;
            -webkit-transition: all .2s;
            opacity: var(--thumb-opacity);
        }
        .range-container .upper::after{
            width: calc(var(--thumb-width) + 100px);
            right: calc(0px - ((var(--thumb-width) / 2) + 50px));
            opacity: 0;
        }
        /* preview progress */
        .range-container .preview-progress{
            position: absolute;
            width: var(--preview-progress-width);
            height: 100%;
            background-color: #ffffff63;
            z-index: -1;
        }
        /* loded data progress */
        .range-container .loaded-progress{
            position: absolute;
            height: 100%;
            background-color: #ffffff63;
            z-index: -2;
        }
        /* player lower controls */
        .lower-controls{
            width: var(--player-width);
            height: 42px;
            position: relative;
            z-index: 1;
            /*display: table; orig*/
            display: flex;
            align-items: center;
            background-color: transparent;
        }
        .lower-controls svg,
        .lower-controls .digital-timer,
        .lower-controls .fullscreen,
        .lower-controls .volume{
            font-family: 'Noto Sans', sans-serif;
            font-weight: 300;
            fill: var(--controls-color);
            color: var(--controls-color);
            /*display: table-cell; orig*/
            vertical-align: middle;
            /*position: absolute; orig*/
        }
        .lower-controls .play-btn{
            left: 16px;
        }
        .lower-controls .pause-btn{
            left: 17px;
        }
        .lower-controls .hide-controls{
            display: none;
        }
        .lower-controls .rwd{
            left: 56px;
        }
        .lower-controls .stop{
            left: 92px;
            top: 3.5px;
        }
        .lower-controls .fwd{
            left: 121px;
        }
        .lower-controls .digital-timer{
            width: 120px;
            line-height: 40px;
            position: relative;
            left: 170px;
            font-size: 15px;
            bottom: 1px;
            user-select: none;
            display: inline-block;
        }
        .lower-controls .fullscreen,
        .lower-controls .pic-in-pic-mode{
            display: inline-block;
            right: 25px;
            top: 4px;
            width: 31px;
            height: 31px;
        }
        .lower-controls .fullscreen span{
            z-index: 1;
            position: absolute;
            width: 31px;
            height: 31px;
            background-color: transparent;
        }
        .lower-controls .fullscreen .hover-effect{
            width: 33px;
            height: 33px;
            left: -1px;
            top: -1px;
        }
        .lower-controls .fullscreen .hide-fullscreen{
            display: none;
        }
        .lower-controls .pic-in-pic-mode{
            width: 30px;
            height: 30px;
            right: 70px;
        }
        .lower-controls .volume{
            position: relative;
            right: 108px;
            width: 40px;
        }
        .lower-controls .volume svg{
            position: absolute;
            right: 7px;
            bottom: 10px;
            display: none;
        }
        .lower-controls .volume .vol-100{
            display: block;
        }
        .lower-controls .volume .vol-60{
            right: 9px;
        }
        .lower-controls .volume .vol-30{
            right: 11px;
        }
        .lower-controls .volume input{
            position: absolute;
            width: 0px;
            height: 4px;
            outline: none;
            -webkit-appearance: none;
            top: 0px;
            bottom: 2px;
            right: 30px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.7);
            opacity: 0;
            transition: all .15s;
        }
        .lower-controls .volume input::-webkit-slider-thumb{
            -webkit-appearance: none;
            width: 13px;
            height: 13px;
            border-radius: 100%;
            background-color: white;
        }
        .lower-controls .volume:hover input{
            width: 90px;
            opacity: 1;
            transition: all .3s;
            right: 40px;
        }
        .player-controls .preview-area{
            position: relative;
            bottom: 30px;
            width: 170px;
            height: 96px;
            background-color: black;
            margin-left: 11px;
            margin-right: 11px;
            border: 2px solid white;
            border-radius: 2px;
            display: none;
        }
        .player-controls .preview-area span{
            position: absolute;
            bottom: -27px;
            font-family: 'Noto Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: white;
            width: 100%;
            text-align: center;
        }
        .player-controls .preview-area img{
            border-radius: 2px;
        }
        </style
@endsection
@section('content')
    <div class="container">
        <div class="player-container">
            <video class="video-selector" preload="auto" oncontextmenu="return false" data-pause="true" data-changing="false">
                <source src="video_player/video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="player-controls">
                <div class="controls-background"></div>
                <div class="preview-area"><span>0:00</span><img src="" alt=""></div>
                <div class="player-progressbar">
                    <div class="range-container">
                        <input type="range" step="any" min="0" max="100" value="0">
                        <div class="lower">
                            <div class="preview-progress"></div>
                            <div class="loaded-progress"></div>
                            <div class="upper"></div>
                        </div>
                    </div>
                </div>
                <div class="lower-controls">
                    <svg class="play-btn" height="40" width="40"><path d="M14.167 30.083V9.792l15.958 10.166Z"/></svg>
                    <svg class="pause-btn hide-controls" viewBox="0 0 36 36" height="40" width="40"><path d="M 12,26 16,26 16,10 12,10 z M 21,26 25,26 25,10 21,10 z"></path></svg>
                    <svg class="rwd" height="40" width="40"><path d="M33.083 27.708 21.708 20l11.375-7.708Zm-14.791 0L6.917 20l11.375-7.708Z"/></svg>
                    <svg class="stop" viewBox="0 0 39 39" height="32" width="32"><path d="M12.167 27.833V12.167h15.666v15.666Z"/></svg>
                    <svg class="fwd" height="40" width="40"><path d="M6.875 27.708V12.292L18.25 20Zm14.875 0V12.292L33.125 20Z"/></svg>
                    <div class="digital-timer">
                        <span class="running-time">0:00</span>
                        /
                        <span class="total-time">0:00</span>
                    </div>
                    <div class="fullscreen">
                        <span></span>
                        <svg viewBox="0 0 40 40" height="31" width="31" data-fullscreen="false"><path d="M7.583 32.417v-9.334h3.667v5.667h5.667v3.667Zm0-15.5V7.583h9.334v3.667H11.25v5.667Zm15.5 15.5V28.75h5.667v-5.667h3.667v9.334Zm5.667-15.5V11.25h-5.667V7.583h9.334v9.334Z"/></svg>
                        <svg class="hide-fullscreen" viewBox="0 0 40 40" height="32" width="32"><path d="M13.708 31.667v-5.375H8.333v-2.75h8.125v8.125ZM8.333 16.458v-2.75h5.375V8.333h2.75v8.125Zm15.209 15.209v-8.125h8.125v2.75h-5.375v5.375Zm0-15.209V8.333h2.75v5.375h5.375v2.75Z"/></svg>
                    </div>
                    <svg class="pic-in-pic-mode" viewBox="0 0 39 39" height="40" width="40"><path d="M16.792 28.5h15.041V18H16.792ZM6.125 33.333q-1.125 0-1.958-.833-.834-.833-.834-1.958V9.458q0-1.125.834-1.958.833-.833 1.958-.833h27.75q1.125 0 1.958.833.834.833.834 1.958v21.084q0 1.125-.834 1.958-.833.833-1.958.833Zm0-2.791h27.75V9.458H6.125v21.084Zm0 0V9.458v21.084Z"/></svg>
                    <div class="volume">
                        <svg class="vol-100" height="25px" viewBox="0 0 23 23" width="25px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3 9v6h4l5 5V4L7 9H3zm7-.17v6.34L7.83 13H5v-2h2.83L10 8.83zM16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77 0-4.28-2.99-7.86-7-8.77z"/></svg>
                        <svg class="vol-60" height="25" viewBox="0 0 23 23" width="25"><path d="M5 15V9h4l5-5v16l-5-5Zm11 1V7.95q1.125.525 1.812 1.625.688 1.1.688 2.425 0 1.325-.688 2.4Q17.125 15.475 16 16Zm-4-7.15L9.85 11H7v2h2.85L12 15.15ZM9.5 12Z"/></svg>
                        <svg class="vol-30" height="25" viewBox="0 0 23 23" width="25"><path d="M7 15V9h4l5-5v16l-5-5Zm2-2h2.85L14 15.15v-6.3L11.85 11H9Zm2.5-1Z"/></svg>
                        <svg class="vol-0" height="25" viewBox="0 0 23 23" width="25"><path d="m19.8 22.6-3.025-3.025q-.625.4-1.325.688-.7.287-1.45.462v-2.05q.35-.125.688-.25.337-.125.637-.3L12 14.8V20l-5-5H3V9h3.2L1.4 4.2l1.4-1.4 18.4 18.4Zm-.2-5.8-1.45-1.45q.425-.775.638-1.625.212-.85.212-1.75 0-2.35-1.375-4.2T14 5.275v-2.05q3.1.7 5.05 3.137Q21 8.8 21 11.975q0 1.325-.362 2.55-.363 1.225-1.038 2.275ZM9.1 11.9Zm7.15 1.55L14 11.2V7.95q1.175.55 1.838 1.65.662 1.1.662 2.4 0 .375-.062.738-.063.362-.188.712ZM12 9.2 9.4 6.6 12 4Zm-2 5.95V12.8L8.2 11H5v2h2.85Z"/></svg>
                        <input type="range" min="0" max="1" step="any" value="0.8">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        const body = document.querySelector('body');
        const playerContainer = document.querySelector('.player-container');
        const media = document.querySelector('.video-selector');
        const playerProgressbar = document.querySelector('.range-container')
        const playerProgressbarUpper = document.querySelector('.range-container .upper')
        const playerProgressbarLower = document.querySelector('.range-container .lower')
        const playerProgressbarInput = document.querySelector('.range-container input')
        const playBtn = document.querySelector('.play-btn');
        const pauseBtn = document.querySelector('.pause-btn');
        const rwd = document.querySelector('.rwd');
        const stopMedia = document.querySelector('.stop');
        const fwd = document.querySelector('.fwd');
        const runningTime = document.querySelectorAll('.digital-timer span')[0];
        const totalTime = document.querySelectorAll('.digital-timer span')[1];
        const fullscreenSpan = document.querySelector('.fullscreen span')
        const fullscreen = document.querySelectorAll('.fullscreen svg')[0]
        const exitFullscreen = document.querySelectorAll('.fullscreen svg')[1]
        const picInPicMode = document.querySelector('.pic-in-pic-mode')
        const vol_100 = document.querySelectorAll('.volume svg')[0]
        const vol_60 = document.querySelectorAll('.volume svg')[1]
        const vol_30 = document.querySelectorAll('.volume svg')[2]
        const vol_0 = document.querySelectorAll('.volume svg')[3]
        const vol_input = document.querySelector('.volume input')
        const previewProgress = document.querySelector('.preview-progress')
        const previewArea = document.querySelector('.player-controls .preview-area')
        const previewAreaSpan = document.querySelector('.player-controls .preview-area span')
        const previewAreaImg = document.querySelector('.player-controls .preview-area img')
        const loadedProgress = document.querySelector('.range-container .loaded-progress')

        playerProgressbarLower.onmousemove = (e) => progressbarMouseMove(e)
        playerProgressbarInput.oninput = () => progressbarInput(true)
        playerProgressbar.onclick = () => progressbarClick()
        playerProgressbar.onmouseover = (e) => progressbarMouseover(e)
        playerProgressbar.onmousemove = (e) => progressbarMouseover(e)
        playerProgressbar.onmouseout = (e) => progressbarMouseout(e)
        playerProgressbar.onmousedown = () => updatePlayerTime()
        playerProgressbarInput.onchange = () => updatePlayerTime()
        playBtn.onclick = () => playMedia()
        pauseBtn.onclick = () => pauseMedia()
        rwd.onclick = () => timeBackward()
        stopMedia.onclick = () => stopPlayer()
        fwd.onclick = () => timeForward()
        media.onloadeddata = () => playerLoadedData()
        media.ontimeupdate = () => playerProgressData()
        media.onended = () => stopPlayer()
        media.onprogress = () => playerLoadedProgress()
        media.oncanplay = () => playerLoadedProgress()

        // player progressbar
        setTimeout(()=>{
            progressbarMouseout();
            setTimeout(()=>playerProgressbarUpper.style.setProperty('--thumb-opacity', '1'), 300)
        }, 1000)
        function progressbarMouseMove(e) {
            const rect = playerProgressbarLower.getBoundingClientRect()
            const percent = Math.min(Math.max(0, e.x - rect.x), rect.width) / rect.width;
            playerProgressbarInput.value = percent*100
        }
        function progressbarInput(v){
            playerProgressbarUpper.style.width = playerProgressbarInput.value + '%'
            if(v){media.setAttribute('data-changing', 'true')}
            pauseMedia(true)
        }
        function progressbarClick(){
            if(media.getAttribute('data-pause') !== 'true'){
                playMedia()
                media.setAttribute('data-pause', 'false')
            }
        }
        function progressbarMouseover(event){
            const rect = playerProgressbar.getBoundingClientRect()
            var x = event.clientX;
            if(x < rect.right-7 && x > rect.left+7){
                playerProgressbarUpper.style.setProperty('--thumb-width', '15px');
                playerProgressbarUpper.style.setProperty('--thumb-height', '15px');
                playerProgressbar.style.setProperty('--slider-height', '8px');
                previewProgress.style.opacity = '1';
            }else{
                progressbarMouseout(event)
            }
        }
        function progressbarMouseout(event){
            playerProgressbarUpper.style.setProperty('--thumb-width', '0px');
            playerProgressbarUpper.style.setProperty('--thumb-height', '0px');
            playerProgressbar.style.setProperty('--slider-height', '4px');
            previewProgress.style.opacity = '0';
            previewArea.style.display = 'none'
        }

        // basic player controls
        function updatePlayerTime(){
            media.currentTime = (playerProgressbarInput.value*media.duration)/100
            playerProgressbarUpper.style.width = playerProgressbarInput.value + '%'
            media.setAttribute('data-changing', 'false')
        }
        function playMedia() {
            media.play();
            playBtn.classList.add('hide-controls')
            pauseBtn.classList.remove('hide-controls')
            media.setAttribute('data-pause', 'false')
        }
        function pauseMedia(v) {
            media.pause();
            pauseBtn.classList.add('hide-controls')
            playBtn.classList.remove('hide-controls')
            if(!v){media.setAttribute('data-pause', 'true')}
        }
        function timeBackward() {
            media.currentTime -= 5;
        }
        function stopPlayer() {
            pauseMedia();
            media.currentTime = 0
        }
        function timeForward() {
            media.currentTime += 5;
        }
        // for full screen
        fullscreenSpan.onmouseover = () => {
            fullscreen.classList.add('hover-effect')
            setTimeout(()=>fullscreen.classList.remove('hover-effect'), 200)
        }
        fullscreenSpan.onclick = () => goFullScreen(true)
        window.onresize = () => goFullScreen(false)
        function goFullScreen(v){
            var state = document.webkitIsFullScreen;
            if (state == false && v == true) {
                playerContainer.requestFullscreen()
                document.documentElement.style.setProperty('--player-width', window.innerWidth + 'px');
                media.classList.add('goes-full')
                fullscreen.classList.add('hide-fullscreen')
                exitFullscreen.classList.remove('hide-fullscreen')
                body.style.overflowX = 'hidden'
                setTimeout(()=>fullscreen.setAttribute('data-fullscreen', 'true'), 200)
            }else if(state == true &&  v == true){
                document.exitFullscreen();
            }else if(state == true && v == false){
                document.documentElement.style.setProperty('--player-width', window.innerWidth + 'px');
            }
        }
        playerContainer.onfullscreenchange = () => {
            var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
            if(state == false){
                if(fullscreen.getAttribute('data-fullscreen') == 'true'){
                    media.classList.remove('goes-full')
                    fullscreen.classList.remove('hide-fullscreen')
                    exitFullscreen.classList.add('hide-fullscreen')
                    fullscreen.setAttribute('data-fullscreen', 'false')
                    document.documentElement.style.setProperty('--player-width', '800px');
                    setTimeout(()=>body.style.overflowX = 'auto', 200)
                }
            }
        };
        // for picture in picture mode
        picInPicMode.onclick = () => {
            if (document.pictureInPictureElement) {
                document.exitPictureInPicture();
            } else {
                if (document.pictureInPictureEnabled) {
                    media.requestPictureInPicture();
                }
            }
        }
        media.onleavepictureinpicture = e => {
            const was_playing = !media.paused
            if(was_playing){
                setTimeout(()=>playMedia(), 0)
            }else{
                setTimeout(()=>pauseMedia(), 0)
            }

        }
        // for volume controls
        function showVolumeIcons(v){
            if(Boolean(media.webkitAudioDecodedByteCount)){
                if(v >= 0.6){
                    volumeIconDecider(0)
                }else if(v >= 0.3){
                    volumeIconDecider(1)
                }else if(v > 0){
                    volumeIconDecider(2)
                }else{
                    volumeIconDecider(3)
                }
            }else{
                volumeIconDecider(3)
                vol_input.style.display = 'none'
                vol_0.style.fill = '#afafaf'
            }
        }
        function volumeIconDecider(v){
            var iconsList = ['100', '60', '30', '0']
            for(var i = 0; i < 4; i++){
                if(i == v){
                    document.querySelector('.volume .vol-' + iconsList[i]).style.display = 'block'
                }else{
                    document.querySelector('.volume .vol-' + iconsList[i]).style.display = 'none'
                }
            }
        }
        vol_input.oninput = () => {
            media.volume = vol_input.value;
            showVolumeIcons(vol_input.value)
        }
        // preview
        //playerProgressbarInput.onmouseover = (e) => showPreviewProgress(e)
        //playerProgressbarInput.onmousemove = (e) => showPreviewProgress(e)
        function showPreviewProgress(e){
            const rect = playerProgressbarLower.getBoundingClientRect()
            const percent = (Math.min(Math.max(0, e.x - rect.x), rect.width) / rect.width)*100;
            previewProgress.style.setProperty('--preview-progress-width', percent + '%')
            previewArea.style.display = 'block'
            var w = Number(window.getComputedStyle(playerProgressbarLower).width.replace(/[^0-9]/g, ''))
            if((percent*w)/100 <= 85){
                previewArea.style.left = '0px'
            }else if((percent*w)/100 >= w-85){
                previewArea.style.left = w-170 + 'px'
            }else{
                previewArea.style.left = ((percent*w)/100)-85 + 'px'
            }
            var sortedTime = timeSorter((percent*media.duration)/100);
            if(sortedTime.charAt(0) == 0){
                sortedTime = sortedTime.substring(1, sortedTime.length);
            }
            previewAreaSpan.innerHTML = sortedTime;
            var previewImageNumber = (Math.floor((percent-0.01)/4))+1;
            if(previewImageNumber > 0){ previewAreaImg.src = `frames/frame-${previewImageNumber}.png` }
        }
        // for loaded data progress
        function playerLoadedProgress() {
            var duration = media.duration;
            for (var i = 0; i <= duration; i++) {
                try { var totalBuffered = media.buffered.end(i) }catch {}
                var percentBuffered = (totalBuffered / duration) * 100;
                loadedProgress.style.width = percentBuffered + '%'
            }
        }
        // give currentTime on timeupdate
        function playerProgressData(){
            var sortedTime = timeSorter(media.currentTime)
            if(sortedTime.charAt(0) == 0){
                sortedTime = sortedTime.substring(1, sortedTime.length);
            }
            runningTime.innerHTML = sortedTime
            if(media.getAttribute('data-changing') == 'false'){
                playerProgressbarUpper.style.width = (media.currentTime*100)/media.duration + '%'
            }
        }
        // give total duration of video
        function playerLoadedData() {
            var sortedTime = timeSorter(media.duration)
            if(sortedTime.charAt(0) == 0){
                sortedTime = sortedTime.substring(1, sortedTime.length);
            }
            totalTime.innerHTML = sortedTime
            // show volume icons after video loaded
            showVolumeIcons(vol_input.value);
        }

        // convert seconds to hours, minutes and seconds
        function timeSorter(v) {
            if (v > 0) {
                var timeGiven = v;
                var hours = minutes = seconds = 0;
                var calcHours = timeGiven / 3600;
                hours = Math.trunc(calcHours);
                var calcMinutes = Number('.' + calcHours.toString().split('.')[1]) * 60;
                minutes = Math.trunc(calcMinutes);
                if (minutes.toString().length == 1) { minutes = '0' + minutes; }
                var calcSeconds = Number('.' + calcMinutes.toString().split('.')[1]) * 60;
                seconds = Math.trunc(calcSeconds);
                if (seconds.toString().length == 1) { seconds = '0' + seconds; }
                if (hours == 0) {
                    return `${minutes}:${seconds}`
                } else if (hours == 0 && minutes == 0) {
                    return `00:${seconds}`
                } else {
                    return `${hours}:${minutes}:${seconds}`
                }
            } else {
                return `00:00`
            }
        }
    </script>
@endsection
