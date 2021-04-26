<?php

include('. /logic/procedures/utility.inc.php');


class comments{
    private $commentId;
    private $readerId;
    private $comment;
    private $articleId;
    private $created_at;


    // Function to convert DATETIME TO TIME ELAPSED string
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    // Write comment form
    function show_write_comment_form($commentId = -1)
    {
        $html = '
    <div class="write_comment" data-comment-id="' . $commentId . '">
        <form>
            <input name="commentId" type="hidden" value="' . $commentId . '">
            <input name="readerId" type="hidden" value="">
            <textarea name="content" placeholder="Write your comment here..." required></textarea>
            <button type="submit">Submit Comment</button>
        </form>
    </div>
    ';
        return $html;
    }

    // Function  populates the comments and comments replies using a loop
    function show_comments($comments, $commentId = -1)
    {
        $html = '';
        if ($commentId != -1) {
            // If comments are replies sort them by the "created_at" date column
            array_multisort(array_column($comments, 'created_at'), SORT_ASC, $comments);
        }

        // Iterates the comments using the foreach loop
        foreach ($comments as $comment) {
            if ($comment['commentId'] == $commentId) {
                // Add the comment to the $html variable
                $html .= '
            <div class="comment">
                <div>
                    <h3 class="readerId">' . htmlspecialchars($comment['readerId'], ENT_QUOTES) . '</h3>
                    <span class="date">' . time_elapsed_string($comment['created_at']) . '</span>
                </div>
                <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>
                <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Reply</a>
                ' . show_write_comment_form($comment['id']) . '
                <div class="replies">
                ' . show_comments($comments, $comment['id']) . '
                </div>
            </div>
            ';
            }
        }
        return $html;
    }

}

// Article ID needs to exist, this is used to determine which comments are for which article
if (isset($_GET['articleId'])) {
    // Check if the submitted form variables exist
    if (isset($_POST['readerId'], $_POST['content'])) {
        // POST variables exist, insert a new comment into the MySQL comments table (user submitted form)
        $stmt = $pdo->prepare('INSERT INTO comments (articleId, commentId, readerId, comment, created_at) VALUES (?,?,?,?,NOW())');
        $stmt->execute([$_GET['articleId'], $_POST['commentId'], $_POST['readerId'], $_POST['content']]);
        exit('Your comment has been submitted!');
    }

    // Get all comments by the Article ID ordered by the submit date
    $stmt = $pdo->prepare('SELECT * FROM comments WHERE articleId = ? ORDER BY created_at DESC');
    $stmt->execute([$_GET['articleId']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the total number of comments
    $stmt = $pdo->prepare('SELECT COUNT(*) AS total_comments FROM comments WHERE articleId = ?');
    $stmt->execute([$_GET['articleId']]);
    $comments_info = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    exit('No article ID specified!');
}


/*
//GETTING COMMENTS COUNT
<?=$comments_info['total_comments']?>

//SHOWING THE COMMMENT FORM
<?=show_write_comment_form()?>

//SHOW COMMENTS
<?=show_comments($comments)?>
*/

?>

