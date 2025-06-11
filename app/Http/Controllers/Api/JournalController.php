<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JournalResource;
use App\Models\Journal;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $journal = Journal::where('user_id', $request->user()->id)->first();

        return response()->json([
            'journal' => $journal ? new JournalResource($journal) : null,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            Journal::updateOrCreate(['user_id' => $request->user()->id], [
                'user_id' => $request->user()->id,
                'code' => $request->code,
            ]);
        } catch (QueryException $e) {
            $errorCode = $e->getCode(); // Код ошибки SQL (зависит от СУБД)
            $errorMessage = $e->getMessage();
            return response()->json(['error' => 'Database error: ' . $errorMessage], $errorCode);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Последний журнал успешно обновлен'], );
    }
}
