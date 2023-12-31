<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Interfaces\SchoolInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequests\StoreRequest;
use App\Http\Requests\SchoolRequests\UpdateRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Services\SchoolService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ApiSchoolController extends Controller
{
    private SchoolInterface $school;
    private SchoolService $service;
    public function __construct(SchoolInterface $schoolInterface, SchoolService $schoolService)
    {
        $this->service = $schoolService;
        $this->school = $schoolInterface;
    }
    public function index()
    {
        $data = $this->school->get();
        return ResponseHelper::success(SchoolResource::collection($data), trans('messages.success_get_all'));
    }
    public function store(StoreRequest $request)
    {
        $data = $this->service->create($request);
        $this->school->store($data);
        return ResponseHelper::success(null, trans('messages.success_create'));
    }
    public function show(School $school_api)
    {
        return ResponseHelper::success(SchoolResource::make($school_api), trans('messages.success_show'));
    }
    public function update(UpdateRequest $request, School $school_api)
    {
        $data = $this->service->update($school_api, $request);
        $this->school->update($school_api->id, $data);
        return ResponseHelper::success(null, trans('messages.success_update'));
    }
    public function destroy(School $school_api)
    {
        if (!$this->school->delete($school_api->id)) {
            return ResponseHelper::error(null, trans('messages.error_restrict'));
        }
        $this->service->remove($school_api->image);
        return ResponseHelper::success(null, trans('messages.success_delete'));
    }
}
