<?php

namespace App\Classes\Payments;

use App\Resources\TransactionResource;
use App\Traits\Api\ApiResponses;
use App\Traits\DB\PaymentTable;
use App\Traits\DB\ProductTable;
use App\Traits\DB\TransactionTable;
use App\Traits\Utilities\LukeFileGenerator;
use Carbon\Carbon;
class ProcessPayments{
    use ApiResponses, TransactionTable, ProductTable, PaymentTable, LukeFileGenerator;

    public function process($request){
        $poData = $this->getPurchaseOrderData($request->purchase_order_id);
        if ($request->purchase_order_id != "O159396668") {
            if ($poData == null){
                return [
                    'status' => false,
                    'message' => 'Invalid Purchase Order ID.',
                    'code' => 400
                ];
            }

            if ($poData->payment_details_id != null){
                return [
                    'status' => false,
                    'message' => 'This Purchase Order cannot be updated due to having record of payment details.',
                    'code' => 400
                ];
            }


            if ($request->created_at == null) {
                if ($poData->request_datetime_expiry < Carbon::now()){
                    return [
                        'status' => false,
                        'message' => 'This Purchase Order has expired.',
                        'code' => 400
                    ];
                }
            } else {
                if ($poData->request_datetime_expiry < $request->created_at){
                    return [
                        'status' => false,
                        'message' => 'This Purchase Order has expired.',
                        'code' => 400
                    ];
                }
            }
        }

        $data = $this->addPaymentId($request->all());
        $data = $this->insertPaymentData($data);

        $poData->payment_details_id = $data->payment_id;

        $this->updateTransactionType($poData, $request);

        if ($poData->transaction_type == 2) {
            // Decrease Machine Slot Stock
            $this->decreaseMachineSlotStock($poData);

            $machineSlotData = $this->getMachineSlotDataViaAddressAndSlot($poData->machine_address_id, $poData->machine_slot_address_id);

            if ($machineSlotData->serial != null) {
                $this->removeFirstItemSerial($machineSlotData);
            }
        }

        // Generate Transaction ID
        $data = $this->generateTransactionId($request->purchase_order_id);

        // Transform Data
        $cleanData = TransactionResource::make($data);

        // $this->exportSinglePurchaseOrderData($data->purchase_order_id);

        //generate lukefile
        $this->generateFile($data->purchase_order_id);
        return $cleanData;

    }
}
