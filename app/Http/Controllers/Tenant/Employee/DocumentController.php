<?php

namespace App\Http\Controllers\Tenant\Employee;

use App\Filters\Tenant\DocumentFilter;
use App\Helpers\Core\Traits\FileHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Employee\DocumentRequest;
use App\Models\Tenant\Employee\Document;
use App\Services\Tenant\Employee\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use FileHandler;

    public function __construct(DocumentFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        return Document::query()
            ->filters($this->filter)
            ->with('createdBy:id,first_name,last_name')
            ->latest()
            ->paginate(\request('per_page', 10));
    }

    public function store(DocumentRequest $request)
    {

        $file_path = $this->uploadImage(
            request()->file('file'),
            'documents',
            null
        );

        Document::query()->create(array_merge(
            $request->only('user_id', 'name','expiry_date'),
            [
                'created_by' => auth()->id(),
                'path' => $file_path
            ]
        ));

        return created_responses('document');
    }


    public function show(Document $document)
    {
        return $document;
    }

    public function getDocumentsAPI()
    {
        try {
            $documents = Document::query()
                ->where('user_id', auth()->id())
                ->with('createdBy:id,first_name,last_name')
                ->latest()
                ->get()
                ->map(function (Document $document) {
                    $creator = $document->createdBy;

                    return [
                        'id' => (int) $document->id,
                        'name' => (string) ($document->name ?? ''),
                        'path' => (string) ($document->path ?? ''),
                        // Flutter DocumentListModel expects String user_id.
                        'user_id' => (string) $document->user_id,
                        'expiry_date' => $document->expiry_date
                            ? (string) $document->expiry_date
                            : null,
                        'created_at' => optional($document->created_at)->toJSON(),
                        'updated_at' => optional($document->updated_at)->toJSON(),
                        // Flutter expects created_by as a user object, never an int id.
                        'created_by' => [
                            'id' => (int) ($creator->id ?? 0),
                            'first_name' => (string) ($creator->first_name ?? ''),
                            'last_name' => (string) ($creator->last_name ?? ''),
                            'full_name' => (string) (
                                $creator->full_name
                                ?? trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? ''))
                            ),
                        ],
                    ];
                })
                ->values();

            return response()->json([
                'status' => true,
                'messege' => 'Success',
                'data' => $documents,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'messege' => $th->getMessage(),
                'data' => [],
            ], 500);
        }
    }


    public function update(Request $request, Document $document)
    {
        $file_path = $document->path;
        if ($request->hasFile('file')) {
            $this->deleteImage($document->path);
            $file_path = $this->uploadImage(
                request()->file('file'),
                'documents'
            );
        }

        $expiry = $request->expiry_date;
        if ($expiry === '' || $expiry === 'null' || $expiry === 'Invalid date') {
            $expiry = null;
        }

        $document->update([
            'name' => $request->name,
            'expiry_date' => $expiry,
            'path' => $file_path
        ]);

        return updated_responses('document');
    }


    public function destroy(Document $document)
    {
        $this->deleteImage($document->path);

        $document->delete();

        return deleted_responses('document');
    }
}
