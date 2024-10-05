document.addEventListener("DOMContentLoaded", function () {
  // Get all checkboxes in the navigation
  const checkboxes = document.querySelectorAll('.loki-menu input[type="checkbox"]');

  checkboxes.forEach((checkbox, index) => {
    // Generate a unique ID for each checkbox
    const uniqueId = `${Date.now()}-${index}`;

    // Assign the ID to the checkbox
    checkbox.setAttribute('id', uniqueId);

    // Find the corresponding label for the checkbox
    const label = checkbox.nextElementSibling.querySelector('label');

    // Set the 'for' attribute of the label
    if (label) {
      label.setAttribute('for', uniqueId);
    }
  });
});
