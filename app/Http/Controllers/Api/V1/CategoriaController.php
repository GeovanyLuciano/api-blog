<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoriaStoreRequest;
use App\Http\Requests\V1\CategoriaUpdateRequest;
use App\Http\Resources\V1\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = Categoria::with(["user"])->where("user_id", $request->user()->id)->latest()->paginate(10);
        return CategoriaResource::collection($categorias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaStoreRequest $request)
    {
        //Cadastro de uma categoria
        $categoria = new Categoria();
        $categoria->name = $request->name;
        $categoria->slug = $request->slug;
        $categoria->description = $request->description;
        $categoria->user_id = $request->user()->id;
        $categoria->save();
        return new CategoriaResource($categoria);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $this->authorize("view", $categoria);
        $categoria->user;
        return new CategoriaResource($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaUpdateRequest $request, string $id)
    {
        $categoria = Categoria::findOrfail($id);
        $this->authorize("update", $categoria);
        $data = $request->validated();
        $categoria->update($data);
        return new CategoriaResource($categoria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $this->authorize("delete", $categoria);
        $categoria->delete();
        return response()->noContent();
    }
}
