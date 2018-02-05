<?php

namespace Eyewitness\Eye\Tools;

use Exception;
use Illuminate\Support\Facades\Blade;

class BladeDirectives
{
    /**
     * Register new Blade functions.
     *
     * @return void
     */
    public function load()
    {
        $this->registerAssetDirectives();
        $this->registerNotificationDirectives();
        $this->registerSvgDirectives();
        $this->registerImageDirectives();
    }

    /**
     * Register asset directives.
     *
     * @return void
     */
    public function registerAssetDirectives()
    {
        Blade::directive('eyewitness_css', function() {
            return "<link rel=\"stylesheet\" type=\"text/css\" href=\"<?php echo route('eyewitness.asset.css', filemtime('".__DIR__."/../../resources/assets/compiled/eyewitness.css')); ?>\">";
        });

        Blade::directive('eyewitness_js', function() {
            return "<script src=\"<?php echo route('eyewitness.asset.js', filemtime('".__DIR__."/../../resources/assets/compiled/eyewitness.js')); ?>\"></script>";
        });
    }

    /**
     * Register notification directives.
     *
     * @return void
     */
    public function registerNotificationDirectives()
    {
        Blade::directive('eyewitness_error', function($message) {
            return $this->baseNotification($message, 'bg-red', 'bold-remove', 'svgcolor-red');
        });

        Blade::directive('eyewitness_success', function($message) {
            return $this->baseNotification($message, 'bg-green', 'check-simple', 'svgcolor-green');
        });

        Blade::directive('eyewitness_warning', function($message) {
            return $this->baseNotification($message, 'bg-orange', 'alert-exc', 'svgcolor-orange');
        });

        Blade::directive('eyewitness_info', function($message) {
            return $this->baseNotification($message, 'bg-blue', 'alert-i', 'svgcolor-blue');
        });

        Blade::directive('eyewitness_error_no_escape', function($message) {
            return $this->baseNotification($message, 'bg-red', 'bold-remove', 'svgcolor-red', false);
        });

        Blade::directive('eyewitness_success_no_escape', function($message) {
            return $this->baseNotification($message, 'bg-green', 'check-simple', 'svgcolor-green', false);
        });

        Blade::directive('eyewitness_warning_no_escape', function($message) {
            return $this->baseNotification($message, 'bg-orange', 'alert-exc', 'svgcolor-orange', false);
        });

        Blade::directive('eyewitness_info_no_escape', function($message) {
            return $this->baseNotification($message, 'bg-blue', 'alert-i', 'svgcolor-blue', false);
        });

        Blade::directive('eyewitness_tutorial', function($message) {
            $s = "<?php if (config('eyewitness.display_helpers', true)): ?>";
            $s .=  $this->baseNotification($message, 'bg-blue', 'alert-i', 'svgcolor-blue');
            $s .= "<?php endif; ?>";
            return $s;
        });
    }

    /**
     * The base notification to use.
     *
     * @param  string  $message
     * @param  string  $bgColor
     * @param  string  $icon
     * @param  string  $iconColor
     * @param  bool  $escape
     * @return string
     */
    protected function baseNotification($message, $bgColor, $icon, $iconColor, $escape = true)
    {
        $s  = "<?php echo '<div class=\"flex ".$bgColor." bg-circuit bg-md rounded text-center text-white text-sm font-bold mb-6 px-4 py-3 shadow\" role=\"alert\">";
        $s .= '<div class="bg-white rounded shadow-lg -mt-6 w-8 h-8">';
        $s .= '<div class="h-full flex justify-center items-center '.$iconColor.'">';
        $s .= $this->getIconString($icon, '', 24, 24);
        $s .= '</div></div>';
        if ($escape) {
            $s .= "<p class=\"ml-3\">'.e($message).'</p>";
        } else {
            $s .= "<p class=\"ml-3\">'.$message.'</p>";
        }
        $s .= "</div>'; ?>";

        return $s;
    }

    /**
     * Register svg directives.
     *
     * To be used @eyewitness_svg('icon_name', 'class', 'width', 'height')
     *
     * @return void
     */
    public function registerSvgDirectives()
    {
        Blade::directive('eyewitness_svg', function($arguments) {
            $arguments = trim(trim($arguments, "("), ")");
            $arguments = explode(',', $arguments.',');

            $icon = trim($arguments[0], "' ");
            $class = trim(isset($arguments[1]) ? $arguments[1] : '', "' ");
            $width = trim(isset($arguments[2]) ? $arguments[2] : '', "' ");
            $height = trim(isset($arguments[3]) ? $arguments[3] : '', "' ");

            return $this->getIconString($icon, $class, $width, $height);
        });
    }

    /**
     * Register image directives.
     *
     * To be used @eyewitness_img('image_name.ext')
     *
     * @return void
     */
    public function registerImageDirectives()
    {
        Blade::directive('eyewitness_img', function($file) {
            $file = trim(trim($file, "("), ")");
            $string = $this->loadFile(__DIR__.'/../../resources/assets/images/'.trim($file, "'"));
            return '<img src="data:image/x-icon;base64,'.base64_encode($string).'"/>';
        });

        Blade::directive('eyewitness_img_raw_base64', function($file) {
            $file = trim(trim($file, "("), ")");
            $string = $this->loadFile(__DIR__.'/../../resources/assets/images/'.trim($file, "'"));
            return base64_encode($string);
        });
    }

    /**
     * The icon svg as a string.
     *
     * @param  string  $icon
     * @param  string  $class
     * @param  int  $width
     * @param  int  $height
     * @return string
     */
    public function getIconString($icon, $class = '', $width = '', $height = '')
    {
        $string = $this->loadFile(__DIR__.'/../../resources/assets/icons/'.$icon.'.svg');
        $string = substr_replace($string, ' class="'.$class.'"', 4, 0);

        if ($width) {
            $string = substr_replace($string, 'width="'.$width.'"', strpos($string, 'width='), 10);
            $string = substr_replace($string, 'height="'.$height.'"', strpos($string, 'height='), 11);
        }

        return $string;
    }

    /**
     * Get the file.
     *
     * @param  string  $path
     * @return string
     */
    public function loadFile($path)
    {
        try {
            return file_get_contents($path);
        } catch (Exception $e) {
            return '';
        }
    }
}
