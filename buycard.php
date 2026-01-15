<?php
session_start();
include_once("storage/userstorage.php");
include_once("storage/cardstorage.php");
include_once("auth.php");
include_once("utils.php");

//functions
function removeFromAdmin($admin, $card, $user_storage){
    $index = 0;
    foreach($admin['cards'] as $c){
        if($c === "card{$card['id']}"){
            array_splice($admin['cards'], $index, 1);
            break;
        }
        $index++;
    }

    $data = [];
    $data['username'] = $admin['username'];
    $data['password'] = $admin['password'];
    $data['email'] = $admin['email'];
    $data['money'] = $admin['money'];
    $data['roles'] = $admin['roles'];
    $data['cards'] = $admin['cards'];
    $data['id'] = $admin['id'];

    $user_storage->update($admin['id'], $data);
}

function addToUser($user, $card, $user_storage, $card_storage){
    array_push($user['cards'], "card{$card['id']}");

    //updating the user
    $data = [];
    $data['username'] = $user['username'];
    $data['password'] = $user['password'];
    $data['email'] = $user['email'];
    $data['money'] = (int)$user['money'] - (int)$card['price'];
    $data['roles'] = $user['roles'];
    $data['cards'] = $user['cards'];
    $data['limit'] = $user['limit'];
    $data['slotprice'] = $user['slotprice'];
    $data['id'] = $user['id'];
    $user_storage->update($user['id'], $data);
    
    unset($data);
    
    //updating the card
    $data['name'] = $card['name'];
    $data['description'] = $card['description'];
    $data['image'] = $card['image'];
    $data['type'] = $card['type'];
    $data['hp'] = $card['hp'];
    $data['attack'] = $card['attack'];
    $data['defense'] = $card['defense'];
    $data['price'] = $card['price'];
    $data['id'] = $card['id'];
    $data['hasowner'] = true;
    $card_storage->update("card{$card['id']}", $data);
}

function checkValidUser($auth){
    if($auth->isAdmin($auth) || !$auth->is_authenticated() || !isset($_GET['id'])){
        die("Error, please go back! <a href=\"home.php\">Home</a>");
    }    
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
checkValidUser($auth);

$card_storage = new CardStorage();
$card = $card_storage->findById($_GET['id']);

$admin = $user_storage->findById("65988d0d7c371");

//check logged in user
if($auth->is_authenticated()){
    $user = $user_storage->findById($auth->authenticated_user()['id']);
}

//check if user has enough money
if($user['money'] - $card['price'] < 0){
    redirect("home.php");
    return;
}
removeFromAdmin($admin, $card, $user_storage);
addToUser($user, $card, $user_storage, $card_storage);

redirect("home.php#{$card['id']}");