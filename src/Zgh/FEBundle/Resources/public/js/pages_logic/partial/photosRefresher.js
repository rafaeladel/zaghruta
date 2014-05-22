function photoRefresh(){
    var photo_uploader = new Uploader({
        wrapperID: "c_photos",
        modalID: "addPhotoPopup",
        listBtnID: "btnPhots",
        listWrapperClass: "content_wrapper",
        browseClass: "dropzone_photo",
        saveButtonClass: "photo_submitBtn",
        previewClass: "photos_previews",
        targetUrl: UrlContainer.newPhoto,
        ajaxLoadUrl: UrlContainer.photoPartial,
        imageRequired: true,
        additionalData: {
            "album_id": {
                "required": true,
                "message": "Please select an album"
            }
        },
        numOfFiles: 10
    });
    photo_uploader.init();

    var album_uploader = new Uploader({
        wrapperID: "c_photos",
        modalID: "addAlbumPopup",
        listBtnID: "btnAlbums",
        listWrapperClass: "content_wrapper",
        browseClass: "dropzone_album",
        saveButtonClass: "album_submitBtn",
        previewClass: "albums_previews",
        targetUrl: UrlContainer.newAlbum,
        ajaxLoadUrl: UrlContainer.albumPartial,
        imageRequired: false,
        additionalData: {
            "album_name": {
                "required": true,
                "message": "Please enter album name."
            },
            "album_info": {
                "required": true,
                "message": "Please enter album info."
            }
        },
        numOfFiles: 10
    });
    album_uploader.init();
}