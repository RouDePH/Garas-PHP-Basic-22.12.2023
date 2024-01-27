<?php const APP_DIR = __DIR__ . "/";

require_once APP_DIR . "classes/DebitAccount.php";
require_once APP_DIR . "classes/StringFormatter.php";

try {
//    $errorAccount = new DebitAccount(-200); // Error
    $account1 = new DebitAccount(200);
    $account2 = new DebitAccount(1000);

    $account1->p2p($account2, 200); // Success operation
//    $account1->p2p($account2, 201);       // Error

    $account2->withdraw(1200); // Success operation
//    $account2->withdraw(1201);       // Error

    echo "Account [" . $account2->getAccountNumber() . "] deposit: " . StringFormatter::toFixed($account2->getBalance(), 2) . " UAH" . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}