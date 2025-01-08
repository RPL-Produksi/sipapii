$(document).ready(() => {
    const isMobile = /Mobi|Android/i.test(navigator.userAgent);
    const cameraDiv = $("#camera");
    const dumpImage = $("#dumpImage");
    const previewContainer = $("#preview");
    const capturedImage = $("#capturedImage");
    const cameraInput = $("#cameraInput");

    const dataURItoFile = (dataURI, fileName) => {
        const byteString = atob(dataURI.split(",")[1]);
        const mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new File([ab], fileName, {
            type: mimeString
        });
    }

    if (!isMobile) {
        cameraDiv.removeClass("d-none");
        cameraDiv.addClass("webcam");
        dumpImage.addClass("d-none");

        Webcam.set({
            width: 590,
            height: 460,
            image_format: "jpeg",
            jpeg_quality: 50,
        });

        Webcam.attach("#camera");

        $("#takePicture").click(() => {
            Webcam.snap((dataUri) => {
                dumpImage.addClass("d-none");
                cameraDiv.addClass("d-none");
                previewContainer.removeClass("d-none");
                capturedImage.attr("src", dataUri);

                const file = dataURItoFile(dataUri, "snapshot.jpg");

                const fileInput = document.getElementById("cameraInput");
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            });

            $("#takePicture").addClass("d-none");
            $("#btnGroupAbsen").removeClass("d-none");
        });

        $("#retakePicture").click(() => {
            previewContainer.addClass("d-none");
            capturedImage.attr("src", "");

            Webcam.reset();
            Webcam.attach("#camera");

            $("#btnGroupAbsen").addClass("d-none");
            $("#takePicture").removeClass("d-none");
            cameraDiv.removeClass("d-none");
        });
    }


    if (isMobile) {
        cameraDiv.addClass("d-none");

        $("#takePicture").click(() => {
            cameraInput.click();
        });

        $("#retakePicture").click(() => {
            cameraInput.click();
        });

        cameraInput.change((event) => {
            const file = event.target.files[0];

            if (file) {
                $('#takePicture').addClass('d-none');
                $('#btnGroupAbsen').removeClass('d-none');

                const reader = new FileReader();
                reader.onload = (e) => {
                    dumpImage.addClass("d-none");
                    previewContainer.removeClass("d-none");
                    capturedImage.attr("src", e.target.result);
                };

                reader.readAsDataURL(file);
            }
        });
    }
});