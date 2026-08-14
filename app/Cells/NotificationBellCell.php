<?php

namespace App\Cells;

use App\Models\NotificationModel;
use CodeIgniter\View\Cells\Cell;

class NotificationBellCell extends Cell
{
    public int $unreadCount = 0;

    public function mount()
    {
        if(!session('logged_in')) {
            return;
        }

        $model = new NotificationModel();
        $result = $model->where('recipient_id', session('user')['id'])
                        ->where('is_read', 0)
                        ->countAllResults();
        
        $this->unreadCount = $result;
    }
}
