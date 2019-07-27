<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Meteo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use App\Service\ContainerParametersHelper;
use App\Service\MeteoService;

class MeteoController extends AbstractController
{

    private $meteo;

    public function __construct(MeteoService $meteo) 
    {
        $this->meteo = $meteo;
    }

    /**
     * @Route("/meteo", name="meteo_app")
     */
    public function getMeteo(ContainerParametersHelper $pathHelpers)
    {
        print_r($this->meteo->getMeteo("RABAT"));
        die;
    }
    
}
