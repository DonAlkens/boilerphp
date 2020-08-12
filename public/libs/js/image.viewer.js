function ImageViewer(element, options =
    {
        display: "body",
        thumbnail: { node: "div", style: "img-thumbnail rounded" },
        extensions: [],
        showRemoveBtn: true,
        removeBtn: { node: "button", style: "btn btn-primary", text: "Remove" }
    }) {

    var images = [];
    var file = document.querySelector(element);
    var length = file.files.length;
    var extensions = ["gif", "png", "jpeg", "jpg"]; 
    
    var j = 0;
    for (var i = 0; i < length; i++) {

        var filename = file.files[i].name;
        var extension = filename.split(".").pop().toLowerCase();

        if (jQuery.inArray(extension, extensions) == -1) {
            file.value = "";
            alert("Invalid file(s) selected!");
        }
        else {
            images.push(file.files[i]);

            const reader = new FileReader(); // The file reader object
            reader.onload = function () {
                //When files have been read by the file reader
                //the onload method will be executed - async

                //For an image reading and previewing
                //Create a new image object
                const image = new Image();
                image.src = reader.result;

                //Render image to browser
                var imgbox = document.createElement(options.thumbnail.node);
                imgbox.setAttribute("class", options.thumbnail.style);
                imgbox.setAttribute("id", "image-viewer_file-id-" + (j))

                imgbox.appendChild(image);


                if (options.showRemoveBtn == undefined || options.showRemoveBtn == true) {

                    var remove = document.createElement(options.removeBtn.node);

                    remove.setAttribute("image-viewer_remove-file-id", (j));
                    remove.setAttribute("class", "image-viewer_remove " + options.removeBtn.style);

                    remove.innerHTML = options.removeBtn.text;

                    remove.onclick = function (event) {

                        var index = event.target.attributes["image-viewer_remove-file-id"].nodeValue;
                        removal_index = Number(index);
                        if(removal_index > images.length) {
                            removal_index = index - (index - image.length);
                        }

                        images.splice(removal_index, 1);
                        $("#image-viewer_file-id-" + index).remove();

                    }

                    imgbox.appendChild(remove);
                }

                document.querySelector(options.display).appendChild(imgbox);
                j++;
            }
            //Browser file reader object
            // It can read files as text using the the readAsText method from the FileReader object.
            //It can read files as URL link using the readAsDataURL method
            reader.readAsDataURL(file.files[i]);

        }
    }



    return { images };
}
