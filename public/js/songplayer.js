$(document).ready(function(){
  var songContainer = document.querySelectorAll('.song-container');
  var song_id = $('.song-container').attr('song-id');
  songContainer.forEach(element => {
  var progress = element.querySelector('.progress');
  var ctrlicon = element.querySelector('.play-icon');
  var playbtn = element.querySelector('.play');
  var song = element.querySelector('.song');
  var backward = element.querySelector('.backward');
  var forward = element.querySelector('.forward');
  var wave = element.querySelector('#wave');
  var timestamp = element.querySelector('.timestamp');
  var likeIcon = element.querySelector('.like-icon');
  var imageDisk = element.querySelector('#main-image');
  var duration;
  var minutes;
  var seconds;
  var like;
  // $(wave).removeClass('wave-hider');
  function liked() {
    like = 1;
    $.post("controller/likeshandler.php",
    {
      songId: song_id,
      like: like
    });
  }
  function disliked() {
    like = 0;
    $.post("controller/likeshandler.php",
    {
      songId: song_id,
      like: like
    });
  }
  function songduration(time) {
    duration = ((time)/60);

    console.log(time);

    minutes = parseInt(time/60);
    seconds = parseInt(time % 60);
    if (parseInt(minutes/10) == 0) {
      minutes = '0'+minutes;
    }
    if (parseInt(seconds/10) == 0) {
      seconds = '0'+seconds;
    }
    timestamp.innerHTML = minutes + ':' + seconds;
  }
  song.pause();
  song.onloadedmetadata = function() {
    
    progress.max = song.duration;
    duration = ((song.duration)/60);

    minutes = parseInt(duration);
    seconds = parseInt((duration *100)%(minutes*100));
    progress.value = song.currentTime;
  }
  $(playbtn).click(function() { 

    if (ctrlicon.classList.contains('fa-pause')) {
      song.pause();
      imageDisk.style.animationPlayState = 'paused';
      ctrlicon.classList.remove('fa-pause');
      ctrlicon.classList.add('fa-play');
      $(wave).addClass('wave-hider');
    }
    else {
      $(wave).removeClass('wave-hider');
      song.play();
      // imageDisk.classList.add('rotation-animation');
      imageDisk.style.animationPlayState = 'running';
      ctrlicon.classList.remove('fa-play');
      ctrlicon.classList.add('fa-pause');
    }
  });
  $(backward,forward).click(function() {
    $(wave).removeClass('wave-hider');
    song.play();
    ctrlicon.classList.remove('fa-play');
    ctrlicon.classList.add('fa-pause');
  });
  $(forward).click(function() {
    progress.value = song.currentTime + 10;
    song.currentTime = progress.value;
  });
  $(backward).click(function() {
    progress.value = song.currentTime - 10;
    song.currentTime = progress.value;
  });
  if (song.play()) {
    setInterval(() => {
    songduration(song.currentTime);
    progress.value = song.currentTime;
    }, 1000);
  }
  if (!song.paused) {
  $(wave).removeClass('wave-hider');
  imageDisk.style.animationPlayState = 'running';
  }
  else {
  $(wave).addClass('wave-hider');
  ctrlicon.classList.remove('fa-pause');
  ctrlicon.classList.add('fa-play');
  imageDisk.style.animationPlayState = 'paused';
  }
  progress.onchange = function() {
    song.play();
    song.currentTime = progress.value;
    $(wave).removeClass('wave-hider');
    ctrlicon.classList.add('fa-pause');
    ctrlicon.classList.remove('fa-play');
  }
  $(likeIcon).click(function(){
    $(this).toggleClass('liked');
    if (likeIcon.classList.contains('liked')) {
      liked();
    }
    else {
      disliked();
    }
  });
  });
  
});