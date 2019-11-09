'use strict';

var video = "";
var canvas = "";

// Access webcam
async function init_webcam(ivideo, icanvas) {
    video = ivideo;
    canvas = icanvas;
    const constraints = {
        audio: false,
        video: {
            width: 1280,
            height: 720
        }
    };

    try {
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        handle_success(stream);
    } catch (e) {
        alert('Si quieres cargar una imagen desde la camara, debes activar los permisos. Si usas un telefono, puedes usar tu camara al cargar una imagen.');
    }
}

// Success
function handle_success(stream) {
    window.stream = stream;
    video.srcObject = stream;
}

// Draw image
function generate_image() {
    canvas.width = video.clientWidth;
    canvas.height = video.clientHeight;
    var context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
};
// Save image
function save_image(value) {
    value.src = canvas.toDataURL();
};