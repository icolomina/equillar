<?php

namespace App\Tests;

use Soneso\StellarSDK\Xdr\XdrSCMapEntry;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;

trait GenerateXdrMapEntryMock
{
    public function generateEntry(string $key, string $mockedMethod, int $xdrValType, mixed $result)
    {
        $xdrKeyMock     = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_SYMBOL)])
            ->getMock()
        ;
        $xdrKeyMock->sym = $key;

        $xdrResultType = new XdrSCValType($xdrValType);
        $xdrValMock    = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([$xdrResultType])
            ->getMock()
        ;

        $xdrValMock->method($mockedMethod)->willReturn($result);
        return $this->getMockBuilder(XdrSCMapEntry::class)
            ->setConstructorArgs([$xdrKeyMock, $xdrValMock])
            ->onlyMethods([])
            ->getMock()
        ;
    }
}
