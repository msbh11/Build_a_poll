<?php 

require_once 'app/init.php';

if(isset($_POST['poll'], $_POST['choice'])) {

    $poll = $_POST['poll'];
    $choice = $_POST['choice'];

    

    $voteQuery = $db->prepare("
        INSERT INTO polls_answers (user, poll, choice)
            SELECT :user, :poll, :choice
            FROM polls 
            WHERE EXISTS (
                SELECT id
                FROM polls
                WHERE id = :poll
                AND DATE(NOW())  BETWEEN starts AND ends)
            AND EXISTS (
                SELECT id
                FROM polls_choices
                WHERE id = :choice
                AND poll = :poll)    
            AND NOT EXISTS (
                SELECT id
                FROM polls_answers
                WHERE user = :user
                AND poll = :poll)
            LIMIT 1      
    
    ");

    $voteQuery->execute([

        'user' => $_SESSION['user_id'],
        'poll' => $poll ,
        'choice' => $choice

    ]);

    header('Location: poll.php?poll=' .$poll);
}
header('Location: index.php');

?>