<?php 

function getProfileImageUrl(?int $userId): string {
    return file_exists('../img/profiles/' . $userId . '.jpg')
      ? '../img/profiles/' . $userId . '.jpg'
      : '../img/profiles/default.jpg';
}

?>