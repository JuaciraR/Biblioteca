<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Traits\Trackable;

class OrderManagement extends Component
{
    use WithPagination, Trackable;

    public $search = '';
    public $statusFilter = '';

    /**
     * Updates the order status (e.g., from paid to shipped).
     * Mantido integralmente.
     */
    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => $newStatus]);
            $this->logAudit(
                'Orders', 
                $orderId, 
                "Admin changed order status to: {$newStatus}"
            );
            session()->flash('success', "Order #{$order->order_number} updated to {$newStatus}.");
        }
    }

    /**
     * Renders the order management view.
     */
    #[Layout('layouts.app')] // Adicionado aqui
    public function render()
    {
        $query = Order::with(['user', 'items.book'])
            ->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        // Removido o ->layout('layouts.app') do final
        return view('livewire.admin.order-management', [
            'orders' => $query->paginate(10)
        ]);
    }
}