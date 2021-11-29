<?php

namespace App\Http\Repositories\Master;

use App\Models\Master\Supplier;

class SupplierRepository
{
    public function getSupplierSearch($search=null)
    {
        return Supplier::where('namaSupplier', 'like', '%'.$search.'%')
                        ->orWhere('alamatSupplier', 'like', '%'.$search.'%')
                        ->orWhere('kodeSupplier', 'like', '%'.$search.'%')
                        ->orWhere('jenisSupplier', 'like', '%'.$search.'%')
                        ->latest('kodeSupplier')
                        ->paginate(10,['*'], 'supplierpage');
    }
}
