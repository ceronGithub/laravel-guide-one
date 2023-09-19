<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Classes\Payments\ProcessPayments;
use App\Models\Store;
use App\Requests\Transaction\CreateTransactionRequest;
use App\Requests\Transaction\GetTransactionRequest;
use App\Requests\Transaction\PaymentRequest;
use App\Resources\TransactionResource;
use App\Traits\Api\ApiResponses;
use App\Traits\DB\MachineTable;
use App\Traits\DB\PaymentTable;
use App\Traits\DB\ProductTable;
use App\Traits\DB\TransactionTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
class TransactionController extends Controller
{
    use ApiResponses, TransactionTable, ProductTable, PaymentTable;

    public function getPurchaseOrderDetails(GetTransactionRequest $request)
    {
        $request->validated();

        try {
            $data = $this->getPurchaseOrderData($request->purchase_order_id, false);

            return $this->generateSuccessResponse(
                'Successfully fetched purchase order details.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Failed to fetch purchase order details.',
                $e
            );
        }
    }

    public function createPurchaseOrder(CreateTransactionRequest $request)
    {
        $request->validated();

        try {
            $productData = $this->getProductData($request->product_id);

            if ($productData == null) {
                return $this->generateFailedResponse(
                    'Invalid Product ID.',
                    null,
                    400
                );
            }

            if ($this->getMachineData($request->machine_address_id) == null) {
                return $this->generateFailedResponse(
                    'Invalid Machine Address ID. This machine is not yet registered.',
                    null,
                    400
                );
            }

            // Add trailing 0 for single digits
            $request->machine_slot_address_id = substr("0{$request->machine_slot_address_id}", -2);

            $machineSlotData = $this->getMachineSlotDataViaAddressAndSlot(
                $request->machine_address_id,
                $request->machine_slot_address_id
            );

            if ($machineSlotData == null) {
                return $this->generateFailedResponse(
                    'Invalid Machine Slot ID. This Machine Slot is not yet registered.',
                    null,
                    400
                );
            }

            if ($this->checkMachineSlotOutofStock($machineSlotData)) {
                return $this->generateFailedResponse(
                    'Failed to create purchase order due to no stocks available.',
                    null,
                    400
                );
            }

            $data = $this->addProductData($request->all(), $productData);
            $data = $this->setAsRequestOrder($data);
            $data = $this->addSerial($data, $machineSlotData->getFirstItemSerial());
            $data = $this->addExpiryDateTime($data);
            $data = $this->addPurchaseOrder($data);



            //Store Data
            $store = Store::where('id', $machineSlotData->machine->store_id)->first();

            $data['store_code']   = $store->store_code;
            $data['product_code'] = $productData->product_code;
            $data['product_id']   = $productData->id;

            $data = $this->insertPurchaseOrder($data);

            return $this->generateSuccessResponse(
                'Successfully created purchase order.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Failed to create purchase order.',
                $e
            );
        }
    }

    public function createPayment(PaymentRequest $request)
    {
        $request->validated();
        try {

            $processPayment = new ProcessPayments();
            $cleanData = $processPayment->process($request);
            if(isset($cleanData['status']) && !$cleanData['status']){
                return $this->generateFailedResponse(
                    $cleanData['message'],
                    null,
                    $cleanData['code']
                );
            }

            return $this->generateSuccessResponse(
                'Successfully logged payment.',
                $cleanData
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Failed to log payment.',
                $e
            );
        }
    }
}
