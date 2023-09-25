const MAX_FILES_NUMBER = 4;

let uploadUrl = '/tasks/upload';

let myDropzone = new Dropzone('.new-file', {
    maxFiles: MAX_FILES_NUMBER,
    url: uploadUrl,
    previewsContainer: ".files-previews",
    sending: function (none, xhr, formData) {
        formData.append('_csrf', $('input[name=_csrf]').val());
    }
});

