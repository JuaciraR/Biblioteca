<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use App\Traits\Trackable;

use Livewire\Attributes\Layout; 

class OrderList extends Component
{
    use WithPagination,Trackable;

    public $statusFilter = '';

    /**
     * Renders the admin orders list with filtering.
     * O atributo #[Layout] substitui o método ->layout() no final do render.
     */
    #[Layout('layouts.app')] // Adicionado: Define o layout de forma compatível com o editor
    public function render()
    {
        $query = Order::with(['user', 'items.book'])->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

         
        return view('livewire.admin.order-list', [
            'orders' => $query->paginate(15)
        ]); // Removido o ->layout() daqui para evitar o erro PHP0418
    }

    /**
     * Updates the status of a specific order.
     * Mantido exatamente como o original.
     */
    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $oldStatus = $order->status;
            $order->update(['status' => $status]);
            $this->logAudit(
                'Orders', 
                $orderId, 
                "Updated Order #{$orderId} status from {$oldStatus} to {$status}"
            );
            session()->flash('success', "Order #{$order->order_number} status updated.");
        }
    }
}