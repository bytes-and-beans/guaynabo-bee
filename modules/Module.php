<?php
namespace modules;

use Craft;
use craft\elements\Entry;

/**
 * Custom module class.
 *
 * This class will be available throughout the system via:
 * `Craft::$app->getModule('my-module')`.
 *
 * You can change its module ID ("my-module") to something else from
 * config/app.php.
 *
 * If you want the module to get loaded on every request, uncomment this line
 * in config/app.php:
 *
 *     'bootstrap' => ['my-module']
 *
 * Learn more about Yii module development in Yii's documentation:
 * http://www.yiiframework.com/doc-2.0/guide-structure-modules.html
 */
class Module extends \yii\base\Module
{
    /**
     * Initializes the module.
     */
    public function init()
    {
        // Set a @modules alias pointed to the modules/ directory
        Craft::setAlias('@modules', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'modules\\console\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\controllers';
        }

        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $extension = new CalendarDBReader();
            Craft::$app->view->registerTwigExtension($extension);
        }
    }
}

class CalendarDBReader extends \Twig\Extension\AbstractExtension
{

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('calenderEntriesAnnual',[$this, 'calenderEntriesAnnual']),
        ];
    }
    public function calenderEntriesAnnual(int $year)
    {
        $yearStartTime = (new \DateTime('first day of January ' . $year)) -> format(\DateTime::ATOM);
        $yearEndTime   = (new \DateTime('23:59:59 last day of December' . $year)) -> format(\DateTime::ATOM);

        $entryQuery = Entry::find()
            -> section('eventsCalendar')
            -> startTime("<=".$yearEndTime) 
            -> endTime(">=".$yearStartTime);
        
        return "this year is " . $year . ".<br>It began at" . $yearStartTime . " and ended at " . $yearEndTime . "<br>There are " . $entryQuery -> count() . " entries in this year's calendar";
    }
}
