<?php
namespace Barryvdh\Elfinder;

use Config;
use View;

class ElfinderController extends \BaseController
{
    protected $package = 'laravel-elfinder';

    public function showIndex()
    {
        $dir = 'packages/barryvdh/' . $this->package;
        $locale = Config::get('app.locale');
        if (!file_exists(public_path() . "/$dir/js/i18n/elfinder.$locale.js"))
        {
            $locale = false;
        }
        return View::make($this->package . '::elfinder')->with(compact('dir', 'locale'));
    }

    public function getViewer(){
        ?>
        <iframe src="http://docs.google.com/viewer?url=<?php
        // set current session_id for feature Google GET request
        //echo rawurlencode("http://".$_SERVER['HTTP_HOST']."/elfinder/headviewer?PHPSESSID=".session_id());
        echo rawurlencode($_REQUEST['url']);
        ?>&embedded=true" width="100%" height="100%" style="border: none;"></iframe><?php
    }

    public function getHeadviewer(){
        set_time_limit(-1);
        session_id($_GET['PHPSESSID']); // we use $_GET, not COOKIE
        session_start();
        header('Content-type: application/pdf');
        readfile($_SESSION['url']);
        return "";
    }

    public function showTinyMCE()
    {
        $dir = 'packages/barryvdh/' . $this->package;
        $locale = Config::get('app.locale');
        if (!file_exists(public_path() . "/$dir/js/i18n/elfinder.$locale.js"))
        {
            $locale = false;
        }
        return View::make($this->package . '::tinymce')->with(compact('dir', 'locale'));
    }

    public function showTinyMCE4()
    {
        $dir = 'packages/barryvdh/' . $this->package;
        $locale = Config::get('app.locale');
        if (!file_exists(public_path() . "/$dir/js/i18n/elfinder.$locale.js"))
        {
            $locale = false;
        }
        return View::make($this->package . '::tinymce4')->with(compact('dir', 'locale'));
    }

    public function showCKeditor4()
    {
        $dir = 'packages/barryvdh/' . $this->package;
        $locale = Config::get('app.locale');
        if (!file_exists(public_path() . "/$dir/js/i18n/elfinder.$locale.js"))
        {
            $locale = false;
        }
        return View::make($this->package . '::ckeditor4')->with(compact('dir', 'locale'));
    }

    public function showConnector()
    {
        $dir = Config::get($this->package . '::dir');
        $roots = Config::get($this->package . '::roots');

        if (!$roots)
        {
            $roots = array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path() . DIRECTORY_SEPARATOR . $dir, // path to files (REQUIRED)
                    'URL' => asset($dir), // URL to files (REQUIRED)
                    'accessControl' => Config::get($this->package . '::access') // filter callback (OPTIONAL)
                ),
                array(
                    'driver' => 'Dropbox', // driver for accessing file system (REQUIRED)
                    'path' => '/',// public_path() . DIRECTORY_SEPARATOR . $dir, // path to files (REQUIRED)
                )
            );
        }

        // Documentation for connector options:
        // https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
        $opts = array(
            'roots' => $roots
        );

        // run elFinder
        $connector = new \elFinderConnector(new \elFinder($opts));
        $connector->run();
    }
}
