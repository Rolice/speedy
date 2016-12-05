<?php
namespace Rolice\Speedy\Http\Requests;

use App\Http\Requests\Request;
use Lang;

class PdfRequest extends Request
{

    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'waybill' => 'required',
        ];
    }

    public function attributes()
    {
        $fields = [
            'waybill',
        ];

        $result = [];

        foreach ($fields as $field) {
            $result[$field] = Lang::get("speedy::speedy.attributes.$field");
        }

        return $result;
    }

}