<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PickleHeroes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .video-banner {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .video-banner video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            transform: translate(-50%, -50%);
            z-index: 0;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .banner-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>

<section class="video-banner">
    <video autoplay muted loop>
        <source src="{{ asset('videos/pickle-ball.mp4') }}" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

  
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
