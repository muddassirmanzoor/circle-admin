<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'client_uuid';
    public $timestamps = true;
    protected $fillable = ['client_uuid', 'customer_id', 'freelancer_id', 'is_archive'];

    public function customer()
    {
        return $this->hasOne('\App\Customer', 'id', 'customer_id');
    }

    public function WalkinCustomer()
    {
        return $this->hasOne('\App\WalkinCustomer', 'id', 'customer_id');
    }

    protected function saveClient($data)
    {
        $save = Client::create($data);
        return !empty($save) ? $save->toArray() : [];
    }

    protected function getClient($freelancer_id = null, $customer_id = null)
    {
        $result = Client::where('freelancer_id', '=', $freelancer_id)->where('customer_id', '=', $customer_id)->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClientsColumn($freelancer_id = null, $column = 'id')
    {
        $result = Client::where('freelancer_id', '=', $freelancer_id)->pluck($column);
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClientDetails($column, $value)
    {
        $result = Client::where($column, '=', $value)->with('customer')->with('WalkinCustomer')->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClientsCount($column, $value)
    {
        return Client::where($column, '=', $value)->count();
    }

    protected function getClients($column, $value)
    {
        $result = Client::where($column, '=', $value)->get();
        return !empty($result) ? $result->toArray() : [];
    }
}
