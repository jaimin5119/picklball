// const qrcode = window.qrcode;

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");

const qrResult = document.getElementById("qr-result");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");
const btnScanQRStop = document.getElementById("btn-scan-qr-stop");

let scanning = false;

qrcode.callback = res => {
    if (res) {
        outputData.innerText = res;
        scanning = false;

        video.srcObject.getTracks().forEach(track => {
            track.stop();
        });

        qrResult.hidden = false;
        canvasElement.hidden = true;
        btnScanQR.hidden = false;
        btnScanQRStop.hidden = true;
    }
};

btnScanQR.onclick = () => {
    navigator.mediaDevices
        .getUserMedia({video: {facingMode: "environment"}})
        .then(function (stream) {
            scanning = true;
            qrResult.hidden = true;
            btnScanQR.hidden = true;
            canvasElement.hidden = false;
            btnScanQRStop.hidden = false;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.srcObject = stream;
            video.play();
            tick();
            scan();
        });
};
btnScanQRStop.onclick = () => {
    navigator.mediaDevices
    scanning = false;
    qrResult.hidden = true;
    btnScanQR.hidden = false;
    canvasElement.hidden = true;
    btnScanQRStop.hidden = true;
};

function tick() {
    // canvasElement.height = video.videoHeight;
    // canvasElement.width = video.videoWidth;
    canvasElement.height = 250;
    canvasElement.width = 250;
    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

    scanning && requestAnimationFrame(tick);
}

function scan() {

    try {
        new qrcode.decode();
        var ref = qrcode.result;
        $.ajax({
            type: 'GET',
            url: '/add_scanner_details',
            data: {
                ref_id: ref
            }
        });
        location.reload(true);
    } catch (e) {
        setTimeout(scan, 300);
    }
}
