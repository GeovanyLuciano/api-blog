<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ComentStoreRequest;
use App\Http\Requests\V1\ComentUpdateRequest;
use App\Http\Resources\V1\ComentResource;
use App\Models\Coment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny",Coment::class);
        $coments = Coment::with(["user", "post"])->where("user_id", $request->user()->id)->latest()->paginate(5);
        return ComentResource::collection($coments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComentStoreRequest $request)
    {
        $coment = new Coment();
        $coment->user_id = $request->user()->id;
        $coment->post_id = $request->post_id;
        $coment->coment = $request->coment;
        $coment->save();
        return new ComentResource($coment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coment = Coment::with(["user"])->findOrFail($id);
        $this->authorize("view",$coment);
        return new ComentResource($coment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComentUpdateRequest $request, string $id)
    {
        $coment = Coment::findOrFail($id);
        $this->authorize("update",$coment);
        $coment->coment = $request->coment;
        $coment->post_id = $request->post_id;
        $coment->save();
        return new  ComentResource($coment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coment = Coment::findOrFail($id);
        $this->authorize("delete",$coment);
        $coment->delete();
        return response()->noContent();
    }
}
