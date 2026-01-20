<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    /**
     * Store a newly created attachment
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
            'document_type' => 'nullable|string'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('attachments', 'public');

            $attachment = Attachment::create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'document_type' => $request->document_type,
                'attachable_type' => $request->attachable_type,
                'attachable_id' => $request->attachable_id,
                'uploaded_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transmission complete. Document vaulted.',
                'attachment' => $attachment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transmission failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified attachment
     */
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Optional: Check if the user has permission to delete this

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return redirect()->back()->with('success', 'Document dismantled from vault.');
    }
}
