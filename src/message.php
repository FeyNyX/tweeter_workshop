<!--
CREATE TABLE Messages(
message_id INT AUTO_INCREMENT,
text VARCHAR(600) NOT NULL,
creation_date DATETIME,
is_read INT,
sender_id INT,
receiver_id INT,
PRIMARY KEY(message_id),
FOREIGN KEY(sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
FOREIGN KEY(receiver_id) REFERENCES Users(user_id) ON DELETE CASCADE
)
-->
<?php
class Message
{
    static private $conn;

    private $messageId;
    private $text;
    private $creationDate;
    private $isRead;
    private $senderId;
    private $receiverId;

    public static function setConnection(mysqli $newConnection)
    {
        self::$conn = $newConnection;
    }

    public function __construct($newId, $newText, $newDate, $newIsRead, $newSenderId, $newReceiverId)
    {
        $this->messageId = $newId;
        $this->setText($newText);
        $this->creationDate = $newDate;
        $this->isRead = $newIsRead;
        $this->senderId = $newSenderId;
        $this->receiverId = $newReceiverId;
    }

    public static function createMessage($senderId, $receiverId, $text){
        if(is_string($text) && strlen($text) <= 600){
            $sql = "INSERT INTO Messages(text, creation_date, is_read, sender_id, receiver_id) VALUES ('$text', NOW(), 0, $senderId, $receiverId)";
            $result = self::$conn->query($sql);
            if($result == true){
                $myMessage = new Message(self::$conn->insert_id, $text, date("Y-m-d H:i:s"), 0 ,$senderId, $receiverId);
                return $myMessage;
            }
        }
        return false;
    }

    public static function loadAllMessagesAsSender($senderId){
        $sql = "SELECT * FROM Messages WHERE sender_id = {$senderId} ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if($result == true){
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $loadedMessage = new Message($row['message_id'],
                        $row['text'],
                        $row['creation_date'],
                        $row['is_read'],
                        $row['sender_id'],
                        $row['receiver_id']);
                    $ret[] = $loadedMessage;
                }
                return $ret;
            }
        }
    }

    public static function loadAllMessagesAsReceiver($receiverId){
        $sql = "SELECT * FROM Messages WHERE receiver_id = {$receiverId} ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);
        if($result == true){
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $loadedMessage = new Message($row['message_id'],
                        $row['text'],
                        $row['creation_date'],
                        $row['is_read'],
                        $row['sender_id'],
                        $row['receiver_id']);
                    $ret[] = $loadedMessage;
                }
                return $ret;
            }
        }
    }

    static public function getMessageById($id){
        $sql = "SELECT * FROM Messages WHERE message_id = {$id}";
        $result = self::$conn->query($sql);
        if($result == true){
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $wantedMessage = new Message($row['message_id'],
                    $row['text'],
                    $row['creation_date'],
                    $row['is_read'],
                    $row['sender_id'],
                    $row['receiver_id']);
                return $wantedMessage;
            }
        }
        return false;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }
    public function getText()
    {
        return $this->text;
    }
    public function setText($text)
    {
        if(is_string($text) && strlen($text) <= 600){
            $this->text = $text;
        }
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function getIsRead()
    {
        return $this->isRead;
    }
    public function setIsRead()
    {
        $this->isRead = 1;
    }
    public function getSenderId()
    {
        return $this->senderId;
    }
    public function getReceiverId()
    {
        return $this->receiverId;
    }
}

