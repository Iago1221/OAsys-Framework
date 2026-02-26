<?php

namespace Framework\Infrastructure\MVC\View\Components\Calendar;

class CalendarEvent
{
    protected $id;
    protected $title;
    protected $date;
    protected $hour;
    protected $metadata = [];

    public function __construct($id, $title, $date, $hour)
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->hour = $hour;
    }

    public function set(string $param, mixed $value): void
    {
        $this->metadata[$param] = $value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'date' => $this->date,
            'hour' => $this->hour,
            'metadata' => $this->metadata
        ];
    }
}
