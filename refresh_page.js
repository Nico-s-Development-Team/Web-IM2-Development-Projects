
  // Detect if the page was restored from back/forward cache
  window.addEventListener('pageshow', function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
      // Force full reload
      location.reload();
    }
  });

