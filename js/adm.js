// $(document).ready(function () {
//     // Mở modal khi click vào tiêu đề trong modal-content
//     $(".btn-primary").click(function () {
//         event.stopPropagation(); // Ngăn sự kiện lan ra document
//         console.log("Mở modal");
//         $("#addPostModal").fadeIn();
//     });

//     // Đóng modal khi click vào nút "Đóng"
//     $(".modal-btn").click(function () {
//         $("#addPostModal").fadeOut();
//     });

//     // Đóng modal khi click ra ngoài modal-content
//     $(document).click(function (event) {
//         if (!$(event.target).closest(".modal-content").length && !$(event.target).is(".btn .btn-primary")) {
//             $("#addPostModal").fadeOut();
//         }
//     });
// });



$(document).ready(function () {
    $("#addPostForm").submit(function (event) {
        event.preventDefault(); // Ngăn form submit mặc định

        var formData = {
            title: $("#title").val(),
            content: $("#content").val(),
            user_id: $("#user_id").val() // Lấy user_id từ input
        };

        $.ajax({
            type: "POST",
            url: "addpost.php", // Đường dẫn đến file xử lý
            data: formData,
            success: function (response) {
                alert(response); // Hiển thị phản hồi từ server
                $("#addPostModal").fadeOut(); // Đóng modal
                location.reload(); // Load lại trang
            },
            error: function () {
                alert("Lỗi khi thêm bài viết!");
            }
        });
    });
});

$(document).ready(function () {
    // Hiển thị modal sửa bài viết
    $(".edit-btn").click(function () {
        let postId = $(this).data("id");

        $.get("editpost.php?id=" + postId, function (data) {
            let post = JSON.parse(data);
            $("#edit_post_id").val(post.id);
            $("#edit_title").val(post.title);
            $("#edit_content").val(post.content);
            $("#editPostModal").show();
        });
    });

    // Xử lý gửi form sửa bài viết
    $("#editPostForm").submit(function (e) {
        e.preventDefault();
        $.post("editpost.php", $(this).serialize(), function (response) {
            let res = JSON.parse(response);
            alert(res.message);
            if (res.success) {
                location.reload();
            }
        });
    });

    // Đóng modal
    $(".close-modal").click(function () {
        $("#editPostModal").hide();
    });

    // Xác nhận xóa bài viết
    $(document).ready(function () {
        $(".delete-btn").click(function () {
            if (confirm("Bạn có chắc chắn muốn xóa bài viết này không?")) {
                let postId = $(this).data("id");

                $.post("deletepost.php", { id: postId }, function (response) {
                    let res = JSON.parse(response);
                    alert(res.message);
                    if (res.success) {
                        location.reload();
                    }
                });
            }
        });
    });

});

