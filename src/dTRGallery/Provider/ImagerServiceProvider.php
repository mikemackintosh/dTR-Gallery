<?php

namespace dTRGallery\Provider;

use Bakery\Application;
use Bakery\Interfaces\ServiceProviderInterface;
use dTRGallery\Utilities\Imager;

class ImagerServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the Util\Interface class on the Application ServiceProvider
     *
     * @param Application $app Silex Application
     */
    public function init(Application $app)
    {
        $app['imager'] = new Imager();
        
    }

}