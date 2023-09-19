<?php

namespace App\Resources;

use App\Http\Classes\ShareContentHandler;
use App\Models\Category;
use App\Models\Machine;
use App\Models\MachineSlot;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Need help to convert Object to Dictionary
        $data = json_encode(parent::toArray($request), true);
        $data = json_decode($data, true);

        $finalData = [];

        for ($i = 0; $i < count($data); $i++) {
            foreach (array_keys($data[$i]) as $datum) {
                if (str_contains($datum, MachineSlot::TBL_NAME))
                    $finalData[$i][str_replace(MachineSlot::TBL_NAME . "_", "", $datum)] = $data[$i][$datum];

                if (str_contains($datum, Product::TBL_NAME))
                    $finalData[$i][MachineSlot::OBJECT_PRODUCT][str_replace(Product::TBL_NAME . "_", "", $datum)] = $data[$i][$datum];

                if (str_contains($datum, Category::TBL_NAME))
                    $finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::OBJECT_CATEGORY][str_replace(Category::TBL_NAME . "_", "", $datum)] = $data[$i][$datum];

                if (str_contains($datum, Machine::TBL_NAME))
                    $finalData[$i][MachineSlot::OBJECT_MACHINE][str_replace(Machine::TBL_NAME . "_", "", $datum)] = $data[$i][$datum];
            }

            $finalData[$i][MachineSlot::COLUMN_SERIAL] = $this->getFirstItemSerial($finalData[$i]['serial']);

            $finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_IMG] = json_decode($finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_IMG], true);
            $finalData[$i][MachineSlot::OBJECT_PRODUCT]['main_image_full_path'] = url('') . '/' . $finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_IMG][0];

            for ($j = 0; $j < count($finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_IMG]); $j++) {
                $finalData[$i][MachineSlot::OBJECT_PRODUCT]['images_full_path'][$j] = url('') . '/' . $finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_IMG][$j];
            }

            unset($finalData[$i][MachineSlot::COLUMN_PRODUCT_ID]);
            unset($finalData[$i][MachineSlot::OBJECT_PRODUCT][Product::COLUMN_CATEGORY_ID]);
            unset($finalData[$i][MachineSlot::COLUMN_MACHINE_ID]);
        }

        return $finalData;
    }

    public function getFirstItemSerial($serial){
        $array = array_map('trim', explode(',', $serial));

        $firstItem = isset($array[0]) ? $array[0] : null;

        return $firstItem;
    }
}
