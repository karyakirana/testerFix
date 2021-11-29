<?php

namespace App\Http\Repositories\Master;

use App\Models\Master\Customer;

class CustomerRepository
{
    public function getCustomerSearch($search=null)
    {
        return Customer::where('nama_cust', 'like', '%'.$search.'%')
            ->orWhere('addr_cust', 'like', '%'.$search.'%')
            ->orWhere('id_cust', 'like', '%'.$search.'%')
            ->latest('id_cust')
            ->paginate(10, ['*'], 'customerpage');
    }
}
