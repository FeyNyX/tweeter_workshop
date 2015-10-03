<!--
CREATE TABLE Comments(
comment_id INT AUTO_INCREMENT,
text VARCHAR(60) NOT NULL,
creation_date DATETIME,
user_id INT,
tweet_id INT,
PRIMARY KEY(comment_id),
FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
FOREIGN KEY(tweet_id) REFERENCES Tweets(tweet_id) ON DELETE CASCADE
)
-->
<?php
class Comment{
    static private $conn;

    private $commentId;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    public static function setConnection(mysqli $newConnection){
        self::$conn = $newConnection;
    }

    public function __construct($newId, $newText, $newDate, $newUserId, $newTweetId){
        $this->id = $newId;
        $this->userId = $newUserId;
        $this->tweetId = $newTweetId;
        $this->setText($newText);
        $this->creationDate = $newDate;
    }

    public static function createComment($userId, $tweetId, $text){
        if(is_string($text) && strlen($text) <= 60){
            $sql = "INSERT INTO Comments(text, creation_date, user_id, tweet_id) VALUES ('$text', NOW(), $userId, $tweetId)";
            $result = self::$conn->query($sql);
            if($result == true){
                $myComment = new Comment(self::$conn->insert_id, $text, date("Y-m-d H:i:s"), $userId, $tweetId);
                return $myComment;
            }
        }
        return false;
    }

    public static function loadAllComments(){
        $sql = "SELECT * FROM Comments ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if($result == true){
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $loadedComment = new Comment($row['comment_id'],
                        $row['text'],
                        $row['creation_date'],
                        $row['user_id'],
                        $row['tweet_id']);
                    $ret[] = $loadedComment;
                }
                return $ret;
            }
        }
    }

    static public function getCommentById($id){
        $sql = "SELECT * FROM Comments WHERE comment_id = {$id}";
        $result = self::$conn->query($sql);
        if($result == true){
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $wantedComment = new Comment ($row['comment_id'], $row['user_id'], $row['tweet_id'], $row['text'], $row['creation_date']);
                return $wantedComment;
            }
        }
        return false;
    }

    public function getCommentId()
    {
        return $this->commentId;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function getTweetId()
    {
        return $this->tweetId;
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function getText()
    {
        return $this->text;
    }
    public function setText($text)
    {
        if(is_string($text) && strlen($text) <= 60){
            $this->text = $text;
        }
    }


}