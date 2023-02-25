<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = ['nome', 'imagem'];
    use HasFactory;

    public function rules(){
        $regras = [
            'nome' => 'required|unique:marcas,nome,'.$this->id,
            'imagem' => 'required|file|mimes:png'
        ];

        return $regras;

    }

    public function feedback(){
        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do Tipo PNG'
        ];

        return $feedback;
    }

    public function modelos() {
        return $this->hasMany('App\Models\Modelo');
    }
}
