<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Requests\Machine\CheckMachinePrinterStatusRequest;
use App\Requests\Machine\CheckMachineRegisteredRequest;
use App\Requests\Machine\GetMachineSlotRequest;
use App\Requests\Machine\RegisterMachineRequest;
use App\Requests\Machine\RegisterMachineSlotRequest;
use App\Requests\Machine\ResetSlotRequest;
use App\Resources\MachineSlotResource;
use App\Traits\Api\ApiResponses;
use App\Traits\DB\MachineTable;
use App\Traits\DB\UserTable;

class MachineController extends Controller
{
    use ApiResponses, MachineTable, UserTable;

    public function checkMachineID(CheckMachineRegisteredRequest $request)
    {
        $request->validated();

        try {
            $data = $this->getMachineData($request->machine_address_id);

            if ($data == null)
                return $this->generateFailedResponse(
                    'This machine is not yet registered.',
                    null,
                    400
                );

            return $this->generateSuccessResponse(
                'This machine is already registered.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Server Error. Please contact the developers.',
                $e
            );
        }
    }

    public function getVideoIdle(){
        return $this->generateSuccessResponse(
            'Get video idle is successful.',
            ['video_link' => url("/storage/video/idle/idle-video.mp4")]
        );
    }

    public function registerMachineID(RegisterMachineRequest $request)
    {
        $request->validated();

        try {
            $userData = $this->getUserData(auth()->user()->id);
            $data = $this->insertMachineId($request, $userData);

            if ($data == null)
                return $this->generateFailedResponse(
                    'Machine registration failed',
                    null,
                    400
                );

            return $this->generateSuccessResponse(
                'This machine is successfully registered.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Machine registration failed',
                $e
            );
        }
    }

    public function registerMachineSlot(RegisterMachineSlotRequest $request)
    {
        $request->validated();

        try {
            $userData = $this->getUserData(auth()->user()->id);

            if ($this->getMachineData($request->machine_address_id) == null) {
                return $this->generateFailedResponse(
                    'Invalid Machine Address ID. This machine is not yet registered.',
                    null,
                    400
                );
            }

            $data = $this->insertMachineSlot($request, $userData);

            if ($data == null)
                return $this->generateFailedResponse(
                    'Machine slot registration failed',
                    null,
                    400
                );

            return $this->generateSuccessResponse(
                'This machine slot is successfully registered.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Machine slot registration failed',
                $e
            );
        }
    }

    public function getMachineSlotList(GetMachineSlotRequest $request)
    {
        $request->validated();

        try {
            $data = $this->getMachineData($request->machine_address_id);

            if ($data == null)
                return $this->generateFailedResponse(
                    'Invalid Machine Address ID. This machine is not yet registered.',
                    null,
                    400
                );

            $data = $this->getMachineSlotData1(
                $request->machine_address_id,
                $request->category_id_filter,
                $request->sort_filter
            );

            // //Transform Data
            $data = MachineSlotResource::make($data);

            return $this->generateSuccessResponse(
                'Get Machine Slot List Successs',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Get Machine Slot List Failed',
                $e
            );
        }
    }

    public function sendPrinterStatus(CheckMachinePrinterStatusRequest $request){
        $request->validated();

        if($request->error){
            throw \Exception();
        }

        try {
            $data = $this->getMachineData($request->machine_address_id);

            if ($data == null)
                return $this->generateFailedResponse(
                    'This machine is not yet registered.',
                    null,
                    400
                );

            $this->updatePrinterStatus($data, $request->all()['printer_status']);

            return $this->generateSuccessResponse(
                'Sucessfully sent the printer status.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Server Error. Please contact the developers.',
                $e
            );
        }
    }

    public function sendMachineStatus(CheckMachineRegisteredRequest $request){
        $request->validated();

        try {
            $data = $this->getMachineData($request->machine_address_id);

            if ($data == null)
                return $this->generateFailedResponse(
                    'This machine is not yet registered.',
                    null,
                    400
                );

            $this->updateLastUpdated($data);


            return $this->generateSuccessResponse(
                'Sucessfully sent the machine status.',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Server Error. Please contact the developers.',
                $e
            );
        }
    }

    public function resetSlot(ResetSlotRequest $request)
    {
        $request->validated();

        try {
            $data = $this->resetSlotTrait($request);

            if ($data == null)
                return $this->generateFailedResponse(
                    'Invalid Machine Address ID. This machine is not yet registered.',
                    null,
                    400
                );

            //Transform Data
            // $cleanData = MachineSlotResource::make($data);

            return $this->generateSuccessResponse(
                'Reset Slot Successful',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Reset Slot Unsuccessful',
                $e
            );
        }
    }
}
