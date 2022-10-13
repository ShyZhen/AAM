<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\BootstrapService;
use App\Http\Controllers\Controller;

class BootstrapController extends Controller
{
    private $bootstrapService;

    public function __construct(BootstrapService $bootstrapService)
    {
        $this->bootstrapService = $bootstrapService;
    }

    /**
     * 所有启动初始化的数据
     *
     * @param Request $request
     */
    public function start(Request $request)
    {
        return $this->bootstrapService->getStartData();
    }


}
