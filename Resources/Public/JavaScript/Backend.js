document
  .querySelectorAll(".form-group .formengine-field-item .input-group")
  .forEach((formFieldIcon) => {
    formFieldIcon
      .querySelectorAll(".input-group-icon img")
      .forEach((selectedIcon) => {
        const fragment = document.createDocumentFragment();
        const selectedImageDiv = document.createElement("div");
        selectedImageDiv.classList.add(
          "NsBasethemeElementIconContainerAtVariance"
        );

        fragment.appendChild(selectedImageDiv);
        const selectedImage = document.createElement("img");
        selectedImage.setAttribute("loading", selectedIcon.loading);
        selectedImage.setAttribute("src", selectedIcon.src);
        selectedImage.setAttribute("alt", selectedIcon.alt);
        selectedImage.setAttribute("title", selectedIcon.title);
        selectedImageDiv.appendChild(selectedImage);
        formFieldIcon.insertAdjacentElement("afterend", selectedImageDiv);
      });
  });

document
  .querySelectorAll(".form-group .input-group .form-control")
  .forEach((themeField) => {
    if (themeField.id || themeField.value) {
      if (themeField.value.includes("fileadmin")) {
        const fragment = document.createDocumentFragment();
        const IconDiv = document.createElement("div");
        IconDiv.classList.add(
          "themeOptionsImagesContainer",
          `${themeField.id}`
        );
        fragment.appendChild(IconDiv);

        const IconImage = document.createElement("img");
        IconImage.classList.add("themeOptionsImages");
        IconImage.setAttribute(
          "src",
          themeField.value.charAt(0) !== "/"
            ? `/${themeField.value}`
            : themeField.value
        );
        IconDiv.appendChild(IconImage);
        themeField.parentNode.parentNode.appendChild(IconDiv);
      }
    }
  });
