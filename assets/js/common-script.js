var isFirefox = typeof InstallTrigger !== 'undefined';
if (isFirefox != true) {
    alert("Sorry !!! Incompatible Browser. Please Use Mozilla FireFox. Thank You.");
    $('#body-content').addClass('hide');
} else {
    DetectRTC.load(function () {
        if (DetectRTC.hasWebcam == false) {
            alert("Sorry !!! You do not have a webcam. Please install one for your Video Call. Thank you.");
            $('#body-content').addClass('hide');
        } else {
            $('#body-content').removeClass('hide');
        }
    });
}
