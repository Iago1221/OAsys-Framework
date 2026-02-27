<?php

namespace Framework\Infrastructure\MVC\View\Components\Calendar;

use Framework\Infrastructure\MVC\View\Components\IComponent;

class Calendar implements IComponent
{
    protected $routeGetSchedules = null;
    protected $routeGetEvents = null;

    /** @var CalendarSchedule[] */
    protected $schedules = [];

    /** @var CalendarEvent[] */
    protected $events = [];

    protected $callbacks = [
        'onDayClick' => null,
        'onEventClick' => null,
        'onScheduleChange' => null
    ];

    public function setRouteGetSchedules(string $routeGetSchedules)
    {
        $this->routeGetSchedules = $routeGetSchedules;
    }

    public function setRouteGetEvents(string $routeGetEvents)
    {
        $this->routeGetEvents = $routeGetEvents;
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
                'routeGetSchedules' => $this->routeGetSchedules,
                'routeGetEvents' => $this->routeGetEvents,
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
