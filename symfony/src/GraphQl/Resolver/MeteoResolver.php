<?php
namespace App\GraphQl\Resolver;

use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Doctrine\ORM\EntityManager;
use App\Service\MeteoService;

class MeteoResolver implements ResolverInterface, AliasedInterface {

    /**
     * @var EntityManager
     */
    private $meteo;

    public function __construct(MeteoService $meteo)
    {
        $this->meteo = $meteo;
    }

    public function resolve(Argument $argument)
    {
        $meteo = $this->meteo->getMeteo($argument['ville']);
        return $meteo; 
    }

    public static function getAliases() : array
    {
        return [
            'resolve' => 'Meteo'
        ];
    }
}