<?php
namespace Controller\Tool;

use Keratine\Controller\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class FinderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $this->container = $app;

        $controllers = $app['controllers_factory'];

        $controllers->get('/', array($this, 'finder'))
            ->bind('finder');

        $controllers->get('/connector', array($this, 'connector'))
            ->bind('finder_connector');

        return $controllers;
    }

    public function finder()
    {
        $path = $this->get('request')->get('path');
        $onlyMimes = $this->get('request')->get('onlyMimes');
        $ui = $this->get('request')->get('ui');
        $uiOptions = $this->get('request')->get('uiOptions');

        return $this->get('twig')->render('tool/finder.html.twig', array(
            'path'      => $path,
            'onlyMimes' => $onlyMimes,
            'ui'        => $ui,
            'uiOptions' => $uiOptions,
        ));
    }

    public function connector()
    {
        include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinderConnector.class.php';
        include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinder.class.php';
        include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinderVolumeDriver.class.php';
        include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinderVolumeLocalFileSystem.class.php';
        // Required for MySQL storage connector
        // include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinderVolumeMySQL.class.php';
        // Required for FTP connector support
        // include_once __DIR__.'/../../../js/vendor/elfinder/php/elFinderVolumeFTP.class.php';

        // path to files
        $path = dirname($this->get('request')->server->get('SCRIPT_FILENAME')) .'/uploads/'. trim($this->get('request')->get('path'), '/');

        // URL to files
        $url = $this->get('request')->getBaseURL().'/uploads/' . trim($this->get('request')->get('path'), '/');

        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',     // driver for accessing file system (REQUIRED)
                    'path'          => $path,                 // path to files (REQUIRED)
                    'URL'           => $url,                  // URL to files (REQUIRED)
                    'accessControl' => array($this, 'access') // disable and hide dot starting files (OPTIONAL)
                )
            )
        );

        // run elFinder
        $connector = new \elFinderConnector(new \elFinder($opts));
        $connector->run();
    }

     /**
     * Simple function to demonstrate how to control file access using "accessControl" callback.
     * This method will disable accessing files/folders starting from  '.' (dot)
     *
     * @param  string  $attr  attribute name (read|write|locked|hidden)
     * @param  string  $path  file path relative to volume root directory started with directory separator
     * @return bool|null
     **/
    public static function access($attr, $path, $data, $volume) {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            :  null;                                    // else elFinder decide it itself
    }
}