<x-filament-widgets::widget>
    <div style="display: grid; gap: 2rem;">
        <x-filament::section id="cameraContainer" class="hidden">
            <video autoplay playsinline id="webCam" class="full-width"></video>
            <div id="result" class="full-width"></div>
        </x-filament::section>

        <div id="loading" class="hidden text-center" style="margin-top: 1rem;">
            <div class="spinner"></div>
        </div>

        <x-filament::section class="hidden" id="imageResultContainer">
            <div id="result"></div>
            <button id="sendImage" class="btn btn-success">Kirim Gambar</button>
            <button id="retakeImage" class="btn btn-primary">Ulang Mengambil Gambar</button>
        </x-filament::section>
    </div>

    <x-filament::section id="actionCamera" style="margin-top: 2rem">
        <canvas id="canvas" class="hidden" width="800" height="600"></canvas>
        <button id="startCamera" class="btn btn-info">Lakukan Absensi</button>
        <div class="grid">
            <button id="capture" class="btn btn-warning hidden">Tangkap Gambar</button>
            <button id="stopCamera" class="btn btn-danger hidden">Tutup Kamera</button>
        </div>
        <div id="permission-message" class="text-danger"></div>
    </x-filament::section>

    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #007bff;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        #result>img {
            width: 100%
        }

        .full-width {
            width: 100%;
        }

        .hidden {
            display: none;
        }

        .btn {
            padding: 1rem;
            width: 100%;
            border-radius: 10px;
            color: white;
            margin-top: 1rem;
            border: none;
            cursor: pointer;
        }

        .btn-success {
            background: #28A745;
        }

        .btn-primary {
            background: #007BFF;
        }

        .btn-warning {
            background: #B8902F;
        }

        .btn-info {
            background: #306EBB;
        }

        .btn-danger {
            background: #CC2A18;
        }

        .text-center {
            text-align: center;
        }


        .grid {
            display: grid;
        }

        .text-danger {
            color: red;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('webCam');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const captureButton = document.getElementById('capture');
            const startCameraButton = document.getElementById('startCamera');
            const stopCameraButton = document.getElementById('stopCamera');
            const cameraContainer = document.getElementById('cameraContainer');
            const resultDiv = document.getElementById('result');
            const permissionMessage = document.getElementById('permission-message');
            const imageResultContainer = document.getElementById('imageResultContainer');
            const sendImageButton = document.getElementById('sendImage');
            const retakeImageButton = document.getElementById('retakeImage');
            const actionCamera = document.getElementById('actionCamera');
            const loadingDiv = document.getElementById('loading');

            let isIn = localStorage.getItem('attendanceType') === 'IN' || localStorage.getItem('attendanceType') ===
                null;
            let stream;
            let capturedDataURL;

            async function startWebcam() {
                const {
                    lat,
                    lng
                } = await getGeolocation();

                let isInOffice = await @this.isInOffice({
                    lat: lat,
                    lng: lng
                });


                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    video.srcObject = stream;
                    cameraContainer.style.display = 'block';
                    permissionMessage.textContent = '';
                    startCameraButton.style.display = 'none';
                    stopCameraButton.style.display = 'inline';
                    captureButton.style.display = 'inline';
                    imageResultContainer.style.display = 'none';
                } catch (error) {
                    permissionMessage.textContent = 'Kamera belum dapat izin akses.';
                }
            }

            function stopWebcam() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                    cameraContainer.style.display = 'none';
                    startCameraButton.style.display = 'inline';
                    stopCameraButton.style.display = 'none';
                    captureButton.style.display = 'none';
                    imageResultContainer.style.display = 'none';
                    actionCamera.style.display = 'block';
                }
            }

            startCameraButton.addEventListener('click', startWebcam);
            stopCameraButton.addEventListener('click', stopWebcam);

            captureButton.addEventListener('click', () => {
                if (video.srcObject) {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    capturedDataURL = canvas.toDataURL('image/png');
                    resultDiv.innerHTML = `<img src="${capturedDataURL}" />`;
                    imageResultContainer.style.display = 'block';
                    video.style.display = "none";
                    actionCamera.style.display = 'none';
                } else {
                    resultDiv.innerHTML = '<p>Pengambilan gambar gagal, periksa setting kamera anda.</p>';
                }
            });

            sendImageButton.addEventListener('click', async () => {
                if (capturedDataURL) {
                    loadingDiv.style.display = 'block';
                    try {
                        const {
                            lat,
                            lng
                        } = await getGeolocation();

                        await @this.present({
                            image_path: capturedDataURL,
                            lat: lat,
                            lng: lng
                        });

                        window.location.reload();

                    } catch (error) {
                        resultDiv.innerHTML =
                            '<p>Gagal mengambil lokasi anda, cek izin akses terlebih dahulu.</p>';
                    } finally {
                        loadingDiv.style.display = 'none';
                    }
                } else {
                    resultDiv.innerHTML = '<p>Belum ada gambar untuk dikirim.</p>';
                }
            });

            retakeImageButton.addEventListener('click', () => {
                imageResultContainer.style.display = 'none';
                resultDiv.innerHTML = '';
                video.style.display = 'inline';
                actionCamera.style.display = 'block';
                if (!video.srcObject) {
                    startWebcam();
                }
            });

            function getGeolocation() {
                return new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        const options = {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        };

                        navigator.geolocation.getCurrentPosition(position => {
                            resolve({
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            });
                        }, error => {
                            reject(error);
                        }, options);
                    } else {
                        reject(new Error('Lokasi tidak support untuk browser ini.'));
                    }
                });
            }
        });
    </script>
</x-filament-widgets::widget>
