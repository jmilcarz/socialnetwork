<?php

class Post {

     # privacy = 1 => private
     # privacy = 2 => public

     public function createPost($body, $creator, $privacy) {
          if ($creator == Auth::loggedin()) {
               if (strlen($body) >= 5 && strlen($body) <= 256) {
                    if ($privacy == "1" || $privacy == "2") {
                         DB::query('INSERT INTO posts VALUES (\'\', :body, NOW(), :userid, :privacy, 0, 0)', [':body'=>$body, ':userid'=>$creator, ':privacy'=>$privacy]);

                         if ($privacy == "1") {
                              Auth::$error = "Pomyślnie opublikowano prywatny post!";
                         }else if ($privacy == "2") {
                              Auth::$error = "Pomyślnie opublikowano publiczny post!";
                         }

                    }
               }else {Auth::$error = "Post może mieć (min: 5 znaków, a max: 256 znaków)!";}
          }else {Auth::$error = "Bład przy tworzeniu posta!";}
     }

     public function displayPostsOnProfile($userid) {
          if (Auth::loggedin()) {
               if ($userid == Auth::loggedin()) {
                    $posts = DB::query('SELECT * FROM posts, users WHERE posts_userid=:puserid AND user_user_id=:userid ORDER BY posts_id DESC', [':puserid'=>$userid, ':userid'=>$userid]);
                    foreach ($posts as $post) {
                         echo $post['user_full_name'] . " ~ ";
                         echo $post['posts_body'] . " (" . $post['posts_date'] . ") " . "<br><hr>";
                    }
               }else {
                    $posts = DB::query('SELECT * FROM posts, users WHERE posts_userid=:puserid AND user_user_id=:userid AND posts_privacy=2 ORDER BY posts_id DESC', [':puserid'=>$userid, ':userid'=>$userid]);
                    foreach ($posts as $post) {
                         echo $post['user_full_name'] . " ~ ";
                         echo $post['posts_body'] . "<br><hr>";
                    }
               }
          }

     }

}