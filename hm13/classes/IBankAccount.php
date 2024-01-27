<?php

interface  IBankAccount
{
    public function getAccountNumber(): string;

    public function getBalance(): int;

    public function deposit($amount): void;

    public function withdraw($amount): void;

    public function p2p(IBankAccount $recipient, int $amount): void;
}
