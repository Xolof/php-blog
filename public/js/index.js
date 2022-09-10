/**
 * Flash message
 */
const flashMessage = document.getElementById("flashMessage");

if (flashMessage) {
    window.setTimeout(() => {
        flashMessage.classList.add("fade-out");
    }, 3500);
}

/**
 * Add image to post
 */
const addImageToPostButtons = document.getElementsByClassName(
    "addImageToPostButton"
);

const textArea = document.getElementsByTagName("textarea")[0];

function addImageToPost(e) {
    const img = e.target.parentElement.children[0];
    const src = img.src;
    const alt = img.alt;
    const regex = /\/img.*/;
    const imagePath = src.match(regex)[0];
    const imgMarkdown = `![${alt}](${imagePath})`;
    textArea.value += `\n\n${imgMarkdown}`;
    textArea.scrollTop = 100000;
}

for (let button of addImageToPostButtons) {
    button.onclick = (e) => {
        addImageToPost(e);
    };
}

/**
 * Save an image
 */
const saveImgButton = document.getElementById("saveImage");
const imageFileInput = document.getElementById("imageFileInput");
const uploadImageMessageDiv = document.getElementById("uploadImageMessageDiv");

function saveImage(e) {
    e.preventDefault();

    const imageFile = imageFileInput.files[0];
    const fileName = imageFile.name;

    var formData = new FormData();
    formData.append("imageFile", imageFile);
    formData.append("saveImage", true);

    var statusCode = 0;

    fetch("/api/save-image", {
        method: "post",
        body: formData,
    })
        .then((res) => {
            statusCode = res.status;
            return res.json();
        })
        .then((json) => {
            const messageDivChildren = uploadImageMessageDiv.children;
            if (messageDivChildren.length) {
                messageDivChildren[0].remove();
            }

            const p = document.createElement("p");

            if (statusCode === 201) {
                p.style.color = "green";
                const div = document.createElement("div");
                const img = document.createElement("img");
                img.src = `/img/gallery/${json.fileName}`;
                img.alt = json.fileName;
                img.classList.add("galleryImage");
                const button = document.createElement("button");
                button.classList.add("addImageToPostButton");
                button.onclick = (e) => {
                    addImageToPost(e);
                };
                button.textContent = "Add to post";
                div.appendChild(img);
                div.appendChild(button);
                document
                    .getElementsByClassName("galleryImages")[0]
                    .prepend(div);
            } else {
                p.style.color = "red";
            }

            p.style.fontWeight = "bold";
            p.textContent = json.message;
            uploadImageMessageDiv.appendChild(p);
        });
}

if (saveImgButton) {
    saveImgButton.onclick = (e) => {
        saveImage(e);
    };
}

/**
 * Show filename for image to be uploaded
 */
if (imageFileInput) {
    imageFileInput.addEventListener("change", () => {
        const imageFile = imageFileInput.files[0];
        const imageFileNameEl =
            document.getElementsByClassName("imageFileName")[0];
        imageFileNameEl.textContent = "Image selected: " + imageFile.name;
        document.getElementsByClassName("imageFileInputLabel")[0].style.margin =
            "0";
        document.getElementById("saveImage").style.display = "block";
    });
}

/**
 * To top link
 */
toTopLink = document.getElementsByClassName("toTopLink")[0];

window.addEventListener("scroll", (e) => {
    const scrollTop =
        window.pageYOffset !== undefined
            ? window.pageYOffset
            : (
                  document.documentElement ||
                  document.body.parentNode ||
                  document.body
              ).scrollTop;

    if (scrollTop > 225) {
        if (!toTopLink.classList.contains("opaque")) {
            toTopLink.classList.add("opaque");
        }
    } else {
        if (toTopLink.classList.contains("opaque")) {
            toTopLink.classList.remove("opaque");
        }
    }
});
