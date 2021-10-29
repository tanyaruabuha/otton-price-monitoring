<?php

namespace App\Http\Controllers\Parser;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;

class ParserController extends Controller
{
    const SMASH_INN = 'SmashInn';
    const TENNIS_WAREHOUSE_EUROPE = 'TennisWarehouseEurope';
    const STRINGERS_WORLD = 'StringersWorld';
    const TENNIS_POINT = 'TennisPoint';
    const TENNIS_NUTS = 'TennisNuts';
    const APOLLOLE = 'Apollole';
    const PCF_FRAMEWOR = 'Framework';
    const PCF_TWUSA = 'Twusa';

    private $PCF_SMASHINN;
    private $PCF_TENNISWA;
    private $PCF_STRINGER;
    private $PCF_TENNISPO;
    private $PCF_TENNISNU;
    private $PCF_APOLLOLE;
    private $PCF_FRAMEWOR;
    private $PCF_TWUSA;

    public function __construct($competitorsLinks)
    {
        $this->PCF_SMASHINN = $competitorsLinks->PCF_SMASHINN ?? null;
        $this->PCF_TENNISWA = $competitorsLinks->PCF_TENNISWA ?? null;
        $this->PCF_STRINGER = $competitorsLinks->PCF_STRINGER ?? null;
        $this->PCF_TENNISNU = $competitorsLinks->PCF_TENNISNU ?? null;
        $this->PCF_TENNISPO = $competitorsLinks->PCF_TENNISPO ?? null;
        $this->PCF_APOLLOLE = $competitorsLinks->PCF_APOLLOLE ?? null;
        $this->PCF_FRAMEWOR = $competitorsLinks->PCF_FRAMEWOR ?? null;
        $this->PCF_TWUSA = $competitorsLinks->PCF_TWUSA ?? null;
    }

    /**
     * Main class function, that receive competitors name and returns parsed value
     * @param $parserName
     * @return array|bool|mixed|string|null
     * @throws \ErrorException
     */
    public function getValue($parserName)
    {
        try {
            switch ($parserName) {
                case self::SMASH_INN:
                    return $this->parseSmashInn();
                    break;
                case self::TENNIS_WAREHOUSE_EUROPE:
                    return $this->parseTennisWarehouseEurope();
                case self::STRINGERS_WORLD:
                    return $this->parseStringersWorld();
                    break;
                case self::TENNIS_POINT:
                    return $this->parseTennisPoint();
                    break;
                case self::TENNIS_NUTS:
                    return $this->parseTennisNuts();
                    break;
                case self::APOLLOLE:
                    return $this->parseApollole();
                    break;
                case self::PCF_FRAMEWOR:
                    return $this->parseFramework();
                    break;
                case self::PCF_TWUSA:
                    return $this->parseTwusa();
                    break;
            }
        } catch (ClientException $exception) {
            if ($exception->getCode() == 404) {
                return "Not found";
            } else {
                throw $exception;
            }
        } catch (\ErrorException $exception) {
            if (strpos($exception->getMessage(), "403 Forbidden")) {
                return "Access denied";
            } elseif (strpos($exception->getMessage(), "404 Not Found")) {
                return "Not found";
            } else {
                throw $exception;
            }
        }
    }

    private function parseSmashInn()
    {
        $parser = new SmashInnParser();
        return $parser->parse($this->PCF_SMASHINN);
    }

    private function parseTennisWarehouseEurope()
    {
        $parser = new TennisWarehouseEuropeParser();
        return $parser->parse($this->PCF_TENNISWA);
    }

    private function parseStringersWorld()
    {
        $parser = new StringersWorldParser();
        return $parser->parse($this->PCF_STRINGER);
    }

    private function parseTennisPoint()
    {
        $parser = new TennisPointParser();
        return $parser->parse($this->PCF_TENNISPO);
    }

    private function parseTennisNuts()
    {
        $parser = new TennisNutsParser();
        return $parser->parse($this->PCF_TENNISNU);
    }

    private function parseApollole()
    {
        $parser = new ApolloleParser();
        return $parser->parse($this->PCF_APOLLOLE);
    }


    private function parseFramework()
    {
        $parser = new FrameworkParser();
        return $parser->parse($this->PCF_FRAMEWOR);
    }

    private function parseTwusa()
    {
        $parser = new TwusaParser();
        return $parser->parse($this->PCF_TWUSA);
    }
}
