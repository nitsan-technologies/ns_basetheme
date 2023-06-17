window.addEventListener("DOMContentLoaded", (event) => {
  const cusCard = document.querySelectorAll('.ns-ext-form .card .card-header');
  const cusCardSaveBtn = document.querySelectorAll('.ns-ext-form .card-header .btn-primary');
  const fireldInfoTrigger = document.querySelectorAll('.form-group .field-info-trigger');
  const themePreviewSelect =document.querySelectorAll('.form-group .themePreviewSelect');

  
  // Toggle each panel
  if (cusCard) {
    cusCard.forEach(($card) => {
      $card.addEventListener('click', ()=> {
        $card.querySelector('h5 em').classList.toggle('fa-caret-up');
        const cardBody = $card.parentNode.querySelector('.card-body');
        if (cardBody.style.display === "none") {
          cardBody.style.display = "block";
        } else {
          cardBody.style.display = "none";
        }
      });
    });
  } 
  
  // Disable toggle feature on every Submit/Save button
  if (cusCardSaveBtn) {
    cusCardSaveBtn.forEach(($btn)=> {
      $btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if(document.getElementById('nsLicenseLoader')) {
          document.getElementById('nsLicenseLoader').style='block';
        }
      });
    });
  }

  // Toggle question icon
  if (fireldInfoTrigger) {
    fireldInfoTrigger.forEach(($trigger) => {
      $trigger.addEventListener('click', (e) => {
        const infoDesc = $trigger.closest('.form-group').querySelector('.field-info-text');
        if (infoDesc.style.display === "block") {
          infoDesc.style.display = "none";
        } else {
          infoDesc.style.display = "block";
        }
        
      });
    });
  }

  // PATCH: Let's add preview image feature on click change
  if (themePreviewSelect) {
    themePreviewSelect.forEach(($selectItem) => {
      if ($selectItem) {
        $selectItem.addEventListener('change', () => {
          const dataId = $selectItem.getAttribute('data-id');
          const prevImg = document.querySelector(`.themePreviewImg_${dataId}`);
          const selectOptionImg = $selectItem.querySelector('option:selected').getAttribute('data-img-src');
          prevImg.setAttribute('src', selectOptionImg);
        });
      }
    });
  }
});
