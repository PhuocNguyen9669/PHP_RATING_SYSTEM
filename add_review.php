<?php
    include 'components/connect.php';

    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else{
        $get_id = '';
        header("location:all_posts.php");
    }

    if (isset($_POST['submit'])) {
        if($user_id != ''){
            $id = create_unique_id();
            $title = $_POST['title'];
            $title = filter_var($title, FILTER_SANITIZE_STRING);
            $description = $_POST['description'];
            $description = filter_var($description, FILTER_SANITIZE_STRING);
            $rating = $_POST['rating'];
            $rating = filter_var($rating, FILTER_SANITIZE_STRING);

            $verify_review = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND  user_id =?");
            $verify_review->execute([$get_id, $user_id]);

            if ($verify_review->rowCount() > 0 ) {
                $warning_msg[] = 'Your review already added!';
            }else{
                $add_review = $conn->prepare("INSERT INTO `reviews` (id, post_id, user_id, rating, title, description)
                VALUES (?,?,?,?,?,?)");
                $add_review->execute([$id, $get_id, $user_id, $rating, $title, $description]);
                $success_msg[] = "Review added!";
            }
        } else {
            $warning_msg[] = 'Please login first!';
        }
    } 
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add review</title>

    <!-- customer css file link -->
    <link rel="stylesheet"  href="css/style.css">
   
</head>
<body>

<!-- header section starts -->
<?php include 'components/header.php'; ?>
<!-- header section ends -->

<!-- add reviews section starts -->
<section class="account-form">
    <form action="" method="post">
        <h3>Post your review</h3>
        <p class="placeholder">review title <span>*</span></p>
        <input type="text" name="title" require maxlength="50" 
        placeholder="Enter review title" class="box">
        <p class="placeholder">review description</p>
        <textarea name="description" class="box" placeholder="Enter review description"
        maxlength="1000" rows="10" cols="30"></textarea>
        <p class="placeholder">review rating <span>*</span></p>
        <select name="rating" id="" require class="box">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit" value="submit review" name="submit" class="btn">
        <a href="view_post.php?get_id=<?= $get_id; ?>" class="option-btn">Go back</a>
    </form>
</section>
<!-- add reviews section ends -->


<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- customer js file link -->
<script src="js/script.js"></script>

<?php include 'components/alers.php'; ?>
</body>
</html>