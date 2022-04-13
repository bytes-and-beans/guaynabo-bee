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
            new \Twig_SimpleFunction('calenderEntriesWeekly',[$this, 'calenderEntriesWeekly']),
        ];
    }
    /**
     * Returns an array of all events in a year.
     * 
     * array structure is:  
     * //The year array
     *   {
     *      //the number of this month
     *      1 => {
     *          //the number of this day
     *          16 => {
     *              event entry
     *              event entry
     *              event entry
     *          }
     *      }
     *   } 
     */
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
    /** Returns an array of the events in a given week  
     * 
     * The default is for the first day of the week returned to be today,  
     * you may enter an integer that will offset the returned result by a given 
     * number of days either before, or after today.
     * 
     * array structure
     * array of days
     * {
     *      //Date in dd-mm-yyyy
     *      01-05-1982: {
     *          // Array of event entries for this day
     *      }
     * }
     * 
     *  @param int $dayOffset
     */
    public function calenderEntriesWeekly(int $dayOffset=0)
    {
        $today = new \DateTimeImmutable();
        $weekStartTime = $today -> modify('00:00:00') -> format(\DateTime::ATOM);
        $weekEndTime   = $today -> modify($dayOffset . " days") -> modify("23:59:59")-> format(\DateTime::ATOM);

        $entryQuery = Entry::find()
            -> section('eventsCalendar')
            -> startTime("<=".$weekEndTime) 
            -> endTime(">=".$weekStartTime);
        // Year is an array of months, each month is an array of days, each day an array of events
        $weekArray = array();
        $dayInterval = new \DateInterval('P1D');
        foreach ($entryQuery->all() as  $event) {
            $ending = $event -> endTime != $event -> startTime ? $event -> endTime : date_modify($event->endTime, '23:59:59');
            $eventPeriod = new \DatePeriod($event->startTime, $dayInterval, $event->endTime);
            foreach ($eventPeriod as $day) {
                $date = $date -> format("d-m-Y");
                if (! array_key_exists($date, $weekArray[$date])) {
                    $weekArray[$date] = array();
                }
                array_push($weekArray[$date],$event);
            }
        }
        return $weekArray;
    }
}

