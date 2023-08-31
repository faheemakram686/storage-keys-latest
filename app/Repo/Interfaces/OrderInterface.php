<?php
namespace App\Repo\Interfaces;

interface OrderInterface{
    public function saveOrder($request);
    public function getAllOrders();
    public function deleteOrder($id);
    public function editOrder($id);
    public function getOrder($id);
    public function updateOrder($request);
    public function convertOrder($request);
    public function getOrderItems($id);
    public function getCustomerOrder($id);
    public function getCustomerOrderApi($id);
    public function getOrderProducts($id);



}
