<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Image Resizer</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/favicon.png">
</head>
<body>


    <div class="alert-box"></div>

    <div class="box">
        <h3>Resize your single or multiple images at the same time!</h3>

        <form action="" enctype="multipart/form-data" id="upform" onsubmit="event.preventDefault(); process_upload();">
            <label for="files">
                <div class="title">
                <i class='bx bx-cloud-upload'></i>    
                <span>Select your file(s)</span>
                </div>
                <p class="note">PS  <q>You can choose multiple images or a zip file (images included)</q></p>
            </label>

            <input type="file" name="files[]" id="files" multiple accept=".zip,.gif,.png,.jpg,.jpeg"/>
            <div class="dimension">
                <input type="text" name="width" id="width" placeholder="width.."/>
                <span>x</span>
                <input type="text" name="height" id="height" placeholder="height.."/>
            </div>
            <button type="submit">Resize Images</button>
        </form>


        <div class="result">
                    <div class="text"></div>

        </div>

    </div>



<script src="assets/script.js"></script>

</body>
</html>