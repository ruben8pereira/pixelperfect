<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Report $report)
    {
        // Check permission to view report (needed to comment)
        //$this->authorize('view', $report);

        $request->validate([
            'content' => 'required|string',
            'include_in_pdf' => 'sometimes|boolean',
        ]);

        try {
            ReportComment::create([
                'report_id' => $report->id,
                'user_id' => Auth::id(),
                'content' => $request->content,
                'include_in_pdf' => $request->has('include_in_pdf'),
            ]);

            return redirect()->route('reports.show', $report)
                ->with('success', 'Comment added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while adding the comment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @param  \App\Models\ReportComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report, ReportComment $comment)
    {
        // Check if the user owns the comment or is an admin/organization
        if (Auth::id() !== $comment->user_id &&
            !in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->back()
                ->with('error', 'You do not have permission to update this comment.');
        }

        $request->validate([
            'content' => 'required|string',
            'include_in_pdf' => 'sometimes|boolean',
        ]);

        try {
            $comment->update([
                'content' => $request->content,
                'include_in_pdf' => $request->has('include_in_pdf'),
            ]);

            return redirect()->route('reports.show', $report)
                ->with('success', 'Comment updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the comment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Report  $report
     * @param  \App\Models\ReportComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report, ReportComment $comment)
    {
        // Check if the user owns the comment or is an admin/organization
        if (Auth::id() !== $comment->user_id &&
            !in_array(Auth::user()->role->name, ['Administrator', 'Organization'])) {
            return redirect()->back()
                ->with('error', 'You do not have permission to delete this comment.');
        }

        try {
            $comment->delete();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Comment deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the comment: ' . $e->getMessage());
        }
    }
}
