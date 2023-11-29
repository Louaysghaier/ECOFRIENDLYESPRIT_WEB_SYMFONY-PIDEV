<?php

// src/Service/QrCodeService.php


namespace App\Service;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;

class QrCodeService{

    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrCode($query){

        $result = $this->builder
            ->data($query)
            ->size(400)
            ->encoding(new Encoding('UTF-8'))
            ->build()
        ;

        $namePng = uniqid(''.'').'.png';

        $result->saveToFile((\dirname(__DIR__,2).'/public/uploads/qrcode'.$namePng));
        return $result->getDataUri();
    }

}
