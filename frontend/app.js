const links = document.querySelectorAll(".top-nav a[data-page]");
const current = document.body.dataset.page;

links.forEach((link) => {
  if (link.dataset.page === current) {
    link.classList.add("active");
  }
});

const gallery = document.querySelector(".product-gallery");

if (gallery) {
  const stageImage = gallery.querySelector(".product-stage img");
  const thumbs = Array.from(gallery.querySelectorAll(".thumb"));
  const [previousButton, nextButton] = Array.from(gallery.querySelectorAll(".gallery-controls button"));
  let activeIndex = Math.max(0, thumbs.findIndex((thumb) => thumb.classList.contains("active")));

  const setActiveImage = (nextIndex) => {
    if (!thumbs.length || !stageImage) {
      return;
    }

    activeIndex = (nextIndex + thumbs.length) % thumbs.length;

    thumbs.forEach((thumb, index) => {
      thumb.classList.toggle("active", index === activeIndex);
    });

    const image = thumbs[activeIndex].querySelector("img");
    stageImage.src = image.src;
  };

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener("click", () => setActiveImage(index));
  });

  previousButton?.addEventListener("click", () => setActiveImage(activeIndex - 1));
  nextButton?.addEventListener("click", () => setActiveImage(activeIndex + 1));
}

const staffPage = document.querySelector(".staff-page");

if (staffPage) {
  const statusClassNames = ["status-pending", "status-processing", "status-shipped"];
  const stockInputs = Array.from(document.querySelectorAll(".stock-input"));
  const stockTotal = document.querySelector("#stockTotal");
  const lowStockCount = document.querySelector("#lowStockCount");
  const processingCount = document.querySelector("#processingCount");
  const staffNote = document.querySelector(".staff-note");

  const updateStockStats = () => {
    const values = stockInputs.map((input) => Math.max(0, Number(input.value) || 0));
    stockTotal.textContent = values.reduce((sum, value) => sum + value, 0);
    lowStockCount.textContent = values.filter((value) => value <= 5).length;

    stockInputs.forEach((input) => {
      input.closest(".stock-item").classList.toggle("is-low", (Number(input.value) || 0) <= 5);
    });
  };

  const updateProcessingCount = () => {
    const statuses = Array.from(document.querySelectorAll(".order-status"));
    processingCount.textContent = statuses.filter((select) => select.value === "Processing").length;
  };

  document.querySelectorAll(".order-status").forEach((select) => {
    select.addEventListener("change", () => {
      const pill = select.closest(".status-control").querySelector(".status-pill");
      pill.textContent = select.value;
      pill.classList.remove(...statusClassNames);
      pill.classList.add(`status-${select.value.toLowerCase()}`);
      updateProcessingCount();
    });
  });

  stockInputs.forEach((input) => {
    input.addEventListener("input", updateStockStats);
  });

  document.querySelector(".staff-save").addEventListener("click", () => {
    staffNote.textContent = "Static updates saved for this browser view.";
  });

  updateStockStats();
  updateProcessingCount();
}
