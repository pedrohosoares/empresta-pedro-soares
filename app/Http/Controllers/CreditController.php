<?php

namespace App\Http\Controllers;

use App\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public $newData = array();

    public function processArray($data, $values)
    {
        //Calc value of parcel + tax
        $parcel = ($data['valor_emprestimo'] / $data['parcela']) + ($data['valor_emprestimo'] * $values['coeficiente'] );

        //Create new index
        if (!isset($this->newData[$values['instituicao']])) {
            $this->newData[$values['instituicao']] = array();
        }
        //Increment array
        $this->newData[$values['instituicao']][] = array(
            "taxa" => $values['taxaJuros'],
            "parcelas" => $data['parcela'],
            "valor_parcela" => number_format($parcel, 2, ',', ''),
            "convenio" => $values["convenio"]
        );
    }

    public function organizeData($simulate,$data){

        foreach ($simulate as $values) {

            if (in_array($values['instituicao'], $data['instituicoes']) and in_array($values['convenio'], $data['convenios'])) {
                $this->processArray($data, $values);
            }

            if (in_array($values['instituicao'], $data['instituicoes']) and empty($data['convenios'])) {
                $this->processArray($data, $values);
            }

            if (empty($data['instituicoes']) and in_array($values['convenio'], $data['convenios'])) {
                $this->processArray($data, $values);
            }

            if (empty($data['instituicoes']) and empty($data['convenios'])) {
                $this->processArray($data, $values);
            }
        }

    }

    public function calc(Request $request)
    {

        try {
            $validate = $request->validate([
                'valor_emprestimo' => 'required'
            ]);

            $parcelas = empty($request->parcela)?1:$request->parcela;
            $data = array(
                'valor_emprestimo' => $request->valor_emprestimo,
                'instituicoes' => $request->instituicoes,
                'convenios' => $request->convenios,
                'parcela' => $parcelas
            );

            $simulate = $this->simulator();

            $this->organizeData($simulate,$data);

            $this->newData = response($this->newData)->header('Content-Type','application/json')->setStatusCode(200);

        } catch (\Throwable $th) {

            $this->newData = response('')->header('Content-Type', 'application/json')->setStatusCode(500, 'Fail loading data');

        }

        return $this->newData;

    }

    public function simulator()
    {
        try {
            $data = Credit::view();
        } catch (\Throwable $th) {
            $data = response('')->header('Content-Type', 'application/json')->setStatusCode(500, 'Fail loading data');
        }
        return $data;
    }
}
