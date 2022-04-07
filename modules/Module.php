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
        // Year is an array of months, each month is an array of days, each day an array of events
        $yearArray = array();

        $dayInterval = new \DateInterval('P1D');
        foreach ($entryQuery->all() as  $event) {
            $ending = $event -> endTime != $event -> startTime ? $event -> endTime : date_modify($event->endTime, '23:59:59');
            $eventPeriod = new \DatePeriod($event->startTime, $dayInterval, $event->endTime);
            foreach ($eventPeriod as $day) {
                $month = $day -> format("n");
                $date = $day -> format("d");
                if (! array_key_exists($month, $yearArray)) {
                    $yearArray[$month] = array();
                }
                if (! array_key_exists($month, $yearArray[$month])) {
                    $yearArray[$month][$date] = array();
                }
                array_push($yearArray[$month][$date],$event);
            }
        }
        return $yearArray;
    }
}
