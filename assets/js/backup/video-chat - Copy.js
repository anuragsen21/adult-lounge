'use strict';
var startButton = document.getElementById('startButton');
var callButton = document.getElementById('callButton');
var hangupButton = document.getElementById('hangupButton');
callButton.disabled = true;
hangupButton.disabled = true;
startButton.onclick = start;
callButton.onclick = call;
hangupButton.onclick = hangup;
var startTime;
var localVideo = document.getElementById('localVideo');
var remoteVideo = document.getElementById('remoteVideo');
//localVideo.addEventListener('loadedmetadata', function () {
/*trace('Local video videoWidth: ' + this.videoWidth +
    'px,  videoHeight: ' + this.videoHeight + 'px');*/
//});
//remoteVideo.addEventListener('loadedmetadata', function () {
/*trace('Remote video videoWidth: ' + this.videoWidth +
    'px,  videoHeight: ' + this.videoHeight + 'px');*/
//});
remoteVideo.onresize = function () {
    //trace('Remote video size changed to ' +
    //    remoteVideo.videoWidth + 'x' + remoteVideo.videoHeight);
    // We'll use the first onsize callback as an indication that video has started
    // playing out.
    if (startTime) {
        var elapsedTime = window.performance.now() - startTime;
        /*trace('Setup time: ' + elapsedTime.toFixed(3) + 'ms');*/
        startTime = null;
    }
};
var localStream;
var remoteStream;
var pc1;
var pc2;
var offerOptions = {
    offerToReceiveAudio: 1,
    offerToReceiveVideo: 1
};

function getName(pc) {
    //return (pc === pc1) ? 'pc1' : 'pc2';
    return pc1;
}

function getOtherPc(pc) {
    //return (pc === pc1) ? pc2 : pc1;
    return pc2;
}

function gotStream(stream) {
    localVideo.srcObject = stream;
    localStream = stream;
    callButton.disabled = false;
}

function gotStreamR(stream) {
    remoteVideo.srcObject = stream;
    remoteStream = stream;
    callButton.disabled = false;
}

function start() {
    //trace('Requesting local stream');
    startButton.disabled = true;
    navigator.mediaDevices.getUserMedia({
            audio: true,
            video: true
        })
        .then(gotStream)
        .catch(function (e) {
            alert('getUserMedia() error: ' + e.name);
        });
}

function call() {
    callButton.disabled = true;
    hangupButton.disabled = false;
    startTime = window.performance.now();
    var videoTracks = localStream.getVideoTracks();
    var audioTracks = localStream.getAudioTracks();
    /*if (videoTracks.length > 0) {
        trace('Using video device: ' + videoTracks[0].label);
    }
    if (audioTracks.length > 0) {
        trace('Using audio device: ' + audioTracks[0].label);
    }*/
    //var servers = null;
    //var remoteservers = document.getElementById(remote_addr_two);

    var servers = {
        url: 'http://103.120.188.126/projects/adultlounge/video-chat/',
        socketio: {
            /* 'force new connection':true*/
        },
        connection: null,
        debug: false,
        localVideoEl: '',
        remoteVideosEl: '',
        enableDataChannels: true,
        autoRequestMedia: true,
        autoRemoveVideos: true,
        adjustPeerVolume: false,
        peerVolumeWhenSpeaking: 0.25,
        media: {
            video: true,
            audio: true
        },
        receiveMedia: {
            offerToReceiveAudio: 1,
            offerToReceiveVideo: 1
        },
        localVideo: {
            autoplay: true,
            mirror: true,
            muted: true
        }
    };

    var remoteservers = {
        url: '192.168.1.44',
        socketio: {
            /* 'force new connection':true*/
        },
        connection: null,
        debug: false,
        localVideoEl: '',
        remoteVideosEl: '',
        enableDataChannels: true,
        autoRequestMedia: true,
        autoRemoveVideos: true,
        adjustPeerVolume: false,
        peerVolumeWhenSpeaking: 0.25,
        media: {
            video: true,
            audio: true
        },
        receiveMedia: {
            offerToReceiveAudio: 1,
            offerToReceiveVideo: 1
        },
        localVideo: {
            autoplay: true,
            mirror: true,
            muted: true
        }
    };

    pc1 = new RTCPeerConnection(servers);
    //trace('Created local peer connection object pc1');
    pc1.onicecandidate = function (e) {
        onIceCandidate(pc1, e);
    };
    pc2 = new RTCPeerConnection(remoteservers);
    //trace('Created remote peer connection object pc2');
    pc2.onicecandidate = function (e) {
        onIceCandidate(pc2, e);
    };
    pc1.oniceconnectionstatechange = function (e) {};
    pc2.oniceconnectionstatechange = function (e) {

    };
    pc2.onaddstream = gotRemoteStream;

    pc1.addStream(localStream);
    //trace('Added local stream to pc1');

    //trace('pc1 createOffer start');
    pc1.createOffer(
        offerOptions
    ).then(
        onCreateOfferSuccess,
    );
}

function onCreateOfferSuccess(desc) {
    console.log(desc.sdp);
    trace('Offer from pc1\n' + desc.sdp);
    /*pc1.setLocalDescription(desc).then(
        function () {},
    );*/
    pc1.setLocalDescription(desc);
    pc2.setRemoteDescription(desc);
    //trace('pc2 createAnswer start');
    // Since the 'remote' side has no media stream we need
    // to pass in the right constraints in order for it to
    // accept the incoming offer of audio and video.
    pc2.createAnswer().then(
        onCreateAnswerSuccess
    );
}

function gotRemoteStream(e) {
    navigator.mediaDevices.getUserMedia({
            audio: true,
            video: true
        })
        .then(gotStreamR)
        .catch(function (e) {
            alert('getUserMedia() error: ' + e.name);
        });
    //remoteVideo.srcObject = e.stream;
}

function onCreateAnswerSuccess(desc) {
    //trace('Answer from pc2:\n' + desc.sdp);
    pc2.setLocalDescription(desc).then(
        function () {},
    );
    pc1.setRemoteDescription(desc).then(
        function () {},
    );
}

function onIceCandidate(pc, event) {
    getOtherPc(pc).addIceCandidate(event.candidate)
        .then(
            function () {},
            function (err) {}
        );
}

function hangup() {
    trace('Ending call');
    pc1.close();
    pc2.close();
    pc1 = null;
    pc2 = null;
    hangupButton.disabled = true;
    callButton.disabled = false;
}

function trace(arg) {
    var now = (window.performance.now() / 1000).toFixed(3);
    //console.log(now + ': ', arg);
}
startButton.click();
