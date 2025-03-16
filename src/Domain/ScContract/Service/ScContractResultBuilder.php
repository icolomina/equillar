<?php

namespace App\Domain\ScContract\Service;

use App\Blockchain\Stellar\Exception\Transaction\ContractCallFunctionResultException;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrSCError;
use Soneso\StellarSDK\Xdr\XdrSCMapEntry;
use Soneso\StellarSDK\Xdr\XdrSCValType;

class ScContractResultBuilder
{
    public function getResultData(GetTransactionResponse $transactionResponse): mixed
    {
        $xdrResult = $transactionResponse->getResultValue();
        
        return match($xdrResult->type->value) {
            XdrSCValType::SCV_VOID => null,
            XdrSCValType::SCV_BOOL => $xdrResult->getB(),
            XdrSCValType::SCV_ERROR => $this->processFunctionCallError($xdrResult->getError(), $transactionResponse->getTxHash()),
            XdrSCValType::SCV_I128 => $xdrResult->getI128(),
            XdrSCValType::SCV_MAP => $this->generateForMap($xdrResult->getMap()),
            XdrSCValType::SCV_U32 => $xdrResult->getU32(),
            XdrSCValType::SCV_STRING => $xdrResult->getStr(),
            default => $xdrResult->encode()
        };
    }

    private function processFunctionCallError(XdrSCError $xdrSCError, string $trxHash): never
    {
        throw new ContractCallFunctionResultException($xdrSCError->getCode()->getValue(), $xdrSCError->getType()->getValue(), $trxHash);
    }

    /**
     * @param XdrSCMapEntry[] $map
     */
    private function generateForMap(array $map) : array 
    {
        $entryMap = [];
        foreach($map as $entry) {

            $value = match($entry->val->type->value) {
                XdrSCValType::SCV_I128 => $entry->val->getI128(),
                XdrSCValType::SCV_U64  => $entry->val->getU64(),
                XdrSCValType::SCV_U32  => $entry->val->getU32(),
                XdrSCValType::SCV_STRING => $entry->val->getStr(),
                default => null
            };

            $entryMap[$entry->key->sym] = $value;
        } 

        return $entryMap;
    }
}
