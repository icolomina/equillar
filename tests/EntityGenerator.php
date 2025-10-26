<?php

namespace App\Tests;

use App\Entity\Contract\Contract;
use App\Entity\Contract\ContractBalanceMovement;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Entity\Contract\UserContract;
use App\Entity\Contract\UserContractPayment;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserWallet;
use Symfony\Component\Uid\Uuid;

class EntityGenerator
{
    public static function createIssuer(): User
    {
        $issuer = new User();
        $issuer->setCreatedAt(new \DateTimeImmutable());
        $issuer->setEmail('issuer@email.com');
        $issuer->setName('The Issuer');
        $issuer->setPassword('123654');
        $issuer->setRoles(['ROLE_COMPANY']);
        
        return $issuer;
    }

    public static function createInvestor(): User
    {
        $investor = new User();
        $investor->setCreatedAt(new \DateTimeImmutable());
        $investor->setEmail('investor@email.com');
        $investor->setName('The Investor');
        $investor->setPassword('123654');
        $investor->setRoles(['ROLE_USER']);
        
        return $investor;
    }

    public static function createToken(): Token
    {
        $token = new Token();
        $token->setName('Test Dollar');
        $token->setCode('UDST');
        $token->setAddress('XXTTGGDTTDTTDDDT');
        $token->setCreatedAt(new \DateTimeImmutable());
        $token->setDecimals(7);
        $token->setEnabled(true);
        $token->setLocale('en_US');
        $token->setReferencedCurrency('USD');
        $token->setIssuer('System');
        
        return $token;
    }   

    public static function createUserWallet(User $investor): UserWallet
    {
        $wallet = new UserWallet();
        $wallet->setAddress('TGETDBBDBD');
        $wallet->setUsr($investor);
        $wallet->setCreatedAt(new \DateTimeImmutable());
        $wallet->setNetwork('TESTNET');
        
        return $wallet;
    }

    public static function createApprovedContract(User $issuer, Token $token): Contract
    {
        $contract = new Contract();
        $contract->setApprovedAt(new \DateTimeImmutable());
        $contract->setClaimMonths(6);
        $contract->setCreatedAt(new \DateTimeImmutable());
        $contract->setDescription('A contract');
        $contract->setFundsReached(false);
        $contract->setInitialized(false);
        $contract->setFilename('xxxxx.pdf');
        $contract->setGoal(1000000);
        $contract->setIssuer($issuer);
        $contract->setMinPerInvestment(10);
        $contract->setLabel('My Contract');
        $contract->setToken($token);
        $contract->setRate(6);
        $contract->setShortDescription('A contract');
        $contract->setStatus('APPROVED');
        
        return $contract;
    }

    public static function createActiveContract(User $issuer, Token $token): Contract
    {
        $contract = new Contract();
        $contract->setAddress('xxxxxxxxxxxxxxxx');
        $contract->setApprovedAt(new \DateTimeImmutable());
        $contract->setClaimMonths(6);
        $contract->setCreatedAt(new \DateTimeImmutable());
        $contract->setDescription('A contract');
        $contract->setFundsReached(false);
        $contract->setFilename('xxxxx.pdf');
        $contract->setInitialized(true);
        $contract->setInitializedAt(new \DateTimeImmutable());
        $contract->setGoal(1000000);
        $contract->setIssuer($issuer);
        $contract->setMinPerInvestment(10);
        $contract->setLabel('My Contract');
        $contract->setRate(6);
        $contract->setReturnMonths(12);
        $contract->setReturnType(2);
        $contract->setToken($token);
        $contract->setShortDescription('A contract');
        $contract->setStatus('ACTIVE');
        
        return $contract;
    }
    

    public static function createPendingUserContract(Contract $contract, User $investor, UserWallet $wallet): UserContract
    {
        $userContract = new UserContract();
        $userContract->setContract($contract);
        $userContract->setUsr($investor);
        $userContract->setBalance(644444);
        $userContract->setHash('3gf9284g5f95fg94765gf945gf485fg');
        $userContract->setClaimableTs((new \DateTimeImmutable())->getTimestamp());
        $userContract->setStatus('PENDING');
        $userContract->setCreatedAt(new \DateTimeImmutable());
        $userContract->setUserWallet($wallet);
        
        return $userContract;
    }

    public static function createPendingUserContractPayment(UserContract $userContract): UserContractPayment
    {
        $userContractPayment = new UserContractPayment();
        $userContractPayment->setCreatedAt(new \DateTimeImmutable());
        $userContractPayment->setUserContract($userContract);
        $userContractPayment->setStatus('SENT');

        return $userContractPayment;
    }

    public static function createContractWithdrawalRequest(User $user, Contract $contract): ContractWithdrawalRequest
    {
        $contractWithdrawalRequest = new ContractWithdrawalRequest();
        $contractWithdrawalRequest->setContract($contract);
        $contractWithdrawalRequest->setConfirmUrl('https://app.confirm.com');
        $contractWithdrawalRequest->setRequestedAmount(600);
        $contractWithdrawalRequest->setRequestedAt(new \DateTimeImmutable());
        $contractWithdrawalRequest->setRequestedBy($user);
        $contractWithdrawalRequest->setStatus('WAITING');
        $contractWithdrawalRequest->setValidUntil(new \DateTimeImmutable(date('Y-m-d H:i:s', strtotime('+ 1 day'))));
        $contractWithdrawalRequest->setUuid(Uuid::v4());

        return $contractWithdrawalRequest;
    }

    public static function createContractBalanceMovement(User $user, Contract $contract): ContractBalanceMovement
    {
        $contractBalanceMovement = new ContractBalanceMovement();
        $contractBalanceMovement->setContract($contract);
        $contractBalanceMovement->setAmount(1000.50);
        $contractBalanceMovement->setSegmentFrom('available');
        $contractBalanceMovement->setSegmentTo('reserve_fund');
        $contractBalanceMovement->setCreatedAt(new \DateTimeImmutable());
        $contractBalanceMovement->setRequestedBy($user);
        $contractBalanceMovement->setStatus('CREATED');

        return $contractBalanceMovement;
    }

    public static function createContractReserveFundContribution(User $user, Contract $contract): \App\Entity\Contract\ContractReserveFundContribution
    {
        $contribution = new \App\Entity\Contract\ContractReserveFundContribution();
        $contribution->setUuid(Uuid::v4());
        $contribution->setAmount(1000.0);
        $contribution->setStatus('CREATED');
        $contribution->setSourceUser($user);
        $contribution->setContract($contract);
        $contribution->setCreatedAt(new \DateTimeImmutable());

        return $contribution;
    }
}
