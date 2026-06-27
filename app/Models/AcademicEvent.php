<?php
namespace App\Models;

class AcademicEvent extends BaseModel
{
    protected string $table = 'academic_events';
    protected array $fillable = ['title', 'start_date', 'end_date', 'event_type', 'description'];

    /**
     * Get events formatted for FullCalendar
     */
    public function getForCalendar(): array
    {
        $sql = "SELECT id, title, start_date as start, end_date as end, event_type, description FROM {$this->table}";
        $events = $this->db->query($sql)->fetchAll();
        
        // Format for FullCalendar
        $formatted = [];
        foreach ($events as $event) {
            // Adjust end date for exclusive end date in FullCalendar
            $end = (new \DateTime($event['end']))->modify('+1 day')->format('Y-m-d');
            
            $color = '#4f46e5'; // default primary
            if ($event['event_type'] === 'libur') $color = '#ef4444'; // danger
            elseif ($event['event_type'] === 'ujian') $color = '#f59e0b'; // warning
            elseif ($event['event_type'] === 'perkuliahan') $color = '#10b981'; // success
            
            $formatted[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start'],
                'end' => $end,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'description' => $event['description']
                ]
            ];
        }
        
        return $formatted;
    }
}
