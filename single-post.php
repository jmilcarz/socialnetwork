<?php
require_once('./app/autoload.php');

$postid = $_GET['p'];

if (!DB::query('SELECT * FROM posts, users WHERE posts_id=:postid AND user_user_id=posts_userid AND posts_privacy=2', [':postid'=>$postid])){
     require_once('app/modules/404.html');
     exit();
}

$post = DB::query('SELECT * FROM posts, users WHERE posts_id=:postid AND user_user_id=posts_userid AND posts_privacy=2', [':postid'=>$postid])[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <script src="../assets/js/jquery.js"></script>
     <title><?= substr($post['posts_body'], 0, 52) . "..."; ?></title>
</head>
<body id="single-post-container">
     <a href="<?= $_SERVER['HTTP_REFERER']; ?>"><– wróć</a>
     <div>
          <h2><?= $post['user_full_name']; ?></h2>
          <p><?= $post['posts_date']; ?></p>
          <hr>
          <?= $post['posts_body']; ?>
          <hr>
          <p>likes: <b><?= $post['posts_likes']; ?></b></p>
          <p>comments: <b><?= $post['posts_comments']; ?></b></p>
          <hr><hr>
     </div>
     <?php if (Auth::loggedin()) { ?>
               <?= Auth::$error; ?>
               <div class="form">
                    <textarea name="body" rows="4" cols="50" id="commentbody"></textarea>
                    <input type="hidden" name="postid" value="<?= $post['posts_id']; ?>" id="postid">
                    <input type="submit" value="skomentuj" name="createComment" id="createComment">
               </div>
               <hr>
               <script>
               $("#createComment").click(function() {
                    var commentBody = $("#commentbody").val();
                    var postId = $("#postid").val();
                    $.post("../app/api/index.php", {
                         commentBody: commentBody,
                         postid: postId
                    }, function(data, status) {
                         $("#single-post-container").html(data);
                         $("#single-post-container").reset();
                    });
               });
               </script>
     <?php }?>
     <div id="comment-container"><?php Comment::displayComments($post['posts_id']); ?></div>

</body>
</html>
