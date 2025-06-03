<?php
namespace App\Repo\Interfaces;

interface ProductInterface{
    public function saveProduct($request);

    public function getProduct();
    public function deleteProduct($id);
    public function editProduct($id);
    public function updateProduct($request);
    public function getProductDetail($request);
    public function getProductPaginate();
    public function syncWithQuickbook();


}
