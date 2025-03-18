const carousel = document.querySelector(".carousel");
const prevButton = document.querySelector(".carousel-prev");
const nextButton = document.querySelector(".carousel-next");

let scrollAmount = 0;
const itemWidth = 220; // Chiều rộng mỗi item (bao gồm khoảng cách)

nextButton.addEventListener("click", () => {
    scrollAmount += itemWidth;
    carousel.scrollTo({ left: scrollAmount, behavior: "smooth" });
});

prevButton.addEventListener("click", () => {
    scrollAmount -= itemWidth;
    carousel.scrollTo({ left: scrollAmount, behavior: "smooth" });
});


// Danh sách tỉnh thành theo từng miền
function toggleMenu() {
    var dropdown = document.getElementById("menuDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

$(".hero-section h1").lettering();
var textElement = document.querySelector(".hero-section h1");
textElement.addEventListener("mouseenter", () => {
    var cd = anime({
        targets: ".hero-section h1 span",
        opacity: [0, 1],
        duration: 500,
        color: ["#ffffff", "#11b3b0"],
        translateY: [-50, 0],
        delay: (el, i) => i * 10
    });
});
textElement.addEventListener("mouseleave", () => {
    var cd1 = anime({
        targets: ".hero-section h1 span",
        opacity: [0, 1],
        duration: 500,
        color: "#ffffff",
        translateY: [-10, 0],
        delay: (el, i) => i * 10
    });
});

// jquery
function closeModal() {
    document.querySelector('.modal').style.display = 'none';
}

// hiện modal
$(function () {
    $(".carousel-item img").click(function () {
        console.log("Ảnh được click"); // Debug kiểm tra sự kiện click
        $(".modal").addClass("dira");
        $(".modal-content").addClass("hienra");
    });

    $(".modal-btn, .modal").click(function (e) {
        if ($(e.target).is(".modal, .modal-btn")) {
            console.log("Đóng modal");
            $(".modal").removeClass("dira");
            $(".modal-content").removeClass("hienra");
        }
    });
});

// let modalContent = document.querySelector("#myModal .modal-content");

// if (modalContent) {
//     modalContent.innerHTML = response;
// } else {
//     console.error("Không tìm thấy .modal-content trong #myModal");
// }



// Hiển thị modal


$(document).ready(function () {
    $(".carousel-item img").click(function (e) {
        e.preventDefault(); // Ngăn chuyển trang

        let postId = $(this).data("id"); // Lấy ID bài viết từ <img>
        console.log("Post ID:", postId); // In ra console kiểm tra

        if (!postId) {
            alert("Không tìm thấy ID bài viết!");
            return;
        }

        $.ajax({
            url: "getpost.php",
            type: "GET",
            data: { id: postId },
            success: function (response) {
                console.log("Response từ server:", response); // Kiểm tra dữ liệu từ server
                $("#myModal .modal-content").html(response); // Đổ dữ liệu vào modal
                $("#myModal").show(); // Hiển thị modal
            },
            error: function () {
                alert("Lỗi tải bài viết!");
            }
        });
    });
});


// $(document).ready(function () {
//     $(".carousel-item img").click(function (e) {
//         e.preventDefault(); // Ngăn chuyển trang

//         let postId = $(this).data("id"); // Lấy ID bài viết từ data-id
//         $.ajax({
//             url: "getpost.php",
//             type: "GET",
//             data: { id: postId },
//             success: function (response) {
//                 $("#myModal .modal-content").html(response); // Đổ dữ liệu vào modal
//                 $("#myModal").html(data).show(); // Hiển thị nội dung modal
//             },
//             error: function () {
//                 alert("Lỗi tải bài viết!");
//             }
//         });
//     });


// });






setInterval(function () {
    fetch("approved.php")
        .then(response => response.text())
        .then(data => {
            if (data.trim() !== "") {
                let modal = document.getElementById("modal");
                let modalContent = document.getElementById("modal-content");
                if (!modalContent) {
                    console.error("Lỗi: Không tìm thấy phần tử modal-content!");
                    return;
                }
                modalContent.innerHTML = data;

                modal.style.display = "block";
            }
        });
}, 5000); // Kiểm tra mỗi 5 giây




function openModal(postId) {
    fetch("approved.php?id=" + postId)  // Gọi API để lấy bài viết theo ID
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("modal-title").innerText = data.title;
                document.getElementById("modal-date").innerText = data.created_at;
                document.getElementById("modal-content").innerText = data.content;
                document.getElementById("modal-message").style.display = "none";
            } else {
                document.getElementById("modal-message").innerText = "Không tìm thấy bài viết.";
                document.getElementById("modal-message").style.display = "block";
            }
            document.getElementById("myModal").style.display = "block";
        })
        .catch(error => console.error("Lỗi:", error));
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
    const menuItem = document.querySelector(".here");
    const dropdown = document.querySelector(".destination-dropdow");

    if (menuItem && dropdown) {
        menuItem.addEventListener("mouseenter", function () {
            dropdown.classList.add("show");
        });

        menuItem.addEventListener("mouseleave", function (event) {
            // Kiểm tra nếu chuột rời khỏi `.here` nhưng vẫn trong `.destination-dropdown`
            if (!dropdown.contains(event.relatedTarget)) {
                dropdown.classList.remove("show");
            }
        });

        dropdown.addEventListener("mouseleave", function () {
            dropdown.classList.remove("show");
        });
    }
});




