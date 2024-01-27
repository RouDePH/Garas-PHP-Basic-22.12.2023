<?php require_once APP_DIR . "classes/IBankAccount.php";

class DebitAccount implements IBankAccount
{
    private string $accountNumber;
    private int $balance;

    /**
     * @throws Exception
     */
    public function __construct(int $initialBalance, ?string $accountNumber = null)
    {
        $this->setAccountNumber($accountNumber ?? uniqid());
        $this->setBalance($initialBalance);
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @throws Exception
     */
    private function setBalance(int $balance): void
    {
        if ($balance < 0) throw new Exception("The debit account [" . $this->getAccountNumber() . "] cannot use credit funds");
        $this->balance = $balance;
    }

    private function setAccountNumber(string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @throws Exception
     */
    public function deposit($amount): void
    {
        if ($amount > 0) {
            $this->setBalance($this->getBalance() + $amount);
        } else
            throw new Exception("The deposit amount must be greater than zero");
    }

    /**
     * @throws Exception
     */
    public function withdraw($amount): void
    {
        $balance = $this->getBalance();
        if ($amount > 0 && $amount <= $balance) {
            $this->setBalance($balance - $amount);
        } else
            throw new Exception("The withdrawal amount must not exceed the account deposit");
    }

    /**
     * @throws Exception
     */
    public function p2p(IBankAccount $recipient, int $amount): void
    {
        $balance = $this->getBalance();
        if ($amount > 0 && $amount <= $balance) {
            $this->setBalance($balance - $amount);
            $recipient->deposit($amount);
        } else
            throw new Exception("The p2p amount must not exceed the account deposit");
    }
}