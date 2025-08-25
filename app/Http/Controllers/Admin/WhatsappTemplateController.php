<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WhatsappTemplateController extends Controller
{
    public function index()
    {
        $templates = WhatsappTemplate::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'status' => true,
            'data' => $templates,
            'message' => 'WhatsApp templates retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'template_content' => 'required|string',
            'type' => 'required|in:order_notification,custom,marketing',
            'description' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $slug = Str::slug($request->name);
        $counter = 1;
        
        while (WhatsappTemplate::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $counter;
            $counter++;
        }

        $template = WhatsappTemplate::create([
            'name' => $request->name,
            'slug' => $slug,
            'template_content' => $request->template_content,
            'variables' => $request->variables ?? [],
            'type' => $request->type,
            'is_active' => $request->is_active ?? true,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'data' => $template,
            'message' => 'WhatsApp template created successfully'
        ], 201);
    }

    public function show($id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $template,
            'message' => 'WhatsApp template retrieved successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'template_content' => 'required|string',
            'type' => 'required|in:order_notification,custom,marketing',
            'description' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $template->update([
            'name' => $request->name,
            'template_content' => $request->template_content,
            'variables' => $request->variables ?? [],
            'type' => $request->type,
            'is_active' => $request->is_active ?? true,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'data' => $template,
            'message' => 'WhatsApp template updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        $template->delete();

        return response()->json([
            'status' => true,
            'message' => 'WhatsApp template deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        $template->update([
            'is_active' => !$template->is_active
        ]);

        return response()->json([
            'status' => true,
            'data' => $template,
            'message' => 'WhatsApp template status updated successfully'
        ]);
    }

    public function preview(Request $request, $id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'variables' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $previewContent = $template->replaceVariables($request->variables);

        return response()->json([
            'status' => true,
            'data' => [
                'original_template' => $template->template_content,
                'preview_content' => $previewContent,
                'variables_used' => $request->variables
            ],
            'message' => 'Template preview generated successfully'
        ]);
    }

    public function getVariables($id)
    {
        $template = WhatsappTemplate::find($id);
        
        if (!$template) {
            return response()->json([
                'status' => false,
                'message' => 'WhatsApp template not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'variables' => $template->variables_list,
                'template_type' => $template->type
            ],
            'message' => 'Template variables retrieved successfully'
        ]);
    }

    public function getByType($type)
    {
        $templates = WhatsappTemplate::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $templates,
            'message' => 'WhatsApp templates retrieved successfully'
        ]);
    }
}
