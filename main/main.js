const productsPerPage = 3;
let currentPage = 1;
const menuItems = document.querySelectorAll(".menu-item");
const totalPages = Math.ceil(menuItems.length / productsPerPage);

function showPage(page) {
  const startIndex = (page - 1) * productsPerPage;
  const endIndex = startIndex + productsPerPage;

  menuItems.forEach((item, index) => {
    if (index >= startIndex && index < endIndex) {
      item.style.display = "block";
    } else {
      item.style.display = "none";
    }
  });

  document.querySelector(".current-page").textContent = page;
}

document.querySelector(".prev-page").addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    showPage(currentPage);
  }
});

document.querySelector(".next-page").addEventListener("click", () => {
  if (currentPage < totalPages) {
    currentPage++;
    showPage(currentPage);
  }
});

showPage(1);


