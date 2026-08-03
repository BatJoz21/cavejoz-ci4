// Post image
const postImageUpload = document.getElementById('postImageUpload');
const postImageInput = document.getElementById('postImage');
const postImagePreview = document.getElementById('postImagePreview');
const postImagePlaceholder = document.getElementById('postImagePlaceholder');

if(postImageUpload && postImageInput && postImagePreview && postImagePlaceholder) {
    postImageUpload.addEventListener('click', () => {
        postImageInput.click();
    });

    postImageInput.addEventListener('change', () => {
        if(postImageInput.files.length > 0) {
            const contentFile = postImageInput.files[0];

            const contentReader = new FileReader();
            contentReader.onload = (event) => {
                postImagePreview.src = event.target.result;
                postImagePreview.style.display = 'block';
                postImagePlaceholder.style.display = 'none';
            };
            contentReader.readAsDataURL(contentFile);
        }
    });
}