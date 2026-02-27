<?php

namespace Framework\Infrastructure\MVC\View\Components\Calendar;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Calendar implements IComponent
{
    protected $routeGetschedules = null;
    protected $routeGetevents = null;

    /** @var CalendarSchedule[] */
    protected $schedules = [];

    /** @var CalendarEvent[] */
    protected $events = [];

    protected $callbacks = [
        'onDayClick' => null,
        'onEventClick' => null,
        'onScheduleChange' => null
    ];

    public function setRouteGetSchedules(string $routeGetschedules)
    {
        $this->routeGetschedules = $routeGetschedules;
    }

    public function setRouteGetEvents(string $routeGetevents)
    {
        $this->routeGetevents = $routeGetevents;
    }

    public function addSchedule(CalendarSchedule $schedule)
    {
        $this->schedules[] = $schedule;
    }

    public function addEvent(CalendarEvent $event)
    {
        $this->events[] = $event;
    }

    protected function on(string $event, string $func)
    {
        $this->callbacks['on' . ucfirst($event)] = $func;
    }

    public function onDayClick(string $func)
    {
        $this->on('dayClick', $func);
    }

    public function onEventClick(string $func)
    {
        $this->on('eventClick', $func);
    }

    public function onScheduleChange(string $func)
    {
        $this->on('scheduleChange', $func);
    }

    public function toArray(): array
    {
        return [
            'component' => 'CalendarComponent',
            'CalendarComponent' => [
                'routeGetschedules' => $this->routeGetschedules,
                'routeGetevents' => $this->routeGetevents,
                'schedules' => array_values(array_map(function ($schedule) {
                    return $schedule->toArray();
                }, $this->schedules)),
                'events' => array_values(array_map(function ($event) {
                    return $event->toArray();
                }, $this->events)),
                'callbacks' => json_encode($this->callbacks)
            ]
        ];
    }
}
