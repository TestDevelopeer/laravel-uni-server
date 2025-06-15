<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalUpdateRequest;
use App\Http\Resources\JournalResource;
use App\Models\Journal;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $journal = Journal::where('user_id', $request->user()->id)->first();

        return response()->json([
            'journal' => new JournalResource($journal),
        ]);
    }

    public function update(JournalUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            Journal::updateOrCreate(
                ['user_id' => $request->user()->id],
                [
                    'user_id' => $request->user()->id,
                    'code' => $data['code'],
                ]
            );

            return response()->json(['success' => 'Последний журнал успешно обновлен']);
        } catch (QueryException $e) {
            return response()->json(['error' => "Database error CODE {$e->getCode()}: {$e->getMessage()}"], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => "Error: {$e->getMessage()}"], $e->getCode());
        }
    }
}
