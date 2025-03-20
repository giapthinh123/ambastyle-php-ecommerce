document.addEventListener("DOMContentLoaded", function () {
    const menu = document.querySelector("nav ul");
    const toggleBtn = document.createElement("button");
    toggleBtn.classList.add("menu-toggle");
    toggleBtn.innerHTML = "☰";

    document.querySelector("header .container").prepend(toggleBtn);

    toggleBtn.addEventListener("click", function (event) {
        event.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
        menu.classList.toggle("active");
    });

    // Kiểm tra khi thay đổi kích thước màn hình
    function checkScreenSize() {
        if (window.innerWidth > 768) {
            toggleBtn.style.display = "none"; // Ẩn nút ☰ khi màn hình lớn
            menu.classList.remove("active"); // Đảm bảo menu không bị mở khi resize
        } else {
            toggleBtn.style.display = "block"; // Hiện nút ☰ khi màn hình nhỏ
        }
    }

    window.addEventListener("resize", checkScreenSize);
    checkScreenSize(); // Gọi hàm kiểm tra ngay khi load trang

    // Đóng menu khi nhấn vào chỗ trống
    document.addEventListener("click", function () {
        if (menu.classList.contains("active")) {
            menu.classList.remove("active");
        }
    });

    // Ngăn chặn sự kiện click lan ra ngoài menu
    menu.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});
// HIỂN THỊ STORY VÀ ABOUT KHI NHẤN VÀO
document.addEventListener("DOMContentLoaded", function () {
    const storyLink = document.getElementById("story-link");
    const storySection = document.getElementById("story-section");
    const aboutLink = document.getElementById("about-link");
    const aboutSection = document.getElementById("about-section");

    storyLink.addEventListener("click", function (event) {
        event.preventDefault();
        aboutSection.style.display = "none"; // Ẩn ABOUT nếu đang mở
        if (storySection.style.display === "none" || storySection.style.display === "") {
            storySection.style.display = "block"; // Hiện STORY
        } else {
            storySection.style.display = "none"; // Ẩn STORY
        }
    });

    aboutLink.addEventListener("click", function (event) {
        event.preventDefault();
        storySection.style.display = "none"; // Ẩn STORY nếu đang mở
        if (aboutSection.style.display === "none" || aboutSection.style.display === "") {
            aboutSection.style.display = "block"; // Hiện ABOUT
        } else {
            aboutSection.style.display = "none"; // Ẩn ABOUT
        }
    });
     // Đóng ABOUT và STORIES khi nhấn vào chỗ trống
     document.addEventListener("click", function (event) {
        if (!aboutSection.contains(event.target) && !aboutLink.contains(event.target)) {
            aboutSection.style.display = "none";
        }
        if (!storySection.contains(event.target) && !storyLink.contains(event.target)) {
            storySection.style.display = "none";
        }
    });
});
// HiỂN THỊ DANH MỤC SẢN PHẨM KHI NHẤN VÀO
document.addEventListener("DOMContentLoaded", function () {
    const collectionLink = document.getElementById("collection-link");
    const dropdown = document.querySelector(".dropdown");

    collectionLink.addEventListener("click", function (event) {
        event.preventDefault(); // Ngăn chuyển trang
        dropdown.classList.toggle("active"); // Hiển thị/tắt danh mục
    });

    // Ẩn danh mục khi click bên ngoài
    document.addEventListener("click", function (event) {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });
});
//=======HIỂN THỊ ĐỊA CHỈ=======//
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script.js đã tải!");

    const citySelect = document.getElementById("city");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");

    let vietnamData = []; // Biến lưu trữ dữ liệu JSON

    // Hàm tải dữ liệu từ file JSON
    function loadVietnamData() {
        fetch('../data/vietnamAddress.json')
            .then(response => response.json())
            .then(data => {
                vietnamData = data;
                loadCities(); // Sau khi có dữ liệu, tải danh sách tỉnh/thành phố
            })
            .catch(error => console.error("Lỗi tải dữ liệu:", error));
    }

    // Hàm tải danh sách tỉnh/thành phố
    function loadCities() {
        citySelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        vietnamData.forEach(city => {
            let option = document.createElement("option");
            option.value = city.Id;
            option.textContent = city.Name;
            citySelect.appendChild(option);
        });
    }

    // Hàm tải danh sách quận/huyện khi chọn tỉnh/thành phố
    function loadDistricts(cityId) {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>'; // Reset phường/xã

        let selectedCity = vietnamData.find(city => city.Id === cityId);
        if (selectedCity) {
            selectedCity.Districts.forEach(district => {
                let option = document.createElement("option");
                option.value = district.Id;
                option.textContent = district.Name;
                districtSelect.appendChild(option);
            });
        }
    }

    // Hàm tải danh sách phường/xã khi chọn quận/huyện
    function loadWards(cityId, districtId) {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

        let selectedCity = vietnamData.find(city => city.Id === cityId);
        if (selectedCity) {
            let selectedDistrict = selectedCity.Districts.find(d => d.Id === districtId);
            if (selectedDistrict) {
                selectedDistrict.Wards.forEach(ward => {
                    let option = document.createElement("option");
                    option.value = ward.Id;
                    option.textContent = ward.Name;
                    wardSelect.appendChild(option);
                });
            }
        }
    }

    // Sự kiện khi chọn tỉnh/thành phố
    citySelect.addEventListener("change", function () {
        let cityId = this.value;
        if (cityId) {
            loadDistricts(cityId);
        } else {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });

    // Sự kiện khi chọn quận/huyện
    districtSelect.addEventListener("change", function () {
        let cityId = citySelect.value;
        let districtId = this.value;
        if (cityId && districtId) {
            loadWards(cityId, districtId);
        } else {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    });

    // Gọi hàm để tải dữ liệu từ file JSON
    loadVietnamData();
});
function searchProducts() {
    const query = document.getElementById('search-query').value;
    const resultsContainer = document.getElementById('search-results');

    if (query.length > 0) {
        fetch(`../process/search_ajax.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(product => {
                        const productItem = document.createElement('div');
                        productItem.classList.add('search-result-item');
                        productItem.innerHTML = `
                            <a href="../Pages/ProductDetail.php?id=${product.id}">
                                <img src="../${product.image_url}" alt="${product.name}">
                                <span>${product.name}</span>
                            </a>
                        `;
                        resultsContainer.appendChild(productItem);
                    });
                } else {
                    resultsContainer.innerHTML = '<p>Không tìm thấy sản phẩm nào!</p>';
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        resultsContainer.innerHTML = '';
    }
}