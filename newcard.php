<?php
session_start();
include_once("storage/userstorage.php");
include_once("storage/cardstorage.php");    
include_once("auth.php");
include_once("utils.php");

//functions
function validate($post, &$data, &$errors, $cards) {
    //name
    if(isset($post['name'])){
        if(!preg_match('/^[A-Za-z0-9]+$/', $post['name']) && ctype_alpha($post['name'])){
            $errors['name'] = 'Name should only contain alphanumeric characters!';
        }
        else if(strlen($post['name']) < 4){
            $errors['name'] = 'The shortest pokemon name is 4 characters!';
        }
        else if(strlen($post['name']) > 12){
            $errors['name'] = 'The longest pokemon name is 12 characters! (Crabominable)';
        }
    }
    else{
        $errors['name'] = "Name is required";
    }

    //type
    if(isset($post['type'])){
        $allowedTypes = [
            "Normal", "Fire", "Water", "Grass", "Flying", "Fighting", "Poison", "Electric",
            "Ground", "Rock", "Psychic", "Ice", "Bug", "Ghost", "Steel", "Dragon", "Dark", "Fairy"
        ];
        $allowedTypesUpper = array_map('strtoupper', $allowedTypes);
        $typeToUpper = strtoupper($_POST["type"]);
        if(!in_array($typeToUpper, $allowedTypesUpper)){
            $errors['type'] = "Invalid type!"; 
        }
    }
    else{
        $errors['type'] = "Type is required!";
    }

    //hp, attack, defense, hp
    if(isset($post['hp']) || isset($post['attack']) || isset($post['defense']) || isset($post['price'])){
        $hp = (int)$post['hp'];
        $attack = (int)$post['attack'];
        $defense = (int)$post['defense'];
        $price = (int)$post['price'];
        if($hp < 1 || $attack < 1 || $defense < 1 || $price < 1){
            $errors['price'] = "Health, attack, def, price must be greater than zero!";
        }
    }
    else{
        $errors['price'] = "Health, attack, def, price are required!";
    }
    
    //description
    if(isset($post['description'])){
        $description = $post['description'];
        if(strlen($description) < 20){
            $errors['description'] = "Description min 30 characters!";
        }
    }
    else{
        $errors['description'] = "Description is required!";
    }

    //image
    if(isset($post['image'])){
        $image = $post['image'];

        // Get image size information
        if(strlen($image) !== 0){
            $imageData = @getimagesize($image);
            if ($imageData === false){
                $errors['image'] = "Not valid image URL!";
            }
        }
        else{
            $errors['image'] = "Image is required!";
        }

    }
    else{
        $errors['image'] = "Image is required!";
    }

    $data = $post;

    return count($errors) === 0;
}

//data
$card_storage = new CardStorage();
$cards = $card_storage->findAll();

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$admin = $user_storage->findById("65988d0d7c371");

$cardid = count($cards);

//make sure admin is logged in
if(!$auth->isAdmin($auth))
    die("Only admins can create new cards! Please go back! <a href=\"home.php\">Home</a>");

//check logged in user
if($auth->is_authenticated()){
    $user = $user_storage->findById($auth->authenticated_user()['id']);
}

$data = [];
$errors = [];

if (count($_POST) > 0) {
    if (validate($_POST, $data, $errors, $cards)) {
        array_push($admin['cards'], "card{$cardid}");
        $data['id'] = $cardid;
        $data['hp'] = (int)$data['hp'];
        $data['attack'] = (int)$data['attack'];
        $data['defense'] = (int)$data['defense'];
        $data['price'] = (int)$data['price'];
        $data['hasowner'] = false;
        $card_storage->addCard($data);

        //admin update
        unset($data);
        $data['username'] = $admin['username'];
        $data['password'] = $admin['password'];
        $data['email'] = $admin['email'];
        $data['money'] = $admin['money'];
        $data['roles'] = $admin['roles'];
        $data['cards'] = $admin['cards'];
        $data['id'] = $admin['id'];

        $user_storage->update($admin['id'], $data);
        redirect("home.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | New card</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/new_edit_card.css">
    <link rel="icon" type="image/x-icon" href="data:image/png;base64,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">
</head>

<body>
    <?php if($auth->is_authenticated()) : ?>
        <div class="header-user">
            <span>Username: <?= $user['username'] ?>
            <br>
            <?php if(!$auth->isAdmin($auth)) echo "Money: {$user['money']} <span class=\"icon\">💰</span>"; ?></span>
        </div>
    <?php endif; ?>
    
    <header>
        <h1>
            IKémon |
            <a href="home.php">Home</a> | 
            > New Card < |
            <a href="userdetails.php">My Cards</a>  |
            <a href="logout.php">Log Out</a>
        </h1>
    </header>

    <div id="content">
        <div id="card-list">
            <div class="pokemon-card">
                <form action="" method="post" novalidate>
                    <div class="image clr">
                        <div>
                            <button type="submit" class="submit">Create</button>
                        </div>  
                    </div>
                    <div class="details">
                        <h2>                           
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?= $_POST['name'] ?? "" ?>" onkeyup="showHint(this.value)">
                            <?php if (isset($errors['name'])) : ?>
                                <span class="error"><?= $errors['name'] ?></span>
                            <?php endif; ?>

                            <label for="description">Description</label>
                            <input type="text" id="description" name="description" value="<?= $_POST['description'] ?? "" ?>">
                            <?php if (isset($errors['description'])) : ?>
                                <span class="error"><?= $errors['description'] ?></span>
                            <?php endif; ?>

                            <label for="image">Image</label>
                            <input type="text" id="image" name="image" value="<?= $_POST['image'] ?? "" ?>">
                            <?php if (isset($errors['image'])) : ?>
                                <span class="error"><?= $errors['image'] ?></span>
                            <?php endif; ?>
                        </h2>
                        <span class="card-type"><span class="icon">🏷</span>
                            <label for="type">Type</label>
                            <select name="type" style="background-color:var(--clr-main); color:var(--clr-white);">
                                <option value="">All types</option>
                                <?php
                                    $allowedTypes = [
                                        "Normal", "Fire", "Water", "Grass", "Flying", "Fighting", "Poison", "Electric",
                                        "Ground", "Rock", "Psychic", "Ice", "Bug", "Ghost", "Steel", "Dragon", "Dark", "Fairy"
                                    ];

                                    // Loop through the types to create options
                                    foreach ($allowedTypes as $type) {
                                        echo "<option value='" . strtolower($type) . "'>$type</option>";
                                    }
                                ?>
                            </select>
                        </span>
                        <?php if (isset($errors['type'])) : ?>
                            <span class="error"><?= $errors['type'] ?></span>
                        <?php endif; ?>

                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> 
                                <label for="hp">Health</label>
                                <input type="number" id="hp" name="hp" value="<?= $_POST['hp'] ?? "" ?>">
                            </span>

                            <span class="card-attack"><span class="icon">⚔</span> 
                                <label for="attack">Attack</label>
                                <input type="number" id="attack" name="attack" value="<?= $_POST['attack'] ?? "" ?>">
                            </span><br>

                            <span class="card-defense"><span class="icon">🛡</span> 
                                <label for="defense">Defense</label>
                                <input type="number" id="defense" name="defense" value="<?= $_POST['defense'] ?? "" ?>">
                            </span>
                        </span>
                    </div>
                    <div class="edit">
                        <span class="card-price"><span class="icon">💰</span> 
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" value="<?= $_POST['price'] ?? "" ?>">
                        </span>
                        <?php if (isset($errors['price'])) : ?>
                            <span class="error"><?= $errors['price'] ?></span>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            </a>
        </div>
    </div>
    
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>