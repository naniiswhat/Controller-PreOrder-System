const links = document.querySelectorAll(".top-nav a[data-page]");
const current = document.body.dataset.page;
const transitionTargets = document.querySelectorAll('a[href^="shop.php"], a[href^="product.php"]');
const authClose = document.querySelector(".auth-close");
const authPageNames = new Set(["login.php", "register.php"]);

const getUrl = (url) => {
  if (!url) {
    return null;
  }

  try {
    return new URL(url, window.location.href);
  } catch (error) {
    return null;
  }
};

const getPageName = (url) => {
  const parsedUrl = getUrl(url);

  if (!parsedUrl) {
    return "";
  }

  return parsedUrl.pathname.substring(parsedUrl.pathname.lastIndexOf("/") + 1);
};

const isAuthUrl = (url) => authPageNames.has(getPageName(url));
const lastNonAuthPageKey = "controllerPreOrderLastNonAuthPage";

if (!isAuthUrl(window.location.href)) {
  sessionStorage.setItem(lastNonAuthPageKey, window.location.href);
}

if (authClose) {
  const fallbackPage = new URL(authClose.getAttribute("href") || "index.php", window.location.href).href;
  const storedPage = sessionStorage.getItem(lastNonAuthPageKey);
  const referrer = document.referrer;
  const referrerUrl = getUrl(referrer);
  const referrerIsSameSite = referrerUrl?.origin === window.location.origin;
  const referrerIsAuthPage = referrerIsSameSite && isAuthUrl(referrer);
  const referrerIsHandler = referrerIsSameSite && referrerUrl.pathname.includes("/php/");
  const canUseBrowserBack = referrerIsSameSite && !referrerIsAuthPage && !referrerIsHandler && window.history.length > 1;
  const hasStoredPage = storedPage && !isAuthUrl(storedPage);

  if (hasStoredPage) {
    authClose.href = storedPage;
  }

  authClose.addEventListener("click", (event) => {
    if (
      event.defaultPrevented ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey ||
      authClose.target
    ) {
      return;
    }

    event.preventDefault();

    if (referrerIsAuthPage && hasStoredPage && window.history.length > 2) {
      window.history.go(-2);
      return;
    }

    if (canUseBrowserBack) {
      window.history.back();
      return;
    }

    window.location.replace(hasStoredPage ? storedPage : fallbackPage);
  });
}

links.forEach((link) => {
  if (link.dataset.page === current) {
    link.classList.add("active");
  }
});

transitionTargets.forEach((link) => {
  link.addEventListener("click", (event) => {
    const url = new URL(link.href, window.location.href);

    if (
      event.defaultPrevented ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey ||
      link.target ||
      url.href === window.location.href
    ) {
      return;
    }

    event.preventDefault();
    document.body.classList.add("page-transitioning");
    window.setTimeout(() => {
      window.location.href = url.href;
    }, 240);
  });
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
