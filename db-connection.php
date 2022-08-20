<?php
    require_once 'vendor/autoload.php';
$server_name = 'localhost';
$db_name = 'trainee_db';
$db_username = 'root';
$db_password = '';

try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $db_username, $db_password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS userdata (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR (50),
            age INT(3) UNSIGNED,
            gender CHAR(3),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    $rows = [];
    $user_data_count_stmt= $conn->prepare('SELECT COUNT(*) as user_count FROM userdata');
    $rows_count = $user_data_count_stmt->execute();
    while($row = $user_data_count_stmt->fetch(PDO::FETCH_NAMED)){
        var_dump($row);
        $rows[] = $row;
    }
    var_dump($rows[0]['user_count']);
    if(empty($rows) || $rows[0]['user_count'] === 0){
        $faker = Faker\Factory::create();
        foreach(range(1,1000) as $value){
            $stmt= $conn->prepare("
        INSERT INTO userdata (firstname, lastname, email, phone, age, gender)
        VALUES(
        :firstName,
        :lastname,
        :email,
        :phone,
        :age,
        :gender
        )
        ");
            $data = [
                'firstName' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => $faker->email,
                'phone'=> $faker->phoneNumber,
                'age'=> $faker->randomNumber(2),
                'gender'=> $faker->randomElement(['F', 'M']),
            ];
            $stmt->execute($data);
        }
    }
$rows_count = $rows[0]['user_count'];
    if ($rows_count > 0) {
        echo "userdata has $rows_count records";
    } else {
        echo 'userdata has no records';
    }
}catch (PDOException $pe){
    die( $pe->getTraceAsString());
}