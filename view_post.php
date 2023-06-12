<?php
include 'components/connect.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header("location:all_posts.php");
}

if (isset($_POST['delete_review'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete  = $conn->prepare("SELECT * FROM `reviews` WHERE id=?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id =?");
        $delete_review->execute([$delete_id]);
        $success_msg[] = "Review deleted!";
    } else{
        $waring_msg[] = "Review already delete!";
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view post</title>

    <!-- customer css file link -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- header section starts -->
    <?php include 'components/header.php'; ?>
    <!-- header section ends -->

    <!-- view posts section starts -->
    <section class="view-post">
        <div class="heading">
            <h1>post details</h1><a href="all_posts.php" class="inline-option-btn" style="margin-top: 0;">All posts</a>
        </div>
        <?php
        $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id = ? LIMIT 1");
        $select_posts->execute([$get_id]);
        if ($select_posts->rowCount() > 0) {
            while ($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                $total_ratings = 0;
                $rating_1 = 0;
                $rating_2 = 0;
                $rating_3 = 0;
                $rating_4 = 0;
                $rating_5 = 0;

                $select_ratings = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
                $select_ratings->execute([$fetch_post['id']]);
                $total_reviews = $select_ratings->rowCount();
                while ($fetch_rating =  $select_ratings->fetch(PDO::FETCH_ASSOC)) {
                    $total_ratings += $fetch_rating['rating'];
                    if ($fetch_rating['rating'] == 1) {
                        $rating_1 += $fetch_rating['rating'];
                    }
                    if ($fetch_rating['rating'] == 2) {
                        $rating_2 += $fetch_rating['rating'];
                    }
                    if ($fetch_rating['rating'] == 3) {
                        $rating_3 += $fetch_rating['rating'];
                    }
                    if ($fetch_rating['rating'] == 4) {
                        $rating_4 += $fetch_rating['rating'];
                    }
                    if ($fetch_rating['rating'] == 5) {
                        $rating_5 += $fetch_rating['rating'];
                    }
                }
                if ($total_reviews != 0) {
                    $averge = round($total_ratings / $total_reviews, 1);
                } else {
                    $averge = 0;
                }
        ?>
                <div class="row">
                    <div class="col">
                        <img src="uploaded_files/<?= $fetch_post['image']; ?>" alt="" class="image">
                        <h3 class="title"><?= $fetch_post['title']; ?></h3>
                    </div>
                    <div class="col">
                        <div class="flex">
                            <div class="total-reviews">
                                <h3><?= $averge; ?><i class="fas fa-star"></i></h3>
                                <p><?= $total_reviews; ?> reviews</p>
                            </div>
                            <div class="total-ratings">
                                <p>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span><?= $rating_5; ?></span>
                                </p>
                                <p>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span><?= $rating_4; ?></span>
                                </p>
                                <p>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span><?= $rating_3; ?></span>
                                </p>
                                <p>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span><?= $rating_2; ?></span>
                                </p>
                                <p>
                                    <i class="fas fa-star"></i>
                                    <span><?= $rating_1; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Post is missing!</p>';
        }
        ?>

    </section>
    <!-- view posts section ends -->

    <!-- reviews section starts -->
    <section class="reviews-container">
        <div class="heading">
            <h1>User's reviews</h1><a href="add_review.php?get_id=<?= $get_id; ?>" class="inline-btn" style="margin-top: 0;">Add review</a>
        </div>
        <div class="box-container">
            <?php
            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
            $select_reviews->execute([$get_id]);
            if ($select_reviews->rowCount() > 0) {
                while ($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box" <?php if ($fetch_review['user_id'] == $user_id) {
                                            echo 'style="order: -1"';}; ?>>
                        <?php
                        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                        $select_user->execute([$fetch_review['user_id']]);
                        while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                    <div class="user">
                        <?php if ($fetch_user['image'] != '') { ?>
                            <img src="uploaded_files/<?= $fetch_user['image']; ?>" alt="">
                        <?php } else { ?>
                            <h3><?= substr($fetch_user['name'], 0, 1); ?></h3>
                        <?php }; ?>
                        <div>
                            <p><?= $fetch_user['name']; ?></p>
                            <span><?= $fetch_review['date']; ?></span>
                        </div>
                    </div>
                        <?php }; ?>
                        <div class="ratings">
                            <?php if ($fetch_review['rating'] == 1) { ?>
                                <p style="background:var(--red);"><i class="fas fa-star"></i>
                                <span><?= $fetch_review['rating']; ?></span></p>
                            <?php } ?>
                            <?php if ($fetch_review['rating'] == 2) { ?>
                                <p style="background:var(--orange);"><i class="fas fa-star"></i>
                                <span><?= $fetch_review['rating']; ?></span></p>
                            <?php } ?>
                            <?php if ($fetch_review['rating'] == 3) { ?>
                                <p style="background:var(--orange);"><i class="fas fa-star"></i>
                                <span><?= $fetch_review['rating']; ?></span></p>
                            <?php } ?>
                            <?php if ($fetch_review['rating'] == 4) { ?>
                                <p style="background:var(--main-color);"><i class="fas fa-star"></i>
                                <span><?= $fetch_review['rating']; ?></span></p>
                            <?php } ?>
                            <?php if ($fetch_review['rating'] == 5) { ?>
                                <p style="background:var(--main-color);"><i class="fas fa-star"></i>
                                <span><?= $fetch_review['rating']; ?></span></p>
                            <?php } ?>
                        </div>
                        <h3 class="title"><?= $fetch_review['title'] ?></h3>
                       <?php if($fetch_review['description'] != ''){ ?>
                            <p class="des"><?= $fetch_review['description'] ?></p>
                        <?php } ?>
                        <?php if ($fetch_review['user_id'] == $user_id) { ?>
                            <form action="" method="post" class="flex-btn">
                                <input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">
                                <a href="update_review.php?get_id=<?= $fetch_review['id']; ?>" class="inline-option-btn">Edit review</a>
                                <input type="submit" value="delete_review" class="inline-delete-btn" 
                                name="delete_review" onclick="return confirm('Delete this reivew?')">
                            </form>
                        <?php } ?>
                    </div>
            <?php

                }
            } else {
                echo '<p class="empty">No reviews added yet!</p>';
            }
            ?>
        </div>
    </section>
    <!-- reviews section ends -->


    <!-- sweetalert cdn link  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- customer js file link -->
    <script src="js/script.js"></script>

    <?php include 'components/alers.php'; ?>
</body>

</html>