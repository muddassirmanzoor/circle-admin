<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'professions';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'profession_uuid';
    public $timestamps = true;
    protected $fillable = [ 'profession_uuid', 'name', 'is_archive'];

    protected function getAllProfessions() {
        $query = Profession::where('is_archive', '=', 0)->orderByDesc('id');
        $result = $query->get();
        return (!empty($result)) ? $result->toArray() : [];
    }

    protected function saveProfession($data)
    {
        $result = Profession::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected  function updateProfession($column, $value, $data)
    {
        return Profession::where($column, '=', $value)->update($data);
    }

    protected  function getProfessionDataById($column, $value)
    {
        $result = Profession::where($column, '=', $value)->first();
        return !empty($result) ? $result->toArray() : [];
    }

}
