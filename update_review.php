<?php
    include 'components/connect.php';
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else{
        $get_id = '';
        header("location:all_posts.php");
    }

    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);
        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $rating = $_POST['rating'];
        $rating = filter_var($rating, FILTER_SANITIZE_STRING);

        $update_review = $conn->prepare("UPDATE `reviews` SET rating = ?, title = ?, description = ?  WHERE id =?");
        $update_review->execute([$rating, $title, $description, $get_id]);

        $success_msg[] = "Review updated!";
    } else {
        $warning_msg[] = "Update review failed!";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update review</title>
    <!-- customer css file link -->
    <link rel="stylesheet"  href="css/style.css">
</head>
<body>
<!-- header section starts -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- Update review section starts -->
<section class="account-form">

    <?php
    $select_review = $conn->prepare("SELECT * FROM `reviews` WHERE id= ? LIMIT 1");
    $select_review->execute([$get_id]);
    if ($select_review->rowCount() > 0) {
        while ($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <form action="" method="post">
        <h3>edit your review</h3>
        <p class="placeholder">review title <span>*</span></p>
        <input type="text" name="title" require maxlength="50" 
        placeholder="Enter review title" class="box" value="<?= $fetch_review['title']; ?>">
        <p class="placeholder">review description</p>
        <textarea name="description" class="box" placeholder="Enter review description"
        maxlength="1000" rows="10" cols="30"><?= $fetch_review['description']; ?></textarea>
        <p class="placeholder">review rating <span>*</span></p>
        <select name="rating" id="" require class="box">
            <option value="<?= $fetch_review['rating']; ?>"><?= $fetch_review['rating']; ?></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit" value="update review" name="submit" class="btn">
        <a href="view_post.php?get_id=<?= $fetch_review['post_id']; ?>" class="option-btn">Go back</a>
    </form>
    <?php
        }
    } else {
        echo '<p class="empty">somethign went wrong!</p>';
    }
    ?>
</section>
<!-- update review section ends -->


<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- customer js file link -->
<script src="js/script.js"></script>

<?php include 'components/alers.php'; ?>
</body>
</html>