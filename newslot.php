<?php
session_start();
include_once("storage/userstorage.php");
include_once("storage/cardstorage.php");
include_once("auth.php");
include_once("utils.php");

//functions
function addNewSlot($user, $user_storage){
    //updating the user
    $data = [];
    $data['username'] = $user['username'];
    $data['password'] = $user['password'];
    $data['email'] = $user['email'];
    $data['money'] = (int)($user['money'] - $user['slotprice']);
    $data['roles'] = $user['roles'];
    $data['cards'] = $user['cards'];
    $data['limit'] = $user['limit'];
    $data['slotprice'] = $user['slotprice'];
    $data['id'] = $user['id'];
    $user_storage->update($user['id'], $data);
}

function checkValidUser($auth){
    if($auth->isAdmin($auth) || !$auth->is_authenticated()){
        die("Error, please go back! <a href=\"home.php\">Home</a>");
    }    
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
checkValidUser($auth);
//check logged in user
if($auth->is_authenticated()){
    $user = $user_storage->findById($auth->authenticated_user()['id']);
}

if($user['money'] - $user['slotprice'] < 0){
    redirect("userdetails.php");
}
$user['limit'] = $user['limit'] + 1;

if($user['limit'] - count($user['cards']) < 20){
    $user['slotprice'] = 100; 
}
else if($user['limit'] - count($user['cards']) >= 30){
    $user['slotprice'] = 500;
}
else if($user['limit'] - count($user['cards']) >= 20){
    $user['slotprice'] = 250;
}
    
addNewSlot($user, $user_storage);

redirect("userdetails.php");