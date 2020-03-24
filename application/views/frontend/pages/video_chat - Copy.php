<!DOCTYPE html>
<html>

<head>
    <script type='text/javascript' src="<?=base_url('assets/js/scaledrone.min.js')?>"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            align-items: center;
            justify-content: center;
            padding: 0 50px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        video {
            max-width: calc(50% - 100px);
            margin: 0 50px;
            box-sizing: border-box;
            border-radius: 2px;
            padding: 0;
            box-shadow: rgba(156, 172, 172, 0.2) 0px 2px 2px, rgba(156, 172, 172, 0.2) 0px 4px 4px, rgba(156, 172, 172, 0.2) 0px 8px 8px, rgba(156, 172, 172, 0.2) 0px 16px 16px, rgba(156, 172, 172, 0.2) 0px 32px 32px, rgba(156, 172, 172, 0.2) 0px 64px 64px;
        }

        .copy {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 16px;
            color: rgba(0, 0, 0, 0.5);
        }

    </style>
</head>

<body>
    <video id="localVideo" autoplay muted></video>
    <video id="remoteVideo" autoplay></video>
    <div>
        <!--<button id="startButton" class="btn btn-info hide">Start</button>
    <button id="callButton" class="btn btn-info" style="margin-left:50%;">Call</button>-->
        <button id="hangupButton" class="btn btn-danger">Hang Up</button>
    </div>
    <script src="<?=base_url('assets/js/script.js')?>"></script>

    <!--<script src="<?=base_url('assets/js/video-chat.js')?>"></script>-->
</body>

</html>
