<?php
function AllPublishedPosts($pdo) {
    $stmt = $pdo->prepare("SELECT blog.*, users.username FROM blog JOIN users ON blog.author_id = users.user_id WHERE blog.status='published' ORDER BY blog.id ASC;");
    $stmt->execute();

    return $stmt->fetchAll();
}

function UserPosts($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT blog.*, users.username FROM blog JOIN users ON blog.author_id = users.user_id WHERE blog.author_id = :id ORDER BY blog.id ASC;");
    $stmt->execute(['id' => $user_id]);

    return $stmt->fetchAll();
}

function searchPosts($pdo, $filter_query, $params) {
    $query = "SELECT blog.*, users.username FROM blog JOIN users ON blog.author_id = users.user_id" . $filter_query . " AND
                 blog.status='published' ORDER BY blog.id ASC LIMIT :offset, :records_per_page";
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function updatePostViews($pdo, $post_id) {
    $stmt = $pdo->prepare("UPDATE blog SET views = views + 1 WHERE id = :id");

    return $stmt->execute(['id' => $post_id]);
}

function getPostById($pdo, $post_id) {
    $stmt = $pdo->prepare("SELECT blog.*, users.username, blog.author_id FROM blog JOIN users ON blog.author_id = users.user_id WHERE blog.id = :id");
    $stmt->execute(['id' => $post_id]);

    return $stmt->fetch();
}

function updatePostComments($pdo, $post_id, $comments) {
    $stmt = $pdo->prepare("UPDATE blog SET comment = :comment WHERE id = :id");

    return $stmt->execute(['id' => $post_id, 'comment' => $comments]);
}

function updatePost($pdo, $title, $text, $status, $post_id, $user_id) {
    $stmt = $pdo->prepare("UPDATE blog SET title = :title, text = :text, updated_at = NOW(), status= :status WHERE id = :id AND author_id = :author_id");

    return $stmt->execute(['title' => $title, 'text' => $text, 'status' => $status, 'id' => $post_id, 'author_id' => $user_id]);
}

function deletePost($pdo, $post_id) {
    $stmt = $pdo->prepare("DELETE FROM blog WHERE id = :id");

    return $stmt->execute(['id' => $post_id]);
}

function createPost($pdo, $title, $text, $author_id, $status) {
    $stmt = $pdo->prepare("INSERT INTO blog (title, text, author_id, created_at, updated_at, views, comment, status) 
                           VALUES (:title, :text, :author_id, NOW(), NULL, 0, '', :status)");
                           
    return $stmt->execute(['title' => $title, 'text' => $text, 'author_id' => $author_id, 'status' => $status]);
}
?>
