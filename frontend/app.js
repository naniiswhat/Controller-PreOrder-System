const links = document.querySelectorAll(".top-nav a[data-page]");
const current = document.body.dataset.page;

links.forEach((link) => {
  if (link.dataset.page === current) {
    link.classList.add("active");
  }
});

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
